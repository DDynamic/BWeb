<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    /**
     * Show login page.
     *
     * @return view
     */
    public function getLogin(Request $request)
    {
        if ($request->session()->has('username')) {
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
        session(['district' => $request->input('district')]);
        session(['username' => $request->input('username')]);
        session(['password' => $request->input('password')]);

        if ($request->input('role') == 'student') {
            session(['role' => 'PARENTSWEB-STUDENT']);
        } else if ($request->input('role') == 'parent') {
            session(['role' => 'PARENTSWEB-PARENT']);
        }

        return redirect()->route('login');
    }

    /**
     * Logout
     *
     * @return view
     */
    public function getExit(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }
}
