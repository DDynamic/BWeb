<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI;

class DashboardPresenter extends BasePresenter
{
    protected function startup()
    {
        parent::startup();
        $user = $this->getUser();

        if (!$user->isLoggedIn()) {
            $this->redirect('Authentication:login');
        }
    }

    public function renderHome()
    {
        echo 'home renderer';
    }
}
