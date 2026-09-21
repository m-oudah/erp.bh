<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use App\Models\ArchiveDocument;
use App\Services\ArchiveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchiveDocumentController extends Controller
{
    protected $archiveService;

    public function __construct(ArchiveService $archiveService)
    {
        $this->archiveService = $archiveService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'archive_file_id' => 'required|exists:archive_files,id',
            'document_name' => 'required|string|max:255',
            'document_no' => 'nullable|string|max:255',
            'document_file' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240', // 10MB max
        ]);

        try {
            $this->archiveService->storeDocument(
                $request->archive_file_id,
                $request->file('document_file'),
                $request->document_name,
                $request->document_no
            );

            return back()->with('success', 'تم رفع الوثيقة بنجاح.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(ArchiveDocument $document)
    {
        // Delete file from storage
        Storage::disk('public')->delete($document->document_path);
        
        $document->delete();
        return back()->with('success', 'تم حذف الوثيقة بنجاح.');
    }
}
