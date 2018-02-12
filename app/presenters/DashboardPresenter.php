<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI;

use App\Containers\RenwebContainer;

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
        $user = $this->user;

        $container = new RenwebContainer(['district' => $user->getIdentity()->district]);
        $client = $container->getService('renweb');

        $response = $client->get('/pw/student/', [
            'cookies' => $user->getIdentity()->cookies
        ])->getBody();

        $user = explode('</h3>', explode('<h3>', $response)[1])[0];
        $table = explode('<th class="col_instructor"><a href="javascript:void(0);">Instructor</a><span class="sort_arrow"></span><span class="sort_arrow_up"></span></th>', $response);
        $rows = explode('<tr', explode('</tbody>', explode('<tbody>', end($table))[1])[0]);

        $classes = [];
        array_shift($rows);

        foreach ($rows as $row) {
            $extracted = explode('">', explode('col_subject"', $row)[1]);
            $course_id = explode('classid=', $extracted[0])[1];
            $student_id = explode('&', explode('studentid=', $extracted[0])[1])[0];
            $name = explode('</a>', $extracted[1])[0];
            $grade = explode('</a>', $extracted[3])[0];
            $email = explode('"', explode('mailto:', $extracted[4])[1])[0];
            $instructor = explode('</a>', $extracted[5])[0];

            if (empty($grade)) {
                $grade = 'N/A';
            }

            array_push($classes, [
                'course_id' => $course_id,
                'student_id' => $student_id,
                'name' => $name,
                'grade' => $grade,
                'email' => $email,
                'instructor' => $instructor
            ]);
        }

        $this->template->classes = $classes;
    }
}
