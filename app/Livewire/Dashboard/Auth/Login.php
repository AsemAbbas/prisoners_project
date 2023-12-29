<?php

namespace App\Livewire\Dashboard\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class Login extends Component
{
    public $email;
    public $password;
    public $attempts;

    public function mount()
    {
        $this->attempts = 0;

        if (Auth::check()) {
            return redirect('/dashboard/prisoners'); // Redirect authenticated users
        }
    }

    public function login()
    {
        $validatedData = $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $emailKey = 'login_attempts_' . $this->email;
        $attempts = Cache::get($emailKey, 0);

        if ($attempts >= 5) {
            $lockedOutUntil = Cache::get('login_locked_out_' . $this->email);
            if ($lockedOutUntil && now() < $lockedOutUntil) {
                $remainingTime = max(1, now()->diffInSeconds($lockedOutUntil));
                $errorMessage = "يرجى المحاولة فيما بعد. الوقت المتبقي: $remainingTime ثانية.";

                session()->flash('error', $errorMessage);
                return redirect()->back();
            }
        }

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::guard('web')->attempt($credentials)) {
            // Reset attempts on successful login
            Cache::forget($emailKey);

            // Remove lockout information from cache on successful login
            Cache::forget('login_locked_out_' . $this->email);

            return redirect()->intended('/dashboard/prisoners');
        } else {
            $attempts++;

            if ($attempts >= 5) {
                $lockedOutUntil = now()->addMinutes(); // Lockout for 2 minutes
                // Store lockout information in cache
                Cache::put('login_locked_out_' . $this->email, $lockedOutUntil, now()->addMinutes());
            }

            // Store login attempts in cache
            Cache::put($emailKey, $attempts);

            session()->flash('error', 'بيانات غير صحيحة حاول مرة اخرى.');
            return redirect()->back();
        }
    }

    public function render(): View
    {
        return view('livewire.dashboard.auth.login');
    }
}
