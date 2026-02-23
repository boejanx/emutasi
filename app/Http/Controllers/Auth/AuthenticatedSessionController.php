<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\SystemLog;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        SystemLog::create([
            'id_user' => Auth::id(),
            'action' => 'LOGIN',
            'description' => 'User berhasil login ke dalam sistem.',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $userId = Auth::id(); // Ambil ID sebelum dilogout

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($userId) {
            SystemLog::create([
                'id_user' => $userId,
                'action' => 'LOGOUT',
                'description' => 'User berhasil keluar/logout dari sistem.',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        return redirect('/');
    }
}
