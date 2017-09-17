<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class DashboardController extends Controller
{
    /**
     * Get the dashboard.
     *
     * @return view
     */
    public function getDashboard(Request $request)
    {
        $base_uri = 'https://'.session('district').'.client.renweb.com';
        $client = new Client([
            'base_uri' => $base_uri,
            'timeout' => 30,
            'cookies' => true,
            'allow_redirects' => true
        ]);

        $jar = new \GuzzleHttp\Cookie\CookieJar();

        $client->get('/pw/', ['cookies' => $jar]);

        $client->post('/pw/index.cfm', [
            'form_params' => [
                'DistrictCode' => session('district'),
                'username' => session('username'),
                'password' => session('password'),
                'UserType' => 'PARENTSWEB-STUDENT',
                'login' => 'Login'
            ],
            'cookies' => $jar
        ]);

        $client->get('/pw/', ['cookies' => $jar]);

        $response = $client->get('/pw/student/', ['cookies' => $jar]);

        echo $response->getBody();

        //return view('dashboard');
    }

}
