<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SqlInjectionProtection
{
    private array $suspiciousPatterns = [
        '/\b(union|select|insert|update|delete|drop|create|alter|truncate|exec|execute|script)\b/i',
        '/(--|#|\/\*|\*\/)/',
        '/\b(or|and)\b.*(=|<|>)/i',
        '/\b(xp_|sp_)/i',
        '/(0x[0-9a-f]+)/i',
        '/\bwaitfor\s+delay\b/i',
        '/\bbenchmark\b/i',
        '/\bsleep\b/i',
        '/\bload_file\b/i',
        '/\boutfile\b/i',
        '/\binto\s+(out|dump)file\b/i',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $inputs = $this->collectInputs($request);
        
        foreach ($inputs as $key => $value) {
            if (is_array($value)) {
                $value = implode(' ', $value);
            }
            
            if (is_string($value) && $this->containsSuspiciousPatterns($value)) {
                Log::warning('SQL injection attempt detected', [
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                    'parameter' => $key,
                    'value' => $value,
                    'user_id' => Auth::check() ? Auth::id() : null,
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid input detected',
                    ], 403);
                }

                return back()->with('error', 'Invalid input detected')->withInput();
            }
        }

        return $next($request);
    }

    private function collectInputs(Request $request): array
    {
        return array_merge(
            $request->all(),
            $request->route()?->parameters() ?? []
        );
    }

    private function containsSuspiciousPatterns(string $value): bool
    {
        foreach ($this->suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }
        return false;
    }
}
