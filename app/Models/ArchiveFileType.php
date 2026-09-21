<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchiveFileType extends Model
{
    protected $fillable = ['name', 'description', 'is_active'];

    public function fields()
    {
        return $this->hasMany(ArchiveFileTypeField::class)->orderBy('order');
    }

    public function files()
    {
        return $this->hasMany(ArchiveFile::class);
    }
