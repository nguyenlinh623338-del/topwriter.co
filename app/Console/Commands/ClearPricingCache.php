<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PricingService;

class ClearPricingCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pricing:clear-cache {--show : Display current pricing after clearing cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the subscription pricing cache that is parsed from welcome.blade.php';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pricingService = app(PricingService::class);

        $this->info('Clearing subscription pricing cache...');

        $cleared = $pricingService->clearCache();

        if ($cleared) {
            $this->info('✅ Subscription pricing cache cleared successfully!');
        } else {
            $this->warn('⚠️  Cache might not have existed or clearing failed.');
        }

        if ($this->option('show')) {
            $this->newLine();
            $this->info('Current pricing from welcome.blade.php:');

            $pricing = $pricingService->getFormattedPricing();

            $this->table(
                ['Plan', 'Price', 'Articles', 'Period'],
                [
                    ['Starter', $pricing['starter']['formatted'], $pricing['starter']['articles'], $pricing['starter']['period']],
                    ['Professional', $pricing['professional']['formatted'], $pricing['professional']['articles'], $pricing['professional']['period']],
                    ['Enterprise', $pricing['enterprise']['formatted'], $pricing['enterprise']['articles'], $pricing['enterprise']['period']],
                ]
            );
        }

        $this->newLine();
        $this->comment('💡 Tip: Run this command after updating prices in welcome.blade.php');
        $this->comment('💡 Use --show flag to display current pricing after clearing cache');
    }
}
