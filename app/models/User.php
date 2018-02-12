<?php

namespace App\Models;

use Nette\Security\User as NetteUser;
use App\Containers\RenwebContainer;

class User extends NetteUser
{
    public function isLoggedIn()
    {
        $parent = parent::isLoggedIn();

        if ($parent) {
            $container = new RenwebContainer(['district' => $this->getIdentity()->district]);
            $client = $container->getService('renweb');

            try {
                $response = $client->get('/pw/', ['cookies' => $this->getIdentity()->cookies])->getBody();
            } catch (ConnectException $e) {
                return false;
            }

            if (strpos($response, 'Logout') !== false) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
