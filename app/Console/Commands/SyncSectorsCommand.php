<?php

namespace App\Console\Commands;

use App\Services\SectorsApiService;
use Illuminate\Console\Command;

class SyncSectorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sectors:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync official 11 IHSG sectors and financial ratio caches from Sectors REST API';

    /**
     * Execute the console command.
     */
    public function handle(SectorsApiService $sectorsApiService): int
    {
        $this->info('Memulai sinkronisasi data dari Sectors REST API...');

        $result = $sectorsApiService->syncAll();

        if ($result['success']) {
            $this->info("✓ {$result['message']}");

            return Command::SUCCESS;
        }

        $this->warn("! {$result['message']}");

        return Command::SUCCESS;
    }
}
