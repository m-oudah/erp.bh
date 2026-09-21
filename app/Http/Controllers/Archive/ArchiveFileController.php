<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use App\Models\ArchiveFile;
use Illuminate\Http\Request;

class ArchiveFileController extends Controller
{
    public function index(Request $request)
    {
        $query = ArchiveFile::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('file_name', 'like', "%{$search}%")
                  ->orWhere('file_no', 'like', "%{$search}%")
                  ->orWhere('id_no', 'like', "%{$search}%");
        }

        $files = $query->latest()->paginate(15);
        
        return view('archive.files.index', compact('files'));
    }

    public function create()
    {
        $types = \App\Models\ArchiveFileType::with('fields')->where('is_active', true)->get();
        return view('archive.files.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file_no' => 'required|string|unique:archive_files,file_no',
            'file_name' => 'required|string|max:255',
            'id_no' => 'nullable|string|max:50',
            'file_mobile' => 'nullable|string|max:50',
            'qetaa' => 'nullable|string|max:50',
            'qasema' => 'nullable|string|max:50',
            'file_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $file = ArchiveFile::create($validated);

        return redirect()->route('archive.files.show', $file->id)->with('success', 'تم إنشاء الملف المادي بنجاح.');
    }

    public function show(ArchiveFile $file)
    {
        $file->load('documents.addedBy');
        return view('archive.files.show', compact('file'));
    }

    public function edit(ArchiveFile $file)
    {
        return view('archive.files.edit', compact('file'));
    }

    public function update(Request $request, ArchiveFile $file)
    {
        $validated = $request->validate([
            'file_no' => 'required|string|unique:archive_files,file_no,' . $file->id,
            'file_name' => 'required|string|max:255',
            'id_no' => 'nullable|string|max:50',
            'file_mobile' => 'nullable|string|max:50',
            'qetaa' => 'nullable|string|max:50',
            'qasema' => 'nullable|string|max:50',
            'file_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $file->update($validated);

        return redirect()->route('archive.files.show', $file->id)->with('success', 'تم تحديث بيانات الملف.');
    }

    public function destroy(ArchiveFile $file)
    {
        $file->delete();
        return redirect()->route('archive.files.index')->with('success', 'تم الحذف بنجاح.');
    }
}
