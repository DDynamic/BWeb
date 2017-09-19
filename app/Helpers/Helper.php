<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Helper
{

    public static function auth($client, $district, $username, $password, $role)
    {
        session(['jar' =>  new \GuzzleHttp\Cookie\CookieJar()]);

        $client->get('/pw/', ['cookies' => session('jar')]);

        $client->post('/pw/index.cfm', [
            'form_params' => [
                'DistrictCode' => $district,
                'username' => $username,
                'password' => $password,
                'UserType' => $role,
                'login' => 'Login'
            ],
            'cookies' => session('jar')
        ]);

        $response = $client->get('/pw/', ['cookies' => session('jar')]);

        if (strpos($response->getBody(), 'Logout') !== false) {
            // logged in success
            session(['auth' => true]);
            return true;
        }
    }

    public static function client()
    {
        $base_uri = 'https://'.session('district').'.client.renweb.com';
        $client = new Client([
            'base_uri' => $base_uri,
            'timeout' => 30,
            'cookies' => true,
            'allow_redirects' => true
        ]);

        return $client;
    }


    public static function list($items)
    {
        $total = count($items);
        if ($total > 1) {
            $items = implode(', ' , array_slice($items, 0, $total - 1)) . ' and ' . end($items);
        } else {
            $items = implode(', ' , $items);
        }

        return $items;
    }

}
