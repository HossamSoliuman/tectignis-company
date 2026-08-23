<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show(): View
    {
        return view('admin.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();

        // Two independent doors share this login: the website CMS (`role`) and
        // the operations portal (`portal_role`). Holding either one is enough.
        if (! $user->isAdmin() && ! $user->hasPortalAccess()) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'These credentials do not have admin access.',
            ]);
        }

        $request->session()->regenerate();

        $home = $user->isAdmin() ? route('admin.dashboard') : route('admin.portal.dashboard');

        return redirect()->intended($home);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
