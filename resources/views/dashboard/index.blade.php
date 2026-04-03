@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-chart-line mr-2"></i>Market Dashboard
                    <span id="connectionStatus" class="badge badge-secondary ml-2">
                        <i class="fas fa-circle text-warning"></i> Auto-refresh: 5s
                    </span>
                </h1>
                <div>
                    <div class="btn-group mr-2">
                        <button id="autoRefreshToggle" class="btn btn-success" onclick="toggleAutoRefresh()">
                            <i class="fas fa-play mr-1"></i> Auto ON
                        </button>
                        <button onclick="refreshData()" class="btn btn-primary">
                            <i class="fas fa-sync-alt mr-1"></i> Refresh Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Markets</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalMarkets">{{ $stats['total_markets'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-globe fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Stocks Up</div>
                            <div class="h5 mb-0 font-weight-bold text-success" id="stocksUp">{{ $stats['stocks_up'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Stocks Down</div>
                            <div class="h5 mb-0 font-weight-bold text-danger" id="stocksDown">{{ $stats['stocks_down'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Metals Up</div>
                            <div class="h5 mb-0 font-weight-bold text-success" id="metalsUp">{{ $stats['metals_up'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Metals Down</div>
                            <div class="h5 mb-0 font-weight-bold text-danger" id="metalsDown">{{ $stats['metals_down'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Last Updated</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800" id="lastUpdated">{{ now()->format('H:i:s') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">All Markets</h6>
                    <div id="countdown" class="badge badge-info">Next refresh: 5s</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="marketTable" width="100%" cellspacing="0">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Symbol</th>
                                    <th>Type</th>
                                    <th>Price</th>
                                    <th>Previous Price</th>
                                    <th>Change</th>
                                    <th>Change %</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="marketTableBody">
                                @forelse($stocks as $stock)
                                    <tr class="market-row" id="row-{{ $stock->symbol }}" data-type="stock" data-symbol="{{ $stock->symbol }}">
                                        <td><strong>{{ $stock->name }}</strong></td>
                                        <td><span class="badge badge-secondary">{{ $stock->symbol }}</span></td>
                                        <td><span class="badge badge-info">Stock</span></td>
                                        <td class="font-weight-bold price-cell">${{ number_format($stock->price, 2) }}</td>
                                        <td class="previous-price-cell">${{ number_format($stock->previous_price ?? 0, 2) }}</td>
                                        <td class="change-amount {{ $stock->getPriceChangeStatus() === 'up' ? 'text-success' : ($stock->getPriceChangeStatus() === 'down' ? 'text-danger' : 'text-muted') }}">
                                            @if($stock->isPriceIncreased())
                                                <i class="fas fa-arrow-up mr-1"></i>+${{ number_format($stock->getPriceDifference(), 2) }}
                                            @elseif($stock->isPriceDecreased())
                                                <i class="fas fa-arrow-down mr-1"></i>-${{ number_format(abs($stock->getPriceDifference()), 2) }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="change-percent {{ $stock->getPriceChangeStatus() === 'up' ? 'text-success' : ($stock->getPriceChangeStatus() === 'down' ? 'text-danger' : 'text-muted') }}">
                                            @if($stock->getPriceChangePercentage() != 0)
                                                {{ $stock->getPriceChangePercentage() > 0 ? '+' : '' }}{{ $stock->getPriceChangePercentage() }}%
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="status-cell">
                                            @if($stock->isPriceIncreased())
                                                <span class="badge badge-success px-3 py-2">
                                                    <i class="fas fa-arrow-up mr-1"></i> UP
                                                </span>
                                            @elseif($stock->isPriceDecreased())
                                                <span class="badge badge-danger px-3 py-2">
                                                    <i class="fas fa-arrow-down mr-1"></i> DOWN
                                                </span>
                                            @else
                                                <span class="badge badge-secondary px-3 py-2">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No stock data available</td>
                                    </tr>
                                @endforelse

                                @foreach($metals as $metal)
                                    <tr class="market-row" id="row-{{ $metal->symbol }}" data-type="metal" data-symbol="{{ $metal->symbol }}">
                                        <td><strong>{{ $metal->name }}</strong></td>
                                        <td><span class="badge badge-secondary">{{ $metal->symbol }}</span></td>
                                        <td>
                                            <span class="badge 
                                                @if($metal->type === 'gold') badge-warning
                                                @elseif($metal->type === 'silver') badge-secondary
                                                @else badge-orange
                                                @endif">
                                                {{ ucfirst($metal->type) }}
                                            </span>
                                        </td>
                                        <td class="font-weight-bold price-cell">${{ number_format($metal->price, 2) }}</td>
                                        <td class="previous-price-cell">${{ number_format($metal->previous_price ?? 0, 2) }}</td>
                                        <td class="change-amount {{ $metal->getPriceChangeStatus() === 'up' ? 'text-success' : ($metal->getPriceChangeStatus() === 'down' ? 'text-danger' : 'text-muted') }}">
                                            @if($metal->isPriceIncreased())
                                                <i class="fas fa-arrow-up mr-1"></i>+${{ number_format($metal->getPriceDifference(), 2) }}
                                            @elseif($metal->isPriceDecreased())
                                                <i class="fas fa-arrow-down mr-1"></i>-${{ number_format(abs($metal->getPriceDifference()), 2) }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="change-percent {{ $metal->getPriceChangeStatus() === 'up' ? 'text-success' : ($metal->getPriceChangeStatus() === 'down' ? 'text-danger' : 'text-muted') }}">
                                            @if($metal->getPriceChangePercentage() != 0)
                                                {{ $metal->getPriceChangePercentage() > 0 ? '+' : '' }}{{ $metal->getPriceChangePercentage() }}%
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="status-cell">
                                            @if($metal->isPriceIncreased())
                                                <span class="badge badge-success px-3 py-2">
                                                    <i class="fas fa-arrow-up mr-1"></i> UP
                                                </span>
                                            @elseif($metal->isPriceDecreased())
                                                <span class="badge badge-danger px-3 py-2">
                                                    <i class="fas fa-arrow-down mr-1"></i> DOWN
                                                </span>
                                            @else
                                                <span class="badge badge-secondary px-3 py-2">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
    .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
    .border-left-danger { border-left: 0.25rem solid #e74a3b !important; }
    .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
    
    .text-gray-300 { color: #dddfeb !important; }
    .text-gray-800 { color: #5a5c69 !important; }
    
    .badge-orange { background-color: #fd7e14; color: white; }
    
    .market-row.up { background-color: rgba(28, 200, 138, 0.1); }
    .market-row.down { background-color: rgba(231, 76, 60, 0.1); }
    
    .market-row:hover {
        transform: scale(1.01);
        transition: transform 0.2s;
    }
    
    .market-row.flash-green {
        animation: flashGreen 0.5s ease-out;
    }
    
    .market-row.flash-red {
        animation: flashRed 0.5s ease-out;
    }
    
    @keyframes flashGreen {
        0% { background-color: rgba(28, 200, 138, 0.5); }
        100% { background-color: transparent; }
    }
    
    @keyframes flashRed {
        0% { background-color: rgba(231, 76, 60, 0.5); }
        100% { background-color: transparent; }
    }
    
    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #e3e6f0;
    }
    
    .change-amount, .change-percent {
        font-weight: 600;
    }
    
    .websocket-connected {
        color: #1cc88a !important;
    }
    
    .websocket-disconnected {
        color: #e74a3b !important;
    }
</style>
@endpush

@push('scripts')
<script>
    const AUTO_REFRESH_INTERVAL = 5000;
    let autoRefreshEnabled = true;
    let countdownTimer;
    let refreshTimer;
    let countdown = 5;

    function toggleAutoRefresh() {
        autoRefreshEnabled = !autoRefreshEnabled;
        const btn = document.getElementById('autoRefreshToggle');
        const status = document.getElementById('connectionStatus');
        
        if (autoRefreshEnabled) {
            btn.className = 'btn btn-success';
            btn.innerHTML = '<i class="fas fa-play mr-1"></i> Auto ON';
            status.innerHTML = '<i class="fas fa-circle text-success"></i> Auto-refresh: 5s';
            startAutoRefresh();
        } else {
            btn.className = 'btn btn-secondary';
            btn.innerHTML = '<i class="fas fa-pause mr-1"></i> Auto OFF';
            status.innerHTML = '<i class="fas fa-circle text-muted"></i> Paused';
            stopAutoRefresh();
        }
    }

    function startAutoRefresh() {
        countdown = 5;
        updateCountdown();
        
        refreshTimer = setInterval(() => {
            fetchMarketData();
        }, AUTO_REFRESH_INTERVAL);
        
        countdownTimer = setInterval(() => {
            countdown--;
            if (countdown <= 0) countdown = 5;
            updateCountdown();
        }, 1000);
    }

    function stopAutoRefresh() {
        clearInterval(refreshTimer);
        clearInterval(countdownTimer);
        document.getElementById('countdown').textContent = 'Paused';
    }

    function updateCountdown() {
        document.getElementById('countdown').textContent = `Next refresh: ${countdown}s`;
    }

    function fetchMarketData() {
        fetch('/markets/api/live')
            .then(response => response.json())
            .then(data => {
                updateDashboard(data);
                document.getElementById('lastUpdated').textContent = new Date().toLocaleTimeString();
                countdown = 5;
            })
            .catch(error => {
                console.error('Error fetching market data:', error);
            });
    }

    function updateDashboard(data) {
        document.getElementById('totalMarkets').textContent = data.stats.total_markets;
        document.getElementById('stocksUp').textContent = data.stats.stocks_up;
        document.getElementById('stocksDown').textContent = data.stats.stocks_down;
        document.getElementById('metalsUp').textContent = data.stats.metals_up;
        document.getElementById('metalsDown').textContent = data.stats.metals_down;

        data.markets.forEach(market => {
            const row = document.getElementById(`row-${market.symbol}`);
            if (row) {
                const oldPrice = row.querySelector('.price-cell').textContent;
                const newPrice = `$${parseFloat(market.price).toFixed(2)}`;
                
                if (oldPrice !== newPrice) {
                    row.querySelector('.price-cell').textContent = newPrice;
                    row.querySelector('.previous-price-cell').textContent = `$${parseFloat(market.previous_price).toFixed(2)}`;
                    
                    const changeClass = market.status === 'up' ? 'text-success' : (market.status === 'down' ? 'text-danger' : 'text-muted');
                    const changeIcon = market.status === 'up' ? 'fa-arrow-up' : (market.status === 'down' ? 'fa-arrow-down' : '');
                    const changePrefix = market.status === 'up' ? '+' : (market.status === 'down' ? '-' : '');
                    
                    row.querySelector('.change-amount').className = `change-amount ${changeClass}`;
                    row.querySelector('.change-percent').className = `change-percent ${changeClass}`;
                    
                    row.classList.remove('flash-green', 'flash-red');
                    void row.offsetWidth;
                    row.classList.add(market.status === 'up' ? 'flash-green' : (market.status === 'down' ? 'flash-red' : ''));
                    
                    const statusCell = row.querySelector('.status-cell');
                    if (market.status === 'up') {
                        statusCell.innerHTML = `<span class="badge badge-success px-3 py-2"><i class="fas fa-arrow-up mr-1"></i> UP</span>`;
                    } else if (market.status === 'down') {
                        statusCell.innerHTML = `<span class="badge badge-danger px-3 py-2"><i class="fas fa-arrow-down mr-1"></i> DOWN</span>`;
                    } else {
                        statusCell.innerHTML = `<span class="badge badge-secondary px-3 py-2">-</span>`;
                    }
                }
            }
        });
    }

    function refreshData() {
        const btn = event.target.closest('button');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
        
        fetch('/markets/fetch', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            location.reload();
        })
        .catch(error => {
            alert('Failed to refresh data. Please try again.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-sync-alt mr-1"></i> Refresh Now';
        });
    }

    // WebSocket Integration Placeholder
    class MarketWebSocket {
        constructor(url) {
            this.url = url;
            this.socket = null;
            this.reconnectAttempts = 0;
            this.maxReconnectAttempts = 5;
        }

        connect() {
            // Placeholder for WebSocket connection
            // Example for Laravel WebSocket (Pusher/Reverb):
            // this.socket = new WebSocket(this.url);
            console.log('WebSocket connection prepared. To enable:');
            console.log('1. Install Laravel Reverb: php artisan install:reverb');
            console.log('2. Or use Pusher: composer require pusher/pusher-php-server');
            console.log('3. Uncomment the connect() method implementation');
        }

        disconnect() {
            if (this.socket) {
                this.socket.close();
            }
        }

        onMessage(callback) {
            // Placeholder for message handler
        }

        reconnect() {
            if (this.reconnectAttempts < this.maxReconnectAttempts) {
                this.reconnectAttempts++;
                setTimeout(() => this.connect(), 1000 * this.reconnectAttempts);
            }
        }
    }

    const marketWS = new MarketWebSocket('wss://your-domain.com/app/market-channel');

    $(document).ready(function() {
        $('#marketTable').DataTable({
            "order": [[ 2, "asc" ], [ 0, "asc" ]],
            "pageLength": 10,
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": -1 }
            ]
        });
        
        startAutoRefresh();
    });

    window.addEventListener('beforeunload', function() {
        stopAutoRefresh();
    });
</script>
@endpush
@endsection
