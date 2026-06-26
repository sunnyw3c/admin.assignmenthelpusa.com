<?php

namespace App\Http\Controllers;

use App\Services\AdminApiService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_token')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $api    = new AdminApiService();
        $result = $api->login($request->email, $request->password);

        if ($result['status'] === 200 && isset($result['data']['token'])) {
            session([
                'admin_token' => $result['data']['token'],
                'admin_user'  => $result['data']['user'],
            ]);
            return redirect()->route('dashboard');
        }

        $message = $result['data']['message'] ?? 'Login failed.';
        return back()->withErrors(['email' => $message])->withInput();
    }

    public function logout(Request $request)
    {
        $api = new AdminApiService();
        $api->logout();
        $request->session()->flush();
        return redirect()->route('login');
    }
}
