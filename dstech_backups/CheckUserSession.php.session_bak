<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckUserSession
{
    // Timeout tidak aktif = 8 jam (dalam detik) - diset panjang untuk kasir self-service
    const IDLE_TIMEOUT = 28800;

    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Jika session_id kosong atau tidak cocok → logout (device lain sudah login)
            if (
                empty($user->current_session_id) ||
                $user->current_session_id !== session()->getId()
            ) {
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();

                return redirect('/')
                    ->withErrors(['message' => 'Akun ini telah login di perangkat lain.']);
            }

            // Cek waktu tidak aktif (idle timeout 30 menit)
            $lastActivity = session('last_activity_time');

            if ($lastActivity) {
                $idleSeconds = Carbon::now()->diffInSeconds(Carbon::createFromTimestamp($lastActivity));
                if ($idleSeconds > self::IDLE_TIMEOUT) {
                    // Hapus session di database agar akun bisa login lagi
                    $user->current_session_id = null;
                    $user->save();

                    Auth::logout();
                    session()->invalidate();
                    session()->regenerateToken();

                    return redirect('/')
                        ->withErrors(['message' => 'Sesi Anda telah berakhir karena tidak aktif selama 30 menit. Silakan login kembali.']);
                }
            }

            // Perbarui waktu aktivitas terakhir
            session(['last_activity_time' => Carbon::now()->timestamp]);
        }

        return $next($request);
    }
}
