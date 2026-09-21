<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchiveDocument extends Model
{
    protected $fillable = [
        'archive_file_id', 'document_no', 'document_name', 'document_type',
        'document_path', 'added_by'
    ];

    public function archiveFile()
    {
        return $this->belongsTo(ArchiveFile::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
