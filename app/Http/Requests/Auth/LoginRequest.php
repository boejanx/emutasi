<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Services\SsoService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'email' => 'NIP',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * Flow:
     * 1. Check if user exists in local users table → attempt login locally
     * 2. If not found locally → authenticate via Polakesatu SSO
     * 3. If SSO succeeds → create/update user in local DB and log in
     * 4. If both fail → throw validation error
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $nip = $this->input('email');
        $password = $this->input('password');

        // Step 1: Try to authenticate against local database first
        if (Auth::attempt(['email' => $nip, 'password' => $password], $this->boolean('remember'))) {
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // Step 2: User not found or password mismatch → try SSO
        $ssoService = app(SsoService::class);
        $ssoResponse = $ssoService->authenticate($nip, $password);

        if ($ssoResponse) {
            // SSO succeeded → create or update user in local database
            $user = User::updateOrCreate(
                ['email' => $nip],
                [
                    'name' => $ssoResponse['data']['nama'] ?? $ssoResponse['nama'] ?? $nip,
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                ]
            );

            // Log the user in
            Auth::login($user, $this->boolean('remember'));
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // Both local and SSO failed
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => 'NIP atau Password salah.',
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
