<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GeneralSettingsController extends Controller
{
    /**
     * General settings index (redirects to backup).
     */
    public function index()
    {
        return redirect()->route('settings.general.backup');
    }

    /**
     * Database backup management view.
     */
    public function backup()
    {
        $dbName = config('database.connections.mysql.database', 'bh_erp_db');
        
        $stats = DB::select("
            SELECT 
                COUNT(table_name) AS tables_count,
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
            FROM information_schema.tables 
            WHERE table_schema = ?
        ", [$dbName]);

        $tablesCount = $stats[0]->tables_count ?? 0;
        $sizeMb = $stats[0]->size_mb ?? 0;

        // Existing backups in storage/app/backups
        $backupDir = storage_path('app/backups');
        $backups = [];
        if (File::isDirectory($backupDir)) {
            $files = File::files($backupDir);
            foreach ($files as $file) {
                if (in_array(strtolower($file->getExtension()), ['sql', 'gz', 'zip'])) {
                    $backups[] = [
                        'filename' => $file->getFilename(),
                        'size' => round($file->getSize() / 1024 / 1024, 2), // MB
                        'created_at' => \Carbon\Carbon::createFromTimestamp($file->getMTime()),
                    ];
                }
            }
            // Sort by latest first
            usort($backups, fn($a, $b) => $b['created_at']->timestamp <=> $a['created_at']->timestamp);
        }

        return view('settings.general.backup', compact('dbName', 'tablesCount', 'sizeMb', 'backups'));
    }

    /**
     * Export database backup with DB name and export date.
     */
    public function exportBackup(Request $request)
    {
        $dbName = config('database.connections.mysql.database', 'bh_erp_db');
        $host = config('database.connections.mysql.host', '127.0.0.1');
        $port = config('database.connections.mysql.port', '3306');
        $user = config('database.connections.mysql.username', 'root');
        $pass = config('database.connections.mysql.password', '');

        $backupDir = storage_path('app/backups');
        if (!File::isDirectory($backupDir)) {
            File::makeDirectory($backupDir, 0755, true, true);
        }

        // Filename: DB name and date of the export day
        $dateStr = now()->format('Y-m-d_H-i-s');
        $filename = "{$dbName}_backup_{$dateStr}.sql";
        $filePath = "{$backupDir}/{$filename}";

        // Locate mysqldump binary
        $mysqldumpPath = 'mysqldump';
        $laragonDumps = glob('C:/laragon/bin/mysql/*/bin/mysqldump.exe');
        if (!empty($laragonDumps) && file_exists($laragonDumps[0])) {
            $mysqldumpPath = $laragonDumps[0];
        }

        $passArg = !empty($pass) ? "-p\"{$pass}\"" : '';
        $cmd = "\"{$mysqldumpPath}\" -h {$host} -P {$port} -u {$user} {$passArg} --default-character-set=utf8mb4 {$dbName} > \"{$filePath}\"";

        set_time_limit(300);
        exec($cmd, $output, $returnVar);

        // Fallback to PHP PDO dumper if mysqldump failed or empty
        if ($returnVar !== 0 || !file_exists($filePath) || filesize($filePath) === 0) {
            $handle = fopen($filePath, 'w');
            fwrite($handle, "-- Database Backup: {$dbName}\n-- Export Date: " . now()->toDateTimeString() . "\n\nSET FOREIGN_KEY_CHECKS=0;\n\n");
            $tables = DB::select('SHOW TABLES');
            foreach ($tables as $t) {
                $tblName = array_values((array)$t)[0];
                $create = DB::select("SHOW CREATE TABLE `{$tblName}`");
                if (!empty($create[0])) {
                    $createSql = $create[0]->{'Create Table'};
                    fwrite($handle, "DROP TABLE IF EXISTS `{$tblName}`;\n{$createSql};\n\n");

                    // Dump rows in batches
                    DB::table($tblName)->orderBy(DB::raw('1'))->chunk(200, function ($rows) use ($handle, $tblName) {
                        foreach ($rows as $row) {
                            $values = array_map(function ($val) {
                                if (is_null($val)) return 'NULL';
                                return DB::getPdo()->quote($val);
                            }, (array)$row);
                            fwrite($handle, "INSERT INTO `{$tblName}` VALUES (" . implode(', ', $values) . ");\n");
                        }
                    });
                    fwrite($handle, "\n");
                }
            }
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fclose($handle);
        }

        if ($request->input('action') === 'direct_download') {
            return response()->download($filePath, $filename);
        }

        return redirect()->route('settings.general.backup')
            ->with('success', "تم إنشاء وتصدير النسخة الاحتياطية بنجاح باسم: {$filename}")
            ->with('download_filename', $filename);
    }

    /**
     * Download a previously exported backup file.
     */
    public function downloadBackup($filename)
    {
        $filename = basename($filename);
        $filePath = storage_path("app/backups/{$filename}");

        if (!file_exists($filePath)) {
            return back()->with('error', 'ملف النسخة الاحتياطية غير موجود.');
        }

        return response()->download($filePath, $filename);
    }

    /**
     * Delete an existing backup file.
     */
    public function deleteBackup($filename)
    {
        $filename = basename($filename);
        $filePath = storage_path("app/backups/{$filename}");

        if (file_exists($filePath)) {
            unlink($filePath);
            return back()->with('success', "تم حذف ملف النسخة الاحتياطية ({$filename}) بنجاح.");
        }

        return back()->with('error', 'ملف النسخة الاحتياطية غير موجود.');
    }
}

