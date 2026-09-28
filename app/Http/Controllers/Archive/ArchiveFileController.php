<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use App\Models\ArchiveFile;
use App\Models\ArchiveFileType;
use Illuminate\Http\Request;

class ArchiveFileController extends Controller
{
    public function index(Request $request)
    {
        $query = ArchiveFile::with('fileType')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $normalizedSearch = str_replace(['أ', 'إ', 'آ'], 'ا', $search);

            $query->where(function($q) use ($normalizedSearch) {
                $q->whereRaw("REPLACE(REPLACE(REPLACE(file_no, 'أ', 'ا'), 'إ', 'ا'), 'آ', 'ا') LIKE ?", ["%{$normalizedSearch}%"])
                  ->orWhereRaw("REPLACE(REPLACE(REPLACE(file_name, 'أ', 'ا'), 'إ', 'ا'), 'آ', 'ا') LIKE ?", ["%{$normalizedSearch}%"]);
            });
        }

        if ($request->filled('type_id')) {
            $query->where('archive_file_type_id', $request->type_id);
        }

        $files = $query->paginate(20)->withQueryString();
        $types = ArchiveFileType::where('is_active', true)->get();
        
        return view('archive.files.index', compact('files', 'types'));
    }

    public function create()
    {
        abort_unless(auth()->user()->hasPermissionTo('archive.files.create'), 403, 'ليس لديك صلاحية إضافة ملفات.');

        $types = ArchiveFileType::with('fields')->where('is_active', true)->get();
        return view('archive.files.create', compact('types'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasPermissionTo('archive.files.create'), 403, 'ليس لديك صلاحية إضافة ملفات.');

        $request->validate([
            'archive_file_type_id' => 'required|exists:archive_file_types,id',
            'file_no' => 'required|string|max:255|unique:archive_files,file_no',
            'file_name' => 'required|string|max:255',
        ]);

        $type = ArchiveFileType::with('fields')->findOrFail($request->archive_file_type_id);
        
        // Build dynamic validation rules
        $dynamicRules = [];
        $dynamicAttributes = [];
        foreach ($type->fields as $field) {
            $rule = $field->is_required ? 'required' : 'nullable';
            if ($field->field_type == 'number') {
                $rule .= '|numeric';
            } elseif ($field->field_type == 'date') {
                $rule .= '|date';
            } else {
                $rule .= '|string';
            }
            $dynamicRules['dynamic_data.' . $field->field_name] = $rule;
            $dynamicAttributes['dynamic_data.' . $field->field_name] = $field->field_label;
        }
        
        $request->validate($dynamicRules, [], $dynamicAttributes);

        ArchiveFile::create([
            'archive_file_type_id' => $request->archive_file_type_id,
            'file_no' => $request->file_no,
            'file_name' => $request->file_name,
            'dynamic_data' => $request->dynamic_data ?? [],
            'department_id' => auth()->id(),
        ]);

        return redirect()->route('archive.files.index')->with('success', 'تم إنشاء الملف بنجاح.');
    }

    public function show(ArchiveFile $file)
    {
        $file->load(['documents', 'fileType.fields']);
        $categories = \App\Models\ArchiveDocumentCategory::where('is_active', true)->get();
        return view('archive.files.show', compact('file', 'categories'));
    }

    public function edit(ArchiveFile $file)
    {
        abort_unless(auth()->user()->hasPermissionTo('archive.files.edit'), 403, 'ليس لديك صلاحية تعديل الملفات.');

        $types = ArchiveFileType::with('fields')->where('is_active', true)->get();
        return view('archive.files.edit', compact('file', 'types'));
    }

    public function update(Request $request, ArchiveFile $file)
    {
        abort_unless(auth()->user()->hasPermissionTo('archive.files.edit'), 403, 'ليس لديك صلاحية تعديل الملفات.');

        $request->validate([
            'archive_file_type_id' => 'required|exists:archive_file_types,id',
            'file_no' => 'required|string|max:255|unique:archive_files,file_no,' . $file->id,
            'file_name' => 'required|string|max:255',
        ]);

        $type = ArchiveFileType::with('fields')->findOrFail($request->archive_file_type_id);
        
        // Build dynamic validation rules
        $dynamicRules = [];
        $dynamicAttributes = [];
        foreach ($type->fields as $field) {
            $rule = $field->is_required ? 'required' : 'nullable';
            if ($field->field_type == 'number') {
                $rule .= '|numeric';
            } elseif ($field->field_type == 'date') {
                $rule .= '|date';
            } else {
                $rule .= '|string';
            }
            $dynamicRules['dynamic_data.' . $field->field_name] = $rule;
            $dynamicAttributes['dynamic_data.' . $field->field_name] = $field->field_label;
        }
        
        $request->validate($dynamicRules, [], $dynamicAttributes);

        $file->update([
            'archive_file_type_id' => $request->archive_file_type_id,
            'file_no' => $request->file_no,
            'file_name' => $request->file_name,
            'dynamic_data' => $request->dynamic_data ?? [],
        ]);

        return redirect()->route('archive.files.show', $file->id)->with('success', 'تم تحديث الملف بنجاح.');
    }

    public function destroy(ArchiveFile $file)
    {
        abort_unless(auth()->user()->hasPermissionTo('archive.files.delete'), 403, 'ليس لديك صلاحية حذف الملفات.');

        $file->delete();
        return redirect()->route('archive.files.index')->with('success', 'تم حذف الملف بنجاح.');
    }
}
