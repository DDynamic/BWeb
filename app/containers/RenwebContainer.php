<?php

namespace App\Containers;

use Nette\DI\Container;

use GuzzleHttp\Client;

class RenwebContainer extends Container
{
    public function createServiceRenweb() {
        $base_uri = 'https://'.$this->parameters['district'].'.client.renweb.com';
        $client = new Client([
            'base_uri' => $base_uri,
            'timeout' => 30,
            'cookies' => true,
            'allow_redirects' => true
        ]);

        return $client;
    }
}
