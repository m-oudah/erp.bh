<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\ArchiveFile;
use App\Models\ArchiveFileType;
use App\Models\ArchiveDocument;
use App\Models\ArchiveDocumentCategory;
use Illuminate\Support\Str;

class ImportOldArchive extends Command
{
    protected $signature = 'archive:import-old';
    protected $description = 'Import old archive data from erp_bh_old database';

    public function handle()
    {
        $this->info('Starting import process...');

        // Set temporary database connection for old DB
        config(['database.connections.old_archive' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => 'erp_bh_old',
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
        ]]);

        $oldDb = DB::connection('old_archive');

        // 1. Import Document Categories
        $this->info('Importing Document Categories...');
        $settings = $oldDb->table('settings')->where('key', 'doc_categories')->first();
        $categoryMapping = []; // map old id (e.g. 'legal') to new ID
        
        if ($settings && $settings->value) {
            $oldCategories = json_decode($settings->value, true);
            if (is_array($oldCategories)) {
                foreach ($oldCategories as $oldCat) {
                    $cat = ArchiveDocumentCategory::firstOrCreate(
                        ['name' => $oldCat['label']],
                        ['is_active' => true]
                    );
                    $categoryMapping[$oldCat['id']] = $cat->id;
                }
            }
        }

        // 2. Import File Types
        $this->info('Importing File Types...');
        $oldDepartments = $oldDb->table('departments')->get();
        $typeMapping = []; // map old slug to new ID
        
        foreach ($oldDepartments as $dept) {
            $type = ArchiveFileType::firstOrCreate(
                ['name' => $dept->department_name],
                ['is_active' => true]
            );
            $typeMapping[$dept->department_slug] = $type->id;
            
            // Generate some dynamic fields based on the old columns if needed
            // But we will skip creating fields for now, just storing data in dynamic_data json
        }
        
        // Fallback type
        $fallbackType = ArchiveFileType::firstOrCreate(['name' => 'عام'], ['is_active' => true]);

        // 3. Import Files
        $this->info('Importing Files...');
        $oldFilesCount = $oldDb->table('files')->count();
        $bar = $this->output->createProgressBar($oldFilesCount);
        $bar->start();

        $oldDb->table('files')->orderBy('id')->chunk(500, function ($files) use ($typeMapping, $fallbackType, $bar) {
            foreach ($files as $oldFile) {
                if (empty($oldFile->file_no)) {
                    $bar->advance();
                    continue;
                }

                $typeId = $typeMapping[$oldFile->department_id] ?? $fallbackType->id;

                $dynamicData = [];
                if (!empty($oldFile->file_mobile)) $dynamicData['mobile'] = $oldFile->file_mobile;
                if (!empty($oldFile->id_no)) $dynamicData['id_no'] = $oldFile->id_no;
                if (!empty($oldFile->acount_no)) $dynamicData['account_no'] = $oldFile->acount_no;
                if (!empty($oldFile->notes)) $dynamicData['notes'] = $oldFile->notes;
                if (!empty($oldFile->heraf_type)) $dynamicData['heraf_type'] = $oldFile->heraf_type;
                if (!empty($oldFile->address)) $dynamicData['address'] = $oldFile->address;
                if (!empty($oldFile->qetaa)) $dynamicData['qetaa'] = $oldFile->qetaa;
                if (!empty($oldFile->qasema)) $dynamicData['qasema'] = $oldFile->qasema;
                if (!empty($oldFile->file_date)) $dynamicData['file_date'] = $oldFile->file_date;
                if (!empty($oldFile->prev_owner)) $dynamicData['prev_owner'] = $oldFile->prev_owner;

                ArchiveFile::updateOrCreate(
                    ['file_no' => $oldFile->file_no],
                    [
                        'archive_file_type_id' => $typeId,
                        'file_name' => empty($oldFile->file_name) ? 'بدون اسم' : $oldFile->file_name,
                        'dynamic_data' => $dynamicData,
                        'created_at' => $oldFile->created_at ?? now(),
                        'updated_at' => $oldFile->updated_at ?? now(),
                    ]
                );
                
                $bar->advance();
            }
        });
        $bar->finish();
        $this->newLine();

        // 4. Import Documents
        $this->info('Importing Documents...');
        $oldDocsCount = $oldDb->table('documents')->count();
        $bar = $this->output->createProgressBar($oldDocsCount);
        $bar->start();

        $oldDb->table('documents')->orderBy('id')->chunk(500, function ($docs) use ($categoryMapping, $bar) {
            foreach ($docs as $oldDoc) {
                if (empty($oldDoc->file_no) || empty($oldDoc->document_image)) {
                    $bar->advance();
                    continue;
                }

                // Find the new file ID
                $newFile = ArchiveFile::where('file_no', $oldDoc->file_no)->first();
                if (!$newFile) {
                    $bar->advance();
                    continue; // Skip document if parent file doesn't exist
                }

                // Determine category
                $categoryId = null;
                if (!empty($oldDoc->document_type)) {
                    $cats = json_decode($oldDoc->document_type, true);
                    if (is_array($cats) && count($cats) > 0) {
                        $oldCatId = $cats[0]; // Take first category
                        $categoryId = $categoryMapping[$oldCatId] ?? null;
                    }
                }

                $extension = strtolower(pathinfo($oldDoc->document_image, PATHINFO_EXTENSION)) ?: 'jpg';
                $path = 'archive/documents/' . $oldDoc->document_image; // Assuming old attachments will be placed here

                ArchiveDocument::updateOrCreate(
                    [
                        'archive_file_id' => $newFile->id,
                        'document_path' => $path,
                    ],
                    [
                        'document_name' => empty($oldDoc->document_name) ? 'وثيقة بدون اسم' : $oldDoc->document_name,
                        'document_no' => $oldDoc->document_no,
                        'document_type' => $extension,
                        'document_size' => 0,
                        'document_category_id' => $categoryId,
                        'created_at' => $oldDoc->created_at ?? now(),
                        'updated_at' => $oldDoc->updated_at ?? now(),
                    ]
                );
                
                $bar->advance();
            }
        });
        $bar->finish();
        $this->newLine();

        $this->info('Import completed successfully!');
    }
}
