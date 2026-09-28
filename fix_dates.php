<?php

use Illuminate\Support\Facades\DB;

$dbName = env('DB_DATABASE');

// Update files dates
DB::statement("UPDATE {$dbName}.archive_files n JOIN erp_bh_old.files o ON n.file_no = o.file_no SET n.created_at = IFNULL(o.created_at, o.updated_at), n.updated_at = IFNULL(o.updated_at, o.created_at)");

// Update documents dates
DB::statement("UPDATE {$dbName}.archive_documents nd JOIN {$dbName}.archive_files nf ON nd.archive_file_id = nf.id JOIN erp_bh_old.documents od ON od.file_no = nf.file_no AND nd.document_path = CONCAT('archive/documents/', IFNULL(od.document_image, '')) SET nd.created_at = IFNULL(od.created_at, od.updated_at), nd.updated_at = IFNULL(od.updated_at, od.created_at)");

echo "Dates updated successfully.\n";
