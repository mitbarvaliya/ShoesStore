<?php

namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\Order;
use App\Models\User;
use App\Services\MarketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    public function __construct(private MarketService $marketService)
    {
    }

    public function index()
    {
        $stocks = Market::where('type', 'stock')->get();
        $metals = Market::whereIn('type', ['gold', 'silver', 'copper'])->get();

        $stats = [
            'total_markets' => Market::count(),
            'stocks_up' => Market::where('type', 'stock')
                ->whereColumn('price', '>', 'previous_price')
                ->count(),
            'stocks_down' => Market::where('type', 'stock')
                ->whereColumn('price', '<', 'previous_price')
                ->count(),
            'metals_up' => Market::whereIn('type', ['gold', 'silver', 'copper'])
                ->whereColumn('price', '>', 'previous_price')
                ->count(),
            'metals_down' => Market::whereIn('type', ['gold', 'silver', 'copper'])
                ->whereColumn('price', '<', 'previous_price')
                ->count(),
        ];

        $salesStats = $this->getSalesStats();
        $userStats = $this->getUserStats();

        return view('dashboard.index', compact('stocks', 'metals', 'stats', 'salesStats', 'userStats'));
    }

    public function refresh()
    {
        $this->marketService->fetchAllPrices();
        return redirect()->back()->with('success', 'Market data refreshed successfully!');
    }

    public function generateCharts()
    {
        Artisan::call('generate:charts');
        return redirect()->back()->with('success', 'Charts generated successfully!');
    }

    private function getSalesStats()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_price');
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();

        $last7DaysOrders = Order::where('created_at', '>=', now()->subDays(7))->count();
        $last30DaysOrders = Order::where('created_at', '>=', now()->subDays(30))->count();

        $dailySales = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as orders'), DB::raw('SUM(total_price) as revenue'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'pending_orders' => $pendingOrders,
            'completed_orders' => $completedOrders,
            'last7days_orders' => $last7DaysOrders,
            'last30days_orders' => $last30DaysOrders,
            'daily_sales' => $dailySales,
        ];
    }

    private function getUserStats()
    {
        $totalUsers = User::count();
        $newUsersToday = User::whereDate('registered_at', today())->count();
        $newUsersThisWeek = User::where('registered_at', '>=', now()->subDays(7))->count();
        $newUsersThisMonth = User::where('registered_at', '>=', now()->subDays(30))->count();

        $dailyRegistrations = User::select(DB::raw('DATE(registered_at) as date'), DB::raw('COUNT(*) as registrations'))
            ->where('registered_at', '>=', now()->subDays(30))
            ->groupBy(DB::raw('DATE(registered_at)'))
            ->orderBy('date')
            ->get();

        return [
            'total_users' => $totalUsers,
            'new_today' => $newUsersToday,
            'new_this_week' => $newUsersThisWeek,
            'new_this_month' => $newUsersThisMonth,
            'daily_registrations' => $dailyRegistrations,
        ];
    }
}
