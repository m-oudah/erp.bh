<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ArchiveDocument extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'archive_file_id', 'document_no', 'document_name', 'document_type',
        'document_path', 'document_category_id', 'added_by'
    ];

    public function category()
    {
        return $this->belongsTo(ArchiveDocumentCategory::class, 'document_category_id');
    }

    public function archiveFile()
    {
        return $this->belongsTo(ArchiveFile::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
