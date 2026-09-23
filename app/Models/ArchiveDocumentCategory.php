<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ArchiveDocumentCategory extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    
    protected $fillable = ['name', 'is_active'];

    public function documents()
    {
        return $this->hasMany(ArchiveDocument::class, 'document_category_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
