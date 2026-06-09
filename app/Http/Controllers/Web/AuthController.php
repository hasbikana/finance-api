<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\LoginRequest;
use App\Http\Requests\Web\RegisterRequest;
use App\Http\Requests\Web\ProfileRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $service
    ) {}

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $result = $this->service->login($request->email, $request->password);

        if (!$result) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        Auth::login($result['user']);
        $request->session()->regenerate();

        return redirect()->intended(route('web.dashboard'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->service->register($request->validated());

        Auth::login($result['user']);

        return redirect()->route('web.dashboard')
            ->with('success', 'Registrasi berhasil! Selamat datang di FinanceApp.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('web.login');
    }

    public function profile()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function updateProfile(ProfileRequest $request)
    {
        $this->service->updateProfile(Auth::user(), $request->validated());

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $success = $this->service->updatePassword(
            Auth::user(),
            $validated['current_password'],
            $validated['password']
        );

        if (!$success) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
