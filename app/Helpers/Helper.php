<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Helper
{
    public static function client()
    {
        if (session('district') !== null) {
            $base_uri = 'https://'.session('district').'.client.renweb.com';
            $client = new Client([
                'base_uri' => $base_uri,
                'timeout' => 30,
                'cookies' => true,
                'allow_redirects' => true
            ]);

            return $client;
        } else {
            return null;
        }
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
