<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    
    public function loginPage()
    {

        $pageConfigs = ['myLayout' => 'blank'];
        return view('auth.login', ['pageConfigs' => $pageConfigs]);
    }

    public function loginProcess(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            return response()->json([
                'status' => 200,
                'message' => 'Login berhasil',
                'auth' => Auth::user(),
            ]);
        }
        return response()->json([
            'status' => 500,
            'message' => 'Username / Password Tidak Sesuai'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
