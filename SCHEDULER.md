# Market Data Scheduler

## Overview
The Laravel scheduler automatically fetches market prices every minute without manual intervention.

## Scheduled Tasks

| Task | Frequency | Description |
|------|-----------|-------------|
| `market:fetch --type=all` | Every minute | Fetches all market data (stocks + metals) |
| `market:fetch --type=stocks` | Every minute | Fetches stock prices |
| `market:fetch --type=metals` | Every 5 minutes | Fetches metal prices |

## Setup Instructions

### Option 1: Manual Scheduler (Development)
```bash
php artisan schedule:work
```

### Option 2: Schedule.bat (Windows)
Double-click `schedule.bat` to start the scheduler.

### Option 3: Windows Task Scheduler
1. Open Task Scheduler
2. Create Basic Task
3. Set trigger: Daily, start time
4. Action: Start a program
5. Program: `php`
6. Arguments: `artisan schedule:run`
7. Start in: `D:\Xampp\htdocs\laravel-app`

### Option 4: Supervisor (Linux/Production)
```ini
[program:laravel-scheduler]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan schedule:run
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/log/laravel-scheduler.log
```

## Verification
```bash
php artisan schedule:list
```

## Logs
Check logs at: `storage/logs/market-fetch.log`
