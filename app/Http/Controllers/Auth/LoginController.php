<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return inertia('Auth/Login');
    }

   public function store(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (!auth()->attempt($credentials, $request->remember)) {
        return back()->withErrors([
            'email' => 'Invalid credentials.'
        ]);
    }

    $request->session()->regenerate();

    $user = auth()->user();

    return match ($user->role?->name) {
        'super_admin', 'admin', 'exam_creator'
            => redirect('/admin/dashboard'),

        'student'
            => redirect('/student/dashboard'),

        default
            => redirect('/login'),
    };
}
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
