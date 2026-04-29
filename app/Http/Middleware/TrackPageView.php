<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    private const BOT_PATTERNS = ['bot', 'crawl', 'spider', 'slurp', 'curl', 'wget', 'python', 'java', 'ruby', 'go-http'];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldTrack($request)) {
            PageView::create([
                'page' => '/'.ltrim($request->path(), '/'),
                'ip_hash' => hash('sha256', $request->ip()),
                'session_id' => $request->session()->getId(),
                'user_agent' => Str::limit($request->userAgent() ?? '', 255),
                'referrer' => Str::limit($request->headers->get('referer') ?? '', 255),
                'country' => null,
                'viewed_at' => now(),
            ]);
        }

        return $response;
    }

    private function shouldTrack(Request $request): bool
    {
        if (! $request->isMethod('GET')) {
            return false;
        }

        $userAgent = strtolower($request->userAgent() ?? '');

        foreach (self::BOT_PATTERNS as $pattern) {
            if (str_contains($userAgent, $pattern)) {
                return false;
            }
        }

        return true;
    }
}
