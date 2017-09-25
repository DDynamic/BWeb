<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ConnectException;

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

            try {
                $client->get('/');
            } catch (ConnectException $e) {
                return false;
            }

            return $client;
        } else {
            return false;
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
