<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\ArchiveFileType;
use App\Models\ArchiveFileTypeField;
use Illuminate\Http\Request;

class ArchiveSettingsController extends Controller
{
    public function index()
    {
        $types = ArchiveFileType::withCount('fields')->get();
        return view('settings.archive_types.index', compact('types'));
    }

    public function create()
    {
        return view('settings.archive_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fields' => 'required|array|min:1',
            'fields.*.field_label' => 'required|string|max:255',
            'fields.*.field_type' => 'required|in:text,number,date,textarea',
            'fields.*.is_required' => 'nullable|boolean',
        ]);

        $type = ArchiveFileType::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true,
        ]);

        foreach ($request->fields as $index => $field) {
            $type->fields()->create([
                'field_name' => 'field_' . uniqid(),
                'field_label' => $field['field_label'],
                'field_type' => $field['field_type'],
                'is_required' => isset($field['is_required']) ? true : false,
                'order' => $index,
            ]);
        }

        return redirect()->route('settings.archive.types.index')->with('success', 'تم إضافة نوع الملف بنجاح.');
    }

    public function edit(ArchiveFileType $archiveType)
    {
        $archiveType->load('fields');
        return view('settings.archive_types.edit', compact('archiveType'));
    }

    public function update(Request $request, ArchiveFileType $archiveType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fields' => 'required|array|min:1',
            'fields.*.field_name' => 'nullable|string',
            'fields.*.field_label' => 'required|string|max:255',
            'fields.*.field_type' => 'required|in:text,number,date,textarea',
            'fields.*.is_required' => 'nullable|boolean',
        ]);

        $archiveType->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Delete existing fields and recreate them to handle order and removals
        $archiveType->fields()->delete();

        foreach ($request->fields as $index => $field) {
            $archiveType->fields()->create([
                'field_name' => !empty($field['field_name']) ? strtolower($field['field_name']) : 'field_' . uniqid(),
                'field_label' => $field['field_label'],
                'field_type' => $field['field_type'],
                'is_required' => isset($field['is_required']) && $field['is_required'] ? true : false,
                'order' => $index,
            ]);
        }

        return redirect()->route('settings.archive.types.index')->with('success', 'تم تحديث نوع الملف بنجاح.');
    }

    public function destroy(ArchiveFileType $archiveType)
    {
        // Could check if it has files first and block deletion
        if ($archiveType->files()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف نوع الملف لأن هناك ملفات مؤرشفة تابعة له.');
        }

        $archiveType->delete();
        return redirect()->route('settings.archive.types.index')->with('success', 'تم حذف النوع بنجاح.');
    }
}
