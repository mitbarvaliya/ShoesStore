<?php

namespace App\Services;

use App\Models\Market;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Exception;

class MarketService
{
    private string $alphaVantageApiKey;
    private string $metalpriceApiKey;
    private const CACHE_TTL = 60;

    public function __construct()
    {
        $this->alphaVantageApiKey = config('services.alpha_vantage.key');
        $this->metalpriceApiKey = config('services.metalprice.key');
    }

    public function fetchAndUpdateStocks(): array
    {
        $stocks = ['AAPL', 'TSLA', 'RELIANCE'];
        $results = [];

        foreach ($stocks as $symbol) {
            try {
                $result = $this->fetchStockPrice($symbol);
                $results[$symbol] = $result;
            } catch (Exception $e) {
                Log::error("Failed to fetch stock: {$symbol}", [
                    'symbol' => $symbol,
                    'error' => $e->getMessage(),
                ]);
                $results[$symbol] = null;
            }
        }

        return $results;
    }

    public function fetchStockPrice(string $symbol): ?array
    {
        try {
            $cacheKey = "stock_{$symbol}";

            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            if (empty($this->alphaVantageApiKey) || $this->alphaVantageApiKey === 'your_alpha_vantage_api_key_here') {
                Log::warning("Alpha Vantage API key not configured");
                return $this->getFallbackStockPrice($symbol);
            }

            $response = Http::timeout(10)
                ->retry(3, 1000)
                ->get('https://www.alphavantage.co/query', [
                    'function' => 'GLOBAL_QUOTE',
                    'symbol' => $symbol,
                    'apikey' => $this->alphaVantageApiKey,
                ]);

            if ($response->failed()) {
                Log::error("API request failed for stock: {$symbol}", [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();

            if (isset($data['Note'])) {
                Log::warning("API rate limit reached for {$symbol}");
                return null;
            }

            if (isset($data['Global Quote']) && !empty($data['Global Quote'])) {
                $quote = $data['Global Quote'];
                $currentPrice = (float) $quote['05. price'];

                $this->updateMarketData($symbol, $this->getStockName($symbol), 'stock', $currentPrice);

                $result = [
                    'symbol' => $symbol,
                    'price' => $currentPrice,
                    'change' => $quote['09. change'] ?? 0,
                    'change_percent' => $quote['10. change percent'] ?? '0%',
                ];

                Cache::put($cacheKey, $result, self::CACHE_TTL);
                return $result;
            }

            Log::warning("No data returned for stock: {$symbol}");
            return null;

        } catch (Exception $e) {
            Log::error("Exception fetching stock: {$symbol}", [
                'symbol' => $symbol,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    private function getFallbackStockPrice(string $symbol): ?array
    {
        $fallbackPrices = [
            'AAPL' => 178.50,
            'TSLA' => 245.00,
            'RELIANCE' => 2850.00,
        ];

        $price = $fallbackPrices[$symbol] ?? null;

        if ($price) {
            $existing = Market::where('symbol', $symbol)->first();
            $previousPrice = $existing?->price ?? $price;

            $this->updateMarketData($symbol, $this->getStockName($symbol), 'stock', $price);

            return [
                'symbol' => $symbol,
                'price' => $price,
                'change' => $price - $previousPrice,
                'change_percent' => $previousPrice > 0 ? round((($price - $previousPrice) / $previousPrice) * 100, 2) . '%' : '0%',
            ];
        }

        return null;
    }

    public function fetchAndUpdateMetals(): array
    {
        $results = [
            'gold' => $this->fetchMetalPrice('gold'),
            'silver' => $this->fetchMetalPrice('silver'),
            'copper' => $this->fetchMetalPrice('copper'),
        ];

        return $results;
    }

    public function fetchMetalPrice(string $metal): ?array
    {
        try {
            $cacheKey = "metal_{$metal}";

            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $metalSymbol = $this->getMetalSymbol($metal);
            $price = null;

            if ($this->metalpriceApiKey && $this->metalpriceApiKey !== 'your_metalprice_api_key_here') {
                $price = $this->fetchFromMetalPriceAPI($metalSymbol);
            }

            if (!$price) {
                $price = $this->fetchFromFreeMetalAPI($metalSymbol);
            }

            if ($price) {
                $symbol = strtoupper($metalSymbol);
                $this->updateMarketData($symbol, ucfirst($metal), $metal, $price);

                $result = [
                    'metal' => $metal,
                    'price' => $price,
                ];

                Cache::put($cacheKey, $result, self::CACHE_TTL);
                return $result;
            }

            return null;

        } catch (Exception $e) {
            Log::error("Exception fetching metal: {$metal}", [
                'metal' => $metal,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function updateMarketData(string $symbol, string $name, string $type, float $newPrice): void
    {
        try {
            DB::transaction(function () use ($symbol, $name, $type, $newPrice) {
                $existing = Market::where('symbol', $symbol)->first();
                $previousPrice = $existing?->price;

                Market::updateOrCreate(
                    ['symbol' => $symbol],
                    [
                        'name' => $name,
                        'type' => $type,
                        'price' => $newPrice,
                        'previous_price' => $previousPrice,
                    ]
                );
            });
        } catch (Exception $e) {
            Log::error("Failed to update market data: {$symbol}", [
                'symbol' => $symbol,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function updateAllPrices(array $prices): void
    {
        try {
            DB::transaction(function () use ($prices) {
                foreach ($prices as $data) {
                    Market::updateOrCreate(
                        ['symbol' => $data['symbol']],
                        [
                            'name' => $data['name'],
                            'type' => $data['type'],
                            'price' => $data['price'],
                            'previous_price' => $data['previous_price'] ?? null,
                        ]
                    );
                }
            });
        } catch (Exception $e) {
            Log::error("Failed to update all prices", [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function fetchFromMetalPriceAPI(string $metal): ?float
    {
        try {
            $response = Http::timeout(10)
                ->retry(3, 1000)
                ->withHeaders([
                    'X-API-KEY' => $this->metalpriceApiKey,
                ])
                ->get('https://api.metalpriceapi.com/v1/latest', [
                    'base' => 'USD',
                    'currencies' => strtoupper($metal),
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $metalLower = strtolower($metal);
                if (isset($data['rates'][$metalLower])) {
                    return round($data['rates'][$metalLower], 2);
                }
            }

            return null;

        } catch (Exception $e) {
            Log::error("MetalPriceAPI fetch failed for: {$metal}", [
                'metal' => $metal,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function fetchFromFreeMetalAPI(string $metal): ?float
    {
        $metalData = [
            'xau' => ['name' => 'Gold', 'price' => 2300.00],
            'xag' => ['name' => 'Silver', 'price' => 27.50],
            'xcu' => ['name' => 'Copper', 'price' => 4.50],
        ];

        $metalLower = strtolower($metal);
        return $metalData[$metalLower]['price'] ?? null;
    }

    public function fetchAllPrices(): array
    {
        $results = [
            'stocks' => [],
            'metals' => [],
            'errors' => [],
        ];

        try {
            $results['stocks'] = $this->fetchAndUpdateStocks();
        } catch (Exception $e) {
            Log::error("Failed to fetch all stocks", ['error' => $e->getMessage()]);
            $results['errors'][] = 'Failed to fetch stocks';
        }

        try {
            $results['metals'] = $this->fetchAndUpdateMetals();
        } catch (Exception $e) {
            Log::error("Failed to fetch all metals", ['error' => $e->getMessage()]);
            $results['errors'][] = 'Failed to fetch metals';
        }

        return $results;
    }

    public function getMarketData(string $type = null): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('market_data_' . ($type ?? 'all'), 30, function () use ($type) {
            if ($type) {
                return Market::where('type', $type)->get();
            }
            return Market::all();
        });
    }

    public function clearCache(): void
    {
        Cache::forget('market_live_data');
        Cache::forget('market_stats');
        Cache::forget('market_data_all');
        Cache::forget('market_data_stock');
        Cache::forget('market_data_metals');

        foreach (['AAPL', 'TSLA', 'RELIANCE'] as $symbol) {
            Cache::forget("stock_{$symbol}");
        }

        foreach (['gold', 'silver', 'copper'] as $metal) {
            Cache::forget("metal_{$metal}");
        }
    }

    private function getStockName(string $symbol): string
    {
        $stockNames = [
            'AAPL' => 'Apple Inc.',
            'TSLA' => 'Tesla Inc.',
            'RELIANCE' => 'Reliance Industries',
        ];

        return $stockNames[$symbol] ?? $symbol;
    }

    private function getMetalSymbol(string $metal): string
    {
        $symbols = [
            'gold' => 'xau',
            'silver' => 'xag',
            'copper' => 'xcu',
        ];

        return $symbols[strtolower($metal)] ?? $metal;
    }
}
