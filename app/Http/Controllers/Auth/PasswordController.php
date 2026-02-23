<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use Illuminate\Support\Facades\Auth;
use App\Models\SystemLog;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        SystemLog::create([
            'id_user' => Auth::id(),
            'action' => 'UPDATE_PASSWORD',
            'description' => 'User memperbarui password akun.',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('status', 'password-updated');
    }
}
