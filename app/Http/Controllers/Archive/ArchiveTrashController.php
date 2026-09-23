<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use App\Models\ArchiveFile;
use App\Models\ArchiveDocument;
use Illuminate\Http\Request;

class ArchiveTrashController extends Controller
{
    public function index()
    {
        $deletedFiles = ArchiveFile::onlyTrashed()->with('fileType')->latest('deleted_at')->get();
        $deletedDocuments = ArchiveDocument::onlyTrashed()->with(['archiveFile', 'category', 'addedBy'])->latest('deleted_at')->get();
        
        return view('archive.trash.index', compact('deletedFiles', 'deletedDocuments'));
    }

    public function restoreFile($id)
    {
        $file = ArchiveFile::onlyTrashed()->findOrFail($id);
        $file->restore();

        return back()->with('success', 'تم استعادة الملف بنجاح.');
    }

    public function forceDeleteFile($id)
    {
        $file = ArchiveFile::onlyTrashed()->findOrFail($id);
        $file->forceDelete();

        return back()->with('success', 'تم الحذف النهائي للملف بنجاح.');
    }

    public function restoreDocument($id)
    {
        $document = ArchiveDocument::onlyTrashed()->findOrFail($id);
        $document->restore();

        return back()->with('success', 'تم استعادة الوثيقة بنجاح.');
    }

    public function forceDeleteDocument($id)
    {
        $document = ArchiveDocument::onlyTrashed()->findOrFail($id);
        $document->forceDelete();

        return back()->with('success', 'تم الحذف النهائي للوثيقة بنجاح.');
    }
}
