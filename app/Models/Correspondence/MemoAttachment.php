<?php

namespace App\Models\Correspondence;

use App\Models\ArchiveDocument;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemoAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'memo_attachments';

    protected $fillable = [
        'memo_id',
        'movement_id',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'uploaded_by',
        'archive_document_id',
    ];

    public function memo()
    {
        return $this->belongsTo(InternalMemo::class, 'memo_id');
    }

    public function movement()
    {
        return $this->belongsTo(MemoMovement::class, 'movement_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function archiveDocument()
    {
        return $this->belongsTo(ArchiveDocument::class, 'archive_document_id');
    }

    public function getFormattedSizeAttribute(): string
    {
        if (!$this->file_size) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = floor(log($this->file_size, 1024));
        return round($this->file_size / pow(1024, $i), 2) . ' ' . ($units[$i] ?? '');
    }
}
