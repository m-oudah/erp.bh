<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use App\Models\ArchiveFile;
use App\Models\ArchiveDocument;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index()
    {
        $filesCount = ArchiveFile::count();
        $documentsCount = ArchiveDocument::count();
        $recentFiles = ArchiveFile::latest()->take(5)->get();

        return view('archive.index', compact('filesCount', 'documentsCount', 'recentFiles'));
    }
}
