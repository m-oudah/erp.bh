<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchiveFile extends Model
{
    protected $fillable = [
        'archive_file_type_id', 'file_no', 'file_name', 'department_id', 'dynamic_data'
    ];

    protected $casts = [
        'dynamic_data' => 'array',
    ];

    public function fileType()
    {
        return $this->belongsTo(ArchiveFileType::class, 'archive_file_type_id');
    }

    public function documents()
    {
        return $this->hasMany(ArchiveDocument::class);
    }
}
