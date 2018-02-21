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

        if (isset(explode('<form action="', $response)[1])) {
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
            $exploded = explode('<table', $response);
            array_shift($exploded);

            $classname = explode('<', explode('<font face="Arial"><b>', $exploded[0])[7])[0];
            $instructor =  explode('<', explode('<font face="Arial"><b>', $exploded[0])[3])[0];
            $term = explode('<', explode('<font face="Arial"><b>', $exploded[0])[5])[0];
            $period = explode('<', explode('<font face="Arial"><b>', $exploded[0])[4])[0];
            $year = explode('<', explode('<font face="Arial"><b>', $exploded[0])[2])[0];
            array_shift($exploded);

            $work = [];
            $i = 0;

            if (isset(explode('<font size="2" face="Arial" >', explode('<tr><td><b><font size="2" face="Arial">Term Grade</font></b></td><td>', $response)[1])[2])) {
                $work['Grade'] = [
                    'percent' => trim(explode('<', explode('<font size="2" face="Arial" >', $response)[1])[0]),
                    'letter' => trim(explode('<', explode('<font size="2" face="Arial" >', explode('<tr><td><b><font size="2" face="Arial">Term Grade</font></b></td><td>', $response)[1])[2])[0])
                ];

                if (strpos($response, 'Points = ') !== false) {
                    $work['Grade']['points'] = trim(explode('<', explode('Points = ', $response)[1])[0]);
                }

                foreach ($exploded as $row) {
                    if (isset(explode('size="2">', $row)[1])) {
                        if (isset(explode('size="2">', $row)[2])) {
                            if (explode('<', explode('size="2">', $row)[2])[0]) {
                                $category = explode('<', explode('size="2">', $row)[2])[0];
                            } else {
                                $category = explode('<', explode('size="2">', $row)[1])[0];
                            }
                        } else {
                            $category = explode('<', explode('size="2">', $row)[1])[0];
                        }

                        if ($row !== end($exploded)) {
                            $work[$category] = [];
                            $assignments = explode('<td align="left" ><font size="1" face="Arial">', $exploded[$i + 1]);
                            $average = trim(explode('<', explode('<font size="2" face="Arial">', explode('<p align="left"><b><font size="2" face="Arial">Category Average</font></b></td><td>', $exploded[$i + 1])[1])[1])[0]);

                            array_push($work[$category], ['catavg' => $average]);
                            array_shift($assignments);

                            foreach ($assignments as $assignment) {
                                $values = explode('<td ', $assignment);
                                array_push($work[$category], [
                                    'name' => trim(explode('<', $values[0])[0]),
                                    'pts' => trim(explode("</", explode('k">', $values[1])[1])[0]),
                                    'max' => trim(explode('<', explode('color="Black">', $values[2])[1])[0]),
                                    'avg' => trim(explode('<', explode('color="Black">', $values[3])[1])[0]),
                                    'status' => trim(explode('<', explode('color="Black">', $values[4])[1])[0]),
                                    'due' => trim(explode('<', explode('color="Black">', $values[5])[1])[0]),
                                    'curve' => trim(explode('<', explode('color="Black">', $values[6])[1])[0]),
                                    'bonus' => trim(explode('<', explode('color="Black">', $values[7])[1])[0])
                                ]);
                            }
                        }
                    }
                    $i++;
                }

                $gradebook = true;

                $this->template->classname = $classname;
                $this->template->instructor = $instructor;
                $this->template->term = $term;
                $this->template->period = $period;
                $this->template->year = $year;
                $this->template->work = $work;
            } else {
                $gradebook = false;
            }
        } else {
            $gradebook = false;
        }

        $this->template->gradebook = $gradebook;
    }
}
