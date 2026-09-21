<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchiveFileTypeField extends Model
{
    protected $fillable = [
        'archive_file_type_id', 'field_name', 'field_label', 
        'field_type', 'is_required', 'order'
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function fileType()
    {
        return $this->belongsTo(ArchiveFileType::class, 'archive_file_type_id');
    }
