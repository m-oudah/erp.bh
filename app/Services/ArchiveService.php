<?php

namespace App\Services;

use App\Models\ArchiveDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ArchiveService
{
    /**
     * Store a new document in the archive.
     * This can be used by the Archive module or any other module (e.g. HR, Engineering).
     */
    public function storeDocument(
        $archiveFileId,
        UploadedFile $file,
        $documentName,
        $documentNo = null,
        $documentCategoryId = null,
        $addedBy = null
    ) {
        // Validate extension if needed
        $allowedExtensions = ['pdf', 'doc', 'docx', 'png', 'jpg', 'jpeg'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $allowedExtensions)) {
            throw new \Exception("File type not allowed. Allowed types: " . implode(', ', $allowedExtensions));
        }

        // Store the file
        $path = $file->store('archive_documents', 'public');

        // Create the DB record
        $document = ArchiveDocument::create([
            'archive_file_id' => $archiveFileId,
            'document_no' => $documentNo,
            'document_name' => $documentName,
            'document_type' => $extension,
            'document_path' => $path,
            'document_category_id' => $documentCategoryId,
            'added_by' => $addedBy ?? auth()->id(),
        ]);

        return $document;
    }
}
