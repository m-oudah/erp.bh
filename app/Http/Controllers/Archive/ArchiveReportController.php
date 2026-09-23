<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use App\Models\ArchiveFile;
use App\Models\ArchiveDocument;
use App\Models\ArchiveFileType;
use App\Models\ArchiveDocumentCategory;
use Illuminate\Http\Request;

class ArchiveReportController extends Controller
{
    public function index()
    {
        $totalFiles = ArchiveFile::count();
        $totalDocuments = ArchiveDocument::count();
        
        $filesByType = ArchiveFileType::withCount('files')->get();
        $documentsByCategory = ArchiveDocumentCategory::withCount('documents')->get();
        $recentDocuments = ArchiveDocument::with(['archiveFile', 'category', 'addedBy'])->latest()->take(10)->get();

        return view('archive.reports.index', compact(
            'totalFiles', 
            'totalDocuments', 
            'filesByType', 
            'documentsByCategory',
            'recentDocuments'
        ));
    }

    public function export(Request $request)
    {
        $fileName = 'archive_report_' . date('Y_m_d_H_i_s') . '.csv';
        $documents = ArchiveDocument::with(['archiveFile', 'category', 'addedBy'])->get();

        $headers = array(
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['رقم الوثيقة', 'اسم الوثيقة', 'الملف التابع', 'التصنيف', 'النوع', 'تاريخ الإضافة', 'بواسطة'];

        $callback = function() use($documents, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 Excel support
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, $columns);

            foreach ($documents as $doc) {
                $row = [
                    $doc->document_no,
                    $doc->document_name,
                    $doc->archiveFile ? $doc->archiveFile->file_name : 'غير محدد',
                    $doc->category ? $doc->category->name : 'غير مصنف',
                    strtoupper($doc->document_type),
                    $doc->created_at->format('Y-m-d'),
                    $doc->addedBy ? $doc->addedBy->name : 'غير محدد'
                ];
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
