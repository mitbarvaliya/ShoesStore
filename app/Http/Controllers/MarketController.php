<?php

namespace App\Http\Controllers;

use App\Models\Market;
use App\Services\MarketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MarketController extends Controller
{
    public function __construct(private MarketService $marketService)
    {
    }

    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10);

        $query = Market::query();

        if ($type !== 'all') {
            if ($type === 'stock') {
                $query->where('type', 'stock');
            } elseif (in_array($type, ['gold', 'silver', 'copper'])) {
                $query->where('type', $type);
            } elseif ($type === 'metals') {
                $query->whereIn('type', ['gold', 'silver', 'copper']);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('symbol', 'like', "%{$search}%");
            });
        }

        $markets = $query->orderBy('type')->orderBy('name')->paginate($perPage);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $markets->items(),
                'meta' => [
                    'current_page' => $markets->currentPage(),
                    'last_page' => $markets->lastPage(),
                    'per_page' => $markets->perPage(),
                    'total' => $markets->total(),
                ],
            ]);
        }

        $stocks = Market::where('type', 'stock')->orderBy('name')->get();
        $metals = Market::whereIn('type', ['gold', 'silver', 'copper'])->orderBy('name')->get();

        return view('markets.index', compact('stocks', 'metals', 'markets', 'type', 'search'));
    }

    public function fetch(Request $request)
    {
        try {
            $type = $request->get('type');

            if ($type === 'stocks') {
                $results = $this->marketService->fetchAndUpdateStocks();
                return response()->json([
                    'success' => true,
                    'type' => 'stocks',
                    'data' => $results,
                    'message' => 'Stocks fetched successfully',
                ]);
            }

            if ($type === 'metals') {
                $results = $this->marketService->fetchAndUpdateMetals();
                return response()->json([
                    'success' => true,
                    'type' => 'metals',
                    'data' => $results,
                    'message' => 'Metals fetched successfully',
                ]);
            }

            $results = $this->marketService->fetchAllPrices();
            return response()->json([
                'success' => true,
                'data' => $results,
                'message' => 'All market data fetched successfully',
            ]);

        } catch (\Exception $e) {
            Log::error('Market fetch error: ' . $e->getMessage(), [
                'exception' => $e,
                'type' => $request->get('type'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch market data: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
            ], 500);
        }
    }

    public function liveData(Request $request)
    {
        try {
            $cacheKey = 'market_live_data';
            
            $data = Cache::remember($cacheKey, 10, function () {
                $markets = Market::all()->map(function ($market) {
                    return [
                        'id' => $market->id,
                        'name' => $market->name,
                        'symbol' => $market->symbol,
                        'type' => $market->type,
                        'price' => (float) $market->price,
                        'previous_price' => (float) ($market->previous_price ?? 0),
                        'status' => $market->getPriceChangeStatus(),
                        'difference' => $market->getPriceDifference(),
                        'percentage' => $market->getPriceChangePercentage(),
                        'updated_at' => $market->updated_at->toIso8601String(),
                    ];
                });

                $stats = Cache::remember('market_stats', 10, function () {
                    return [
                        'total_markets' => Market::count(),
                        'stocks_up' => Market::where('type', 'stock')
                            ->whereNotNull('previous_price')
                            ->whereColumn('price', '>', 'previous_price')
                            ->count(),
                        'stocks_down' => Market::where('type', 'stock')
                            ->whereNotNull('previous_price')
                            ->whereColumn('price', '<', 'previous_price')
                            ->count(),
                        'metals_up' => Market::whereIn('type', ['gold', 'silver', 'copper'])
                            ->whereNotNull('previous_price')
                            ->whereColumn('price', '>', 'previous_price')
                            ->count(),
                        'metals_down' => Market::whereIn('type', ['gold', 'silver', 'copper'])
                            ->whereNotNull('previous_price')
                            ->whereColumn('price', '<', 'previous_price')
                            ->count(),
                    ];
                });

                return [
                    'markets' => $markets,
                    'stats' => $stats,
                ];
            });

            return response()->json([
                'success' => true,
                ...$data,
                'timestamp' => now()->toIso8601String(),
                'cached' => Cache::has($cacheKey),
            ]);

        } catch (\Exception $e) {
            Log::error('Live data error: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load live data',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $market = Market::findOrFail($id);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $market->id,
                        'name' => $market->name,
                        'symbol' => $market->symbol,
                        'type' => $market->type,
                        'price' => (float) $market->price,
                        'previous_price' => (float) ($market->previous_price ?? 0),
                        'status' => $market->getPriceChangeStatus(),
                        'difference' => $market->getPriceDifference(),
                        'percentage' => $market->getPriceChangePercentage(),
                        'updated_at' => $market->updated_at->toIso8601String(),
                    ],
                ]);
            }

            return view('markets.show', compact('market'));

        } catch (\Exception $e) {
            Log::error('Market show error: ' . $e->getMessage(), [
                'market_id' => $id,
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Market not found',
            ], 404);
        }
    }

    public function stocks(Request $request)
    {
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10);

        $query = Market::where('type', 'stock');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('symbol', 'like', "%{$search}%");
            });
        }

        $stocks = $query->orderBy('name')->paginate($perPage);

        return view('markets.stocks', compact('stocks', 'search'));
    }

    public function metals(Request $request)
    {
        $type = $request->get('metal_type', 'all');
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10);

        $query = Market::whereIn('type', ['gold', 'silver', 'copper']);

        if ($type !== 'all' && in_array($type, ['gold', 'silver', 'copper'])) {
            $query->where('type', $type);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('symbol', 'like', "%{$search}%");
            });
        }

        $metals = $query->orderBy('name')->paginate($perPage);

        return view('markets.metals', compact('metals', 'type', 'search'));
    }
}
