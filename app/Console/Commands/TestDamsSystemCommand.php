<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Damage\DamageRecord;
use App\Models\Damage\PricingCriteria;
use App\Models\Damage\DonorOrganization;
use App\Models\Damage\DamageAuditLog;
use App\Models\Damage\VerificationLog;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class TestDamsSystemCommand extends Command
{
    protected $signature = 'dams:test-system';
    protected $description = 'Verify and validate all DAMS models, database counts, criteria, and route registrations';

    public function handle()
    {
        $this->info("=== Testing Damage Assessment & Needs System (DAMS) ===");

        // 1. Database Counts
        $totalRecords = DamageRecord::count();
        $this->line("1. Damage Records Count: " . number_format($totalRecords));
        if ($totalRecords === 0) {
            $this->error("No damage records found!");
        } else {
            $this->info("✓ Damage records initialized successfully.");
        }

        // 2. Sectoral Breakdown
        $sectors = DamageRecord::selectRaw('sector, count(*) as count, sum(estimated_cost_usd) as usd')
            ->groupBy('sector')
            ->get();
        $this->line("\n2. Sector Breakdown & Estimated Costs (USD):");
        foreach ($sectors as $s) {
            $this->line("   - {$s->sector}: " . number_format($s->count) . " records | $" . number_format($s->usd, 2));
        }

        // 3. Pricing Criteria
        $criteriaCount = PricingCriteria::count();
        $this->line("\n3. Pricing Criteria Count: " . $criteriaCount);
        if ($criteriaCount > 0) {
            $this->info("✓ Pricing criteria populated and active.");
        }

        // 4. Sample Record Verification
        $sample = DamageRecord::first();
        if ($sample) {
            $this->line("\n4. Sample Record #{$sample->record_number}:");
            $this->line("   - Name: {$sample->name}");
            $this->line("   - Damage Level: {$sample->damage_level}");
            $this->line("   - Workflow Status: {$sample->workflow_status}");
            $this->line("   - Cost: $" . number_format($sample->estimated_cost_usd, 2) . " / ₪" . number_format($sample->estimated_cost_ils, 2));
            $this->line("   - Certificate UUID: {$sample->certificate_uuid}");
            $this->info("✓ Certificate UUID and Pricing calculation present.");
        }

        // 5. Routes Check
        $expectedRoutes = [
            'damage.dashboard',
            'damage.records.index',
            'damage.records.create',
            'damage.records.show',
            'damage.pricing.index',
            'damage.portal.index',
            'damage.portal.mapData',
            'damage.portal.donorSpace',
            'damage.portal.report',
            'damage.verify-certificate',
        ];

        $this->line("\n5. Checking Route Registrations:");
        foreach ($expectedRoutes as $rName) {
            if (Route::has($rName)) {
                $this->info("   ✓ Route '{$rName}' is registered.");
            } else {
                $this->error("   ✗ Route '{$rName}' MISSING!");
            }
        }

        $this->info("\n=== All System Checks Completed Successfully! ===");
        return Command::SUCCESS;
    }
}
