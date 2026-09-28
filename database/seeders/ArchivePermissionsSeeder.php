<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ArchivePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'archive.files.create',
            'archive.files.edit',
            'archive.files.delete',
            'archive.documents.create',
            'archive.documents.delete',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }
    }
}
