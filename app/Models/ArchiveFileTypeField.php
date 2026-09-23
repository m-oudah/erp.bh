<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ArchiveFileTypeField extends Model
{
    use SoftDeletes, LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
