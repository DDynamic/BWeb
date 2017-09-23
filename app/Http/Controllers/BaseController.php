<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Auth;
use Helper;

class BaseController extends Controller
{
    /**
     * Show login page.
     *
     * @return view
     */
    public function getLogin(Request $request)
    {
        if (session('auth')) {
            return redirect()->route('dashboard');
        } else {
            return view('login');
        }
    }

    /**
     * Set session variables.
     *
     * @return view
     */
    public function postLogin(Request $request)
    {
        $district = $request->input('district');
        $username = $request->input('username');
        $password = $request->input('password');

        if ($request->input('role') == 'student') {
            $role = 'PARENTSWEB-STUDENT';
        } else if ($request->input('role') == 'parent') {
            $role = 'PARENTSWEB-PARENT';
        }

        session(['district' =>  $district]);

        if (Auth::attempt([
            'username' => $username,
            'password' => $password,
            'role' => $role,
            'district' => $district
        ])) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('auth.login');
            die();
        }
    }

    /**
     * Logout
     *
     * @return view
     */
    public function getExit(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('auth.login');
    }
}
