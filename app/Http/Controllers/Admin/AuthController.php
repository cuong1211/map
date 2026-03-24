<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.locations.index');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $adminUsername = env('ADMIN_USERNAME', 'admin');
        $adminPassword = env('ADMIN_PASSWORD', 'admin123');

        if ($request->username === $adminUsername && $request->password === $adminPassword) {
            session(['admin_logged_in' => true, 'admin_username' => $request->username]);
            return redirect()->route('admin.locations.index')->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors(['login' => 'Tên đăng nhập hoặc mật khẩu không đúng.'])->withInput();
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_logged_in', 'admin_username']);
        return redirect()->route('admin.login')->with('success', 'Đã đăng xuất thành công.');
    }
}
