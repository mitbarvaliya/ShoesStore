# Real-Time Market Data - WebSocket Setup Guide

## Current Implementation

### Auto-Refresh (Active)
- **Dashboard**: `/market-dashboard`
- **Refresh Interval**: 5 seconds
- **API Endpoint**: `GET /markets/api/live`

### Features
- Toggle auto-refresh ON/OFF
- Countdown timer showing next refresh
- Visual flash animation on price changes (green for up, red for down)
- Live stats update without page reload

## WebSocket Upgrade (Optional)

### Option 1: Laravel Reverb (Recommended for Production)

```bash
# 1. Install Laravel Reverb
php artisan install:reverb

# 2. Update .env
BROADCAST_CONNECTION=reverb

# 3. Enable broadcasting in bootstrap/app.php
->withBroadcasting()

# 4. Update Event to broadcast
# (Already configured in app/Events/MarketPriceUpdated.php)

# 5. Run Reverb server
php artisan reverb:start

# 6. Frontend JavaScript for WebSocket
```

### Option 2: Pusher (Third-Party)

```bash
# 1. Install Pusher
composer require pusher/pusher-php-server

# 2. Update .env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

# 3. Install frontend dependencies
npm install laravel-echo pusher-js

# 4. Configure Echo in resources/js/bootstrap.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});
```

### Frontend WebSocket Client

```javascript
// Add to dashboard view
<script>
    // Real-time WebSocket listener
    window.Echo.channel('market-prices')
        .listen('MarketPriceUpdated', (event) => {
            updateMarketRow(event.market);
            updateStats();
        });

    function updateMarketRow(market) {
        const row = document.getElementById(`row-${market.symbol}`);
        if (row) {
            // Update price, flash animation, etc.
        }
    }
</script>
```

### Option 3: Soketi (Self-Hosted)

```bash
# 1. Install Soketi
docker pull quay.io/soketi/soketi:latest

# 2. Run Soketi
docker run -d -p 6001:6001 \
  -e SOKETI_DEFAULT_APP_ID=app-id \
  -e SOKETI_DEFAULT_APP_KEY=app-key \
  -e SOKETI_DEFAULT_APP_SECRET=app-secret \
  quay.io/soketi/soketi:latest
```

## Broadcast Channels

| Channel | Type | Purpose |
|---------|------|---------|
| `market-prices` | Public | Real-time price updates |
| `market-updates` | Private | Authenticated user updates |

## Event Structure

```json
{
  "market": {
    "id": 1,
    "symbol": "AAPL",
    "name": "Apple Inc.",
    "type": "stock",
    "price": 178.50,
    "previous_price": 177.25,
    "status": "up",
    "difference": 1.25,
    "percentage": 0.71
  },
  "timestamp": "2024-01-01T12:00:00+00:00"
}
```

## Testing

```bash
# Test API endpoint
curl http://localhost:8000/markets/api/live

# Broadcast event manually
php artisan tinker --execute="
    \$market = App\Models\Market::first();
    App\Events\MarketPriceUpdated::dispatch(\$market);
"
```
