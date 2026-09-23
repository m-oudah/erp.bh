<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ArchiveDocumentCategory;

class ArchiveDocumentCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ArchiveDocumentCategory::latest()->paginate(10);
        return view('settings.document_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('settings.document_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:archive_document_categories,name',
            'is_active' => 'boolean'
        ]);

        ArchiveDocumentCategory::create([
            'name' => $validated['name'],
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('settings.archive.document-categories.index')
            ->with('success', 'تم إضافة تصنيف الوثيقة بنجاح');
    }

    public function edit(ArchiveDocumentCategory $documentCategory)
    {
        return view('settings.document_categories.edit', compact('documentCategory'));
    }

    public function update(Request $request, ArchiveDocumentCategory $documentCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:archive_document_categories,name,' . $documentCategory->id,
            'is_active' => 'boolean'
        ]);

        $documentCategory->update([
            'name' => $validated['name'],
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('settings.archive.document-categories.index')
            ->with('success', 'تم تعديل تصنيف الوثيقة بنجاح');
    }

    public function destroy(ArchiveDocumentCategory $documentCategory)
    {
        $documentCategory->delete();

        return redirect()->route('settings.archive.document-categories.index')
            ->with('success', 'تم حذف تصنيف الوثيقة بنجاح');
    }
}
