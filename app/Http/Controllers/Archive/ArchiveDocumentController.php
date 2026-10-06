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

    public function index(Request $request)
    {
        $query = ArchiveDocument::with(['archiveFile', 'category'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $normalizedSearch = str_replace(['أ', 'إ', 'آ'], 'ا', $search);
            $query->whereRaw("REPLACE(REPLACE(REPLACE(document_name, 'أ', 'ا'), 'إ', 'ا'), 'آ', 'ا') LIKE ?", ["%{$normalizedSearch}%"]);
        }

        if ($request->filled('category_id')) {
            $query->where('document_category_id', $request->category_id);
        }

        if ($request->filled('archive_file_type_id')) {
            $typeId = $request->archive_file_type_id;
            $query->whereHas('archiveFile', function($q) use ($typeId) {
                $q->where('archive_file_type_id', $typeId);
            });
        }

        $documents = $query->paginate(20)->withQueryString();
        $categories = \App\Models\ArchiveDocumentCategory::where('is_active', true)->get();
        $fileTypes = \App\Models\ArchiveFileType::where('is_active', true)->get();

        return view('archive.documents.index', compact('documents', 'categories', 'fileTypes'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('archive.documents.create')) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'ليس لديك صلاحية إضافة وثائق.'], 403);
            }
            return back()->with('error', 'ليس لديك صلاحية إضافة وثائق.');
        }

        $request->validate([
            'archive_file_id' => 'required|exists:archive_files,id',
            'document_name' => 'required|string|max:255',
            'document_no' => 'nullable|string|max:255',
            'document_category_id' => 'nullable|exists:archive_document_categories,id',
            'document_file' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
        ]);

        try {
            $document = $this->archiveService->storeDocument(
                $request->archive_file_id,
                $request->file('document_file'),
                $request->document_name,
                $request->document_no,
                $request->document_category_id
            );

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم رفع الوثيقة بنجاح.',
                    'document' => $document
                ]);
            }

            return back()->with('success', 'تم رفع الوثيقة بنجاح.');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(ArchiveDocument $document)
    {
        abort_unless(auth()->user()->hasPermissionTo('archive.documents.delete'), 403, 'ليس لديك صلاحية حذف الوثائق.');

        Storage::disk('public')->delete($document->document_path);
        $document->delete();
        return back()->with('success', 'تم حذف الوثيقة بنجاح.');
    }

    public function bulkDestroy(Request $request)
    {
        abort_unless(auth()->user()->hasPermissionTo('archive.documents.delete'), 403, 'ليس لديك صلاحية حذف الوثائق.');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:archive_documents,id'
        ]);

        $documents = ArchiveDocument::whereIn('id', $request->ids)->get();

        foreach ($documents as $document) {
            Storage::disk('public')->delete($document->document_path);
            $document->delete();
        }

        return back()->with('success', 'تم حذف الوثائق المحددة بنجاح.');
    }
}
