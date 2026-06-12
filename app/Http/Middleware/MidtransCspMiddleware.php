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

        // Deteksi environment: local pakai Vite dev server, production pakai build
        $isLocal = app()->environment('local');

        // Vite dev server bisa jalan di localhost atau IPv6 [::1]
        $viteSrc = $isLocal
            ? "http://localhost:5173 http://127.0.0.1:5173 http://[::1]:5173 ws://localhost:5173 ws://127.0.0.1:5173 ws://[::1]:5173"
            : "";

        $csp = implode('; ', array_filter([

            // Default fallback
            "default-src 'self' data:",

            // ── SCRIPT ──────────────────────────────────────────────────────
            // unsafe-eval  → WAJIB untuk Snap.js Midtrans
            // unsafe-inline → inline <script> blade
            // $viteSrc      → Vite dev server (local only)
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'"
                . " https://*.midtrans.com"
                . " https://app.sandbox.midtrans.com"
                . " https://app.midtrans.com"
                . " https://cdn.jsdelivr.net"
                . " https://unpkg.com"
                . " https://cdnjs.cloudflare.com"
                . ($isLocal ? " http://localhost:5173 http://127.0.0.1:5173 http://[::1]:5173" : ""),

            // ── STYLE ───────────────────────────────────────────────────────
            "style-src 'self' 'unsafe-inline'"
                . " https://*.midtrans.com"
                . " https://cdn.jsdelivr.net"
                . " https://unpkg.com"
                . " https://cdnjs.cloudflare.com"
                . " https://fonts.googleapis.com"
                . ($isLocal ? " http://localhost:5173 http://127.0.0.1:5173 http://[::1]:5173" : ""),

            // ── FONT ────────────────────────────────────────────────────────
            "font-src 'self' data:"
                . " https://fonts.gstatic.com"
                . " https://cdn.jsdelivr.net"
                . " https://cdnjs.cloudflare.com"
                . " https://*.midtrans.com",

            // ── IMAGE ───────────────────────────────────────────────────────
            "img-src 'self' data: blob:"
                . " https://*.midtrans.com"
                . " https://cdn.jsdelivr.net",

            // ── FRAME (popup Midtrans) ───────────────────────────────────────
            "frame-src 'self'"
                . " https://*.midtrans.com"
                . " https://app.sandbox.midtrans.com"
                . " https://app.midtrans.com",

            // ── CONNECT (fetch / XHR / WebSocket / Vite HMR) ────────────────
            // Vite HMR pakai WebSocket → wajib izinkan ws:// saat local
            "connect-src 'self'"
                . " https://*.midtrans.com"
                . " https://api.sandbox.midtrans.com"
                . " https://api.midtrans.com"
                . " https://cdn.jsdelivr.net"
                . ($isLocal ? " http://localhost:5173 http://127.0.0.1:5173 http://[::1]:5173 ws://localhost:5173 ws://127.0.0.1:5173 ws://[::1]:5173" : ""),

            // ── MEDIA / OBJECT ───────────────────────────────────────────────
            "media-src 'self'",
            "object-src 'none'",

        ]));

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}