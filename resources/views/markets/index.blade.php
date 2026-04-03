@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Market Data</h1>
        <button id="refreshAll" class="btn btn-primary">
            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            <i class="fas fa-sync-alt mr-1"></i> Refresh All
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('markets.index') }}" class="form-inline">
                        <div class="input-group">
                            <select name="type" class="form-control" onchange="this.form.submit()">
                                <option value="all" {{ ($type ?? 'all') === 'all' ? 'selected' : '' }}>All Markets</option>
                                <option value="stock" {{ ($type ?? '') === 'stock' ? 'selected' : '' }}>Stocks Only</option>
                                <option value="metals" {{ ($type ?? '') === 'metals' ? 'selected' : '' }}>Metals Only</option>
                                <option value="gold" {{ ($type ?? '') === 'gold' ? 'selected' : '' }}>Gold</option>
                                <option value="silver" {{ ($type ?? '') === 'silver' ? 'selected' : '' }}>Silver</option>
                                <option value="copper" {{ ($type ?? '') === 'copper' ? 'selected' : '' }}>Copper</option>
                            </select>
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ $search ?? '' }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if($search ?? false)
                                    <a href="{{ route('markets.index', ['type' => $type ?? 'all']) }}" class="btn btn-outline-danger">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-right">
                    <span class="text-muted">{{ $markets->total() }} results</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
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
                    <tbody>
                        @forelse($markets as $market)
                            <tr>
                                <td><strong>{{ $market->name }}</strong></td>
                                <td><span class="badge badge-secondary">{{ $market->symbol }}</span></td>
                                <td>
                                    <span class="badge 
                                        @if($market->type === 'stock') badge-info
                                        @elseif($market->type === 'gold') badge-warning
                                        @elseif($market->type === 'silver') badge-secondary
                                        @else badge-orange
                                        @endif">
                                        {{ ucfirst($market->type) }}
                                    </span>
                                </td>
                                <td class="font-weight-bold">${{ number_format($market->price, 2) }}</td>
                                <td>${{ number_format($market->previous_price ?? 0, 2) }}</td>
                                <td class="{{ $market->isPriceIncreased() ? 'text-success' : ($market->isPriceDecreased() ? 'text-danger' : 'text-muted') }}">
                                    @if($market->isPriceIncreased())
                                        <i class="fas fa-arrow-up mr-1"></i>+${{ number_format($market->getPriceDifference(), 2) }}
                                    @elseif($market->isPriceDecreased())
                                        <i class="fas fa-arrow-down mr-1"></i>-${{ number_format(abs($market->getPriceDifference()), 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="{{ $market->isPriceIncreased() ? 'text-success' : ($market->isPriceDecreased() ? 'text-danger' : 'text-muted') }}">
                                    @if($market->getPriceChangePercentage() != 0)
                                        {{ $market->getPriceChangePercentage() > 0 ? '+' : '' }}{{ $market->getPriceChangePercentage() }}%
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($market->isPriceIncreased())
                                        <span class="badge badge-success"><i class="fas fa-arrow-up"></i> UP</span>
                                    @elseif($market->isPriceDecreased())
                                        <span class="badge badge-danger"><i class="fas fa-arrow-down"></i> DOWN</span>
                                    @else
                                        <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No markets found. Try adjusting your filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($markets->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $markets->firstItem() ?? 0 }} to {{ $markets->lastItem() ?? 0 }} of {{ $markets->total() }} entries
                    </div>
                    <div>
                        <select class="form-control form-control-sm d-inline-block" style="width: auto;" onchange="updatePerPage(this.value)">
                            <option value="5" {{ $markets->perPage() == 5 ? 'selected' : '' }}>5 per page</option>
                            <option value="10" {{ $markets->perPage() == 10 ? 'selected' : '' }}>10 per page</option>
                            <option value="25" {{ $markets->perPage() == 25 ? 'selected' : '' }}>25 per page</option>
                            <option value="50" {{ $markets->perPage() == 50 ? 'selected' : '' }}>50 per page</option>
                        </select>
                        {{ $markets->appends(['type' => $type ?? 'all', 'search' => $search ?? ''])->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge-orange { background-color: #fd7e14; color: white; }
    .form-inline .input-group { flex-wrap: nowrap; }
</style>
@endpush

@push('scripts')
<script>
document.getElementById('refreshAll').addEventListener('click', function() {
    const btn = this;
    const spinner = btn.querySelector('.spinner-border');
    
    btn.disabled = true;
    spinner.classList.remove('d-none');

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
        alert('Failed to fetch data. Please try again.');
        btn.disabled = false;
        spinner.classList.add('d-none');
    });
});

function updatePerPage(value) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', value);
    url.searchParams.delete('page');
    window.location.href = url.toString();
}
</script>
@endpush
@endsection
