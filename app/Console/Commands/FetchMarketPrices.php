<?php

namespace App\Console\Commands;

use App\Services\MarketService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class FetchMarketPrices extends Command
{
    protected $signature = 'market:fetch 
                            {--type= : Filter by type (stocks, metals, all)}
                            {--symbol= : Fetch specific stock symbol}
                            {--metal= : Fetch specific metal (gold, silver, copper)}
                            {--list : List all tracked markets}
                            {--fresh : Clear cache before fetching}';

    protected $description = 'Fetch latest market prices from APIs and update database';

    private array $availableTypes = ['stocks', 'metals', 'all'];
    private array $availableMetals = ['gold', 'silver', 'copper'];

    public function __construct(private MarketService $marketService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $type = $this->option('type') ?? 'all';
        $symbol = $this->option('symbol');
        $metal = $this->option('metal');

        if ($this->option('list')) {
            return $this->listMarkets();
        }

        if ($this->option('fresh')) {
            $this->clearCache();
        }

        $this->info('═══════════════════════════════════════════════');
        $this->info('         MARKET DATA FETCHER');
        $this->info('═══════════════════════════════════════════════');

        if ($symbol) {
            return $this->fetchSingleStock($symbol);
        }

        if ($metal) {
            return $this->fetchSingleMetal($metal);
        }

        return $this->fetchByType($type);
    }

    private function fetchByType(string $type): int
    {
        $type = strtolower($type);

        if (!in_array($type, $this->availableTypes)) {
            $this->error("Invalid type. Available: " . implode(', ', $this->availableTypes));
            return Command::FAILURE;
        }

        $success = true;

        if ($type === 'stocks' || $type === 'all') {
            $success = $this->fetchStocks() && $success;
        }

        if ($type === 'metals' || $type === 'all') {
            $success = $this->fetchMetals() && $success;
        }

        if ($success) {
            $this->newLine();
            $this->info('✓ All market prices updated successfully!');
            return Command::SUCCESS;
        }

        $this->newLine();
        $this->warn('⚠ Some market prices failed to update.');
        return Command::FAILURE;
    }

    private function fetchStocks(): bool
    {
        $this->newLine();
        $this->info('📈 Fetching Stocks...');
        $this->line('─' . str_repeat('─', 50));

        $stocks = $this->marketService->fetchAndUpdateStocks();
        $allSuccess = true;

        foreach ($stocks as $symbol => $data) {
            if ($data) {
                $this->line(sprintf(
                    '  %-10s $%10.2f  (Change: %s)',
                    $symbol,
                    $data['price'],
                    $data['change_percent']
                ));
            } else {
                $this->error("  ✗ {$symbol}: Failed to fetch");
                $allSuccess = false;
            }
        }

        return $allSuccess;
    }

    private function fetchMetals(): bool
    {
        $this->newLine();
        $this->info('🥇 Fetching Metals...');
        $this->line('─' . str_repeat('─', 50));

        $metals = $this->marketService->fetchAndUpdateMetals();
        $allSuccess = true;

        foreach ($metals as $metalType => $data) {
            if ($data) {
                $this->line(sprintf(
                    '  %-10s $%10.2f',
                    ucfirst($metalType),
                    $data['price']
                ));
            } else {
                $this->error("  ✗ " . ucfirst($metalType) . ": Failed to fetch");
                $allSuccess = false;
            }
        }

        return $allSuccess;
    }

    private function fetchSingleStock(string $symbol): int
    {
        $this->info("Fetching stock: {$symbol}...");

        $data = $this->marketService->fetchStockPrice($symbol);

        if ($data) {
            $this->info("✓ {$symbol}: $" . number_format($data['price'], 2));
            return Command::SUCCESS;
        }

        $this->error("✗ Failed to fetch {$symbol}");
        return Command::FAILURE;
    }

    private function fetchSingleMetal(string $metal): int
    {
        $metal = strtolower($metal);

        if (!in_array($metal, $this->availableMetals)) {
            $this->error("Invalid metal. Available: " . implode(', ', $this->availableMetals));
            return Command::FAILURE;
        }

        $this->info("Fetching metal: " . ucfirst($metal) . "...");

        $data = $this->marketService->fetchMetalPrice($metal);

        if ($data) {
            $this->info("✓ " . ucfirst($metal) . ": $" . number_format($data['price'], 2));
            return Command::SUCCESS;
        }

        $this->error("✗ Failed to fetch " . ucfirst($metal));
        return Command::FAILURE;
    }

    private function listMarkets(): int
    {
        $this->newLine();
        $this->info('═══════════════════════════════════════════════');
        $this->info('         TRACKED MARKETS');
        $this->info('═══════════════════════════════════════════════');

        $markets = $this->marketService->getMarketData();

        if ($markets->isEmpty()) {
            $this->warn('No markets found. Run `php artisan market:fetch` first.');
            return Command::SUCCESS;
        }

        $this->table(
            ['Symbol', 'Name', 'Type', 'Price', 'Previous', 'Status'],
            $markets->map(function ($market) {
                return [
                    $market->symbol,
                    $market->name,
                    ucfirst($market->type),
                    '$' . number_format($market->price, 2),
                    $market->previous_price ? '$' . number_format($market->previous_price, 2) : 'N/A',
                    $this->getStatusIcon($market->getPriceChangeStatus()) . ' ' . $market->getPriceChangeStatus(),
                ];
            })
        );

        return Command::SUCCESS;
    }

    private function getStatusIcon(string $status): string
    {
        return match ($status) {
            'up' => '▲',
            'down' => '▼',
            default => '─',
        };
    }

    private function clearCache(): void
    {
        $this->info('Clearing cache...');
        Artisan::call('cache:clear');
        $this->info('Cache cleared.');
    }
}
