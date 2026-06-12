<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MidtransCspMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $isLocal = app()->environment('local');

        $csp = implode('; ', [

            "default-src 'self' data:",

            // ── SCRIPT ──────────────────────────────────────────────────────
            // + code.jquery.com  → jQuery
            // + cdn.datatables.net, cdn.select2.org → plugin jQuery umum
            implode(' ', array_filter([
                "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
                "https://*.midtrans.com",
                "https://app.sandbox.midtrans.com",
                "https://app.midtrans.com",
                "https://cdn.jsdelivr.net",
                "https://unpkg.com",
                "https://cdnjs.cloudflare.com",
                "https://code.jquery.com",           // ← jQuery CDN
                "https://cdn.datatables.net",        // ← DataTables (kalau pakai)
                "https://cdn.select2.org",           // ← Select2 CDN alternatif
                $isLocal ? "http://localhost:5173 http://127.0.0.1:5173 http://[::1]:5173" : null,
            ])),

            // ── STYLE ───────────────────────────────────────────────────────
            implode(' ', array_filter([
                "style-src 'self' 'unsafe-inline'",
                "https://*.midtrans.com",
                "https://cdn.jsdelivr.net",
                "https://unpkg.com",
                "https://cdnjs.cloudflare.com",
                "https://fonts.googleapis.com",
                "https://code.jquery.com",
                "https://cdn.datatables.net",
                "https://cdn.select2.org",
                $isLocal ? "http://localhost:5173 http://127.0.0.1:5173 http://[::1]:5173" : null,
            ])),

            // ── FONT ────────────────────────────────────────────────────────
            "font-src 'self' data:"
                . " https://fonts.gstatic.com"
                . " https://cdn.jsdelivr.net"
                . " https://cdnjs.cloudflare.com"
                . " https://*.midtrans.com",

            // ── IMAGE ───────────────────────────────────────────────────────
            // + maps.gstatic.com, maps.googleapis.com → Google Maps tiles
            "img-src 'self' data: blob:"
                . " https://*.midtrans.com"
                . " https://cdn.jsdelivr.net"
                . " https://maps.gstatic.com"
                . " https://*.googleapis.com"
                . " https://*.ggpht.com",

            // ── FRAME ───────────────────────────────────────────────────────
            // + google.com, maps.google.com → Google Maps embed / reCAPTCHA
            "frame-src 'self'"
                . " https://*.midtrans.com"
                . " https://app.sandbox.midtrans.com"
                . " https://app.midtrans.com"
                . " https://www.google.com"           // ← Google Maps iframe
                . " https://maps.google.com"
                . " https://www.google.co.id"
                . " https://www.gstatic.com",

            // ── CONNECT (fetch / XHR / WebSocket / Vite HMR) ────────────────
            implode(' ', array_filter([
                "connect-src 'self'",
                "https://*.midtrans.com",
                "https://api.sandbox.midtrans.com",
                "https://api.midtrans.com",
                "https://cdn.jsdelivr.net",
                "https://maps.googleapis.com",       // ← Google Maps API
                $isLocal
                    ? "http://localhost:5173 http://127.0.0.1:5173 http://[::1]:5173 ws://localhost:5173 ws://127.0.0.1:5173 ws://[::1]:5173"
                    : null,
            ])),

            // ── WORKER (Google Maps pakai service worker) ────────────────────
            "worker-src 'self' blob:",

            "media-src 'self'",
            "object-src 'none'",

        ]);

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}