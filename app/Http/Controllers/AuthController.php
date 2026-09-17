<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Override;

class AuthController extends Controller
{
    //
    public function index()
    {
        return view('login');
    }
     public function Login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);

            if ($user->role === 'admin') {
                return redirect()->route('admin.index');
            } else if($user->role === 'user') {
                return redirect()->route('pages.presence');
            }
        } else {
            return redirect()->back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('loginauth');
        }
}
