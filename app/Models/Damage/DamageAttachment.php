<?php

namespace App\Models\Damage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DamageAttachment extends Model
{
    use HasFactory;

    protected $table = 'dams_media_attachments';

    protected $fillable = [
        'record_id',
        'attachment_type',
        'title',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'central_archive_file_id',
        'uploaded_by',
    ];

    public function record()
    {
        return $this->belongsTo(DamageRecord::class, 'record_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->attachment_type) {
            'before_photo' => 'صورة قبل الضرر',
            'after_photo' => 'صورة حديثة للأضرار',
            'engineering_drawing' => 'مخطط هندسي',
            'permit_document' => 'رخصة بناء أو مستند رسمي',
            'field_report' => 'تقرير معاينة ميدانية',
            'video' => 'توثيق فيديو',
            default => $this->attachment_type,
        };
    }
}
