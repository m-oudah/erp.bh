<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class BhmPermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'bhm.dashboard.view',
            'bhm.buildings.view', 'bhm.buildings.create', 'bhm.buildings.edit', 'bhm.buildings.delete',
            'bhm.buildings.owners.manage',
            'bhm.buildings.floors.manage',
            'bhm.buildings.units.manage',
            'bhm.license-forms.view', 'bhm.license-forms.create', 'bhm.license-forms.edit', 'bhm.license-forms.approve',
            'bhm.regulatory-reports.view', 'bhm.regulatory-reports.create', 'bhm.regulatory-reports.edit',
            'bhm.economical.view', 'bhm.economical.create', 'bhm.economical.edit',
            'bhm.treatments.view', 'bhm.treatments.create', 'bhm.treatments.assign',
            'bhm.subscriptions.view', 'bhm.subscriptions.create', 'bhm.subscriptions.edit',
            'bhm.customer-pens.view', 'bhm.customer-pens.create', 'bhm.customer-pens.edit',
            'bhm.proof-of-cases.view', 'bhm.proof-of-cases.create', 'bhm.proof-of-cases.edit',
            'bhm.settings.manage'
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        $admin = User::first();
        if ($admin) {
            $admin->givePermissionTo($permissions);
        }
    }
}
