<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Damage\DamageBaselineService;

class InitializeDamageBaselineCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dams:init-baseline {--type=all : buildings, streets, economic, or all} {--limit= : Limit the number of records}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize baseline damage assessment records from existing municipal data (buildings, streets, crafts) marked as total destruction';

    /**
     * Execute the console command.
     */
    public function handle(DamageBaselineService $service)
    {
        $type = $this->option('type') ?: 'all';
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        $this->info("Starting Damage Baseline Initialization (Type: {$type}, Limit: " . ($limit ?: 'None') . ")...");

        if ($type === 'buildings' || $type === 'all') {
            $this->line("Processing Buildings...");
            $bCount = $service->initializeBuildings($limit);
            $this->info("✓ Imported {$bCount} buildings records.");
        }

        if ($type === 'streets' || $type === 'all') {
            $this->line("Processing Streets & Infrastructure...");
            $sCount = $service->initializeStreets($limit);
            $this->info("✓ Imported {$sCount} streets records.");
        }

        if ($type === 'economic' || $type === 'all') {
            $this->line("Processing Economic & Craft Facilities...");
            $eCount = $service->initializeEconomicFacilities($limit);
            $this->info("✓ Imported {$eCount} economic facility records.");
        }

        $this->info("Baseline initialization complete!");
        return Command::SUCCESS;
    }
}
