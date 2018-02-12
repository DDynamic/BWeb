<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI;

use App\Containers\RenwebContainer;

class DashboardPresenter extends BasePresenter
{
    private $client;

    protected function startup()
    {
        parent::startup();
        $user = $this->getUser();

        if (!$user->isLoggedIn()) {
            $this->redirect('Authentication:login');
        }

        $container = new RenwebContainer(['district' => $user->getIdentity()->district]);
        $this->client = $container->getService('renweb');
    }

    public function renderHome()
    {
        $user = $this->user;
        $client = $this->client;

        $response = $client->get('/pw/student/', [
            'cookies' => $user->getIdentity()->cookies
        ])->getBody();

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

            $user->getIdentity()->student_id = $student_id;
        }

        $this->template->classes = $classes;
    }

    public function renderCourse($id)
    {
        $user = $this->user;
        $client = $this->client;

        $response = $client->get('/pw/school/class.cfm?studentid='.$user->getIdentity()->student_id.'&classid='.$id, ['cookies' => $user->getIdentity()->cookies])->getBody();
        $report = explode('<form action="', $response)[1];

        $term = explode('<select class="ftermid" name="termid" onchange="window.termID', $response)[1];
        $term = explode('selected="selected">', $term)[0];
        $term = explode('value="', $term);
        $term = end($term);
        $term = explode('"', $term)[0];

        $district = explode('" />', explode('name="District" value="', $report)[1])[0];
        $report_type = explode('" />', explode('name="ReportType" value="', $report)[1])[0];
        $session_id = explode('" />', explode('name="sessionid" value="', $report)[1])[0];
        $report_hash = explode('" />', explode('name="ReportHash" value="', $report)[1])[0];
        $school_code = explode('" />', explode('name="SchoolCode" value="', $report)[1])[0];
        $student_id = explode('" />', explode('name="StudentID" value="', $report)[1])[0];
        $class_id = explode('" />', explode('name="ClassID2" value="', $report)[1])[0];

        $report_url = '/renweb/reports/parentsweb/parentsweb_reports.cfm?District='.$district.
        '&ReportType='.$report_type.
        '&sessionid='.$session_id.
        '&ReportHash='.$report_hash.
        '&SchoolCode='.$school_code.
        '&StudentID='.$student_id.
        '&ClassID='.$class_id.
        '&TermID='.$term;

        $response = $client->get($report_url, ['cookies' => $user->getIdentity()->cookies])->getBody();
        $this->template->gradebook = $response;
    }
}
