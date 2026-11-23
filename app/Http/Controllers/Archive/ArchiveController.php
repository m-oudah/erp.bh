<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use App\Models\ArchiveFile;
use App\Models\ArchiveDocument;
use App\Models\ArchiveFileType;
use App\Models\ArchiveDocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchiveController extends Controller
{
    public function index()
    {
        $stats = [
            'total_files' => ArchiveFile::count(),
            'total_documents' => ArchiveDocument::count(),
            'files_today' => ArchiveFile::whereDate('created_at', today())->count(),
            'documents_today' => ArchiveDocument::whereDate('created_at', today())->count(),
            'total_types' => ArchiveFileType::where('is_active', true)->count(),
            'total_categories' => ArchiveDocumentCategory::count(),
            'linked_buildings' => DB::table('bhm_building_archive_files')->distinct('building_id')->count('building_id'),
            'linked_memos' => \App\Models\Correspondence\InternalMemo::whereNotNull('linked_archive_file_id')->orWhereNotNull('auto_archive_file_id')->count(),
        ];

        $latestFiles = ArchiveFile::with(['fileType', 'department'])
            ->withCount('documents')
            ->latest()
            ->take(6)
            ->get();

        $latestDocuments = ArchiveDocument::with(['archiveFile.fileType', 'addedBy', 'category'])
            ->latest()
            ->take(6)
            ->get();

        $archiveTypes = ArchiveFileType::where('is_active', true)
            ->withCount('files')
            ->orderByDesc('files_count')
            ->take(8)
            ->get();

        // Keep legacy variables for backwards compatibility
        $filesCount = $stats['total_files'];
        $documentsCount = $stats['total_documents'];
        $recentFiles = $latestFiles;

        return view('archive.index', compact(
            'stats',
            'latestFiles',
            'latestDocuments',
            'archiveTypes',
            'filesCount',
            'documentsCount',
            'recentFiles'
        ));
    }
}
