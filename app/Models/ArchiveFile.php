<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ArchiveFile extends Model
{
    use SoftDeletes, LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
