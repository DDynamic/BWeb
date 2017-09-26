<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Helper;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request)
    {
        $client = Helper::client();

        $response = $client->get('/pw/student/', ['cookies' => session('jar')]);
        $user = explode('</h3>', explode('<h3>', $response->getBody())[1])[0];

        $table = explode('<th class="col_instructor"><a href="javascript:void(0);">Instructor</a><span class="sort_arrow"></span><span class="sort_arrow_up"></span></th>', $response->getBody());
        $rows = explode('<tr', explode('</tbody>', explode('<tbody>', $table[1])[1])[0]);

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

        $brief = '';
        $alert = 0;
        $warning_classes = [];
        $danger_classes = [];
        $failing_classes = [];

        foreach ($classes as $class) {
            if ($class['grade'] != 'N/A') {
                if ($class['grade'] <= 70) {
                    array_push($failing_classes, $class['name']);
                } else if ($class['grade'] <= 80) {
                    array_push($danger_classes, $class['name']);
                } else if ($class['grade'] <= 90) {
                    array_push($warning_classes, $class['name']);
                }
            }
        }

        if (!empty($warning_classes)) {
            $brief = $brief.' Watch your grades in '.Helper::list($warning_classes).'.<br>';
            $alert = 'info';
        }

        if (!empty($danger_classes)) {
            $brief = $brief.' <u>You have a very low grade in '.Helper::list($danger_classes).'.</u><br>';
            $alert = 'warning';
        }

        if (!empty($failing_classes)) {
            $brief = $brief.' <b>You are failing '.Helper::list($failing_classes).'.</b><br>';
            $alert = 'danger';
        }

        if (empty($brief)) {
            $brief = '<b>Looking good!</b> Give yourself a high five. ✋';
            $alert = 'success';
        }

        return view('dashboard.index', ['user' => $user, 'classes' => $classes, 'brief' => $brief, 'alert' => $alert]);
    }

    /**
     * View a course.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCourse(Request $request, $course_id)
    {
        $client = Helper::client();

        $response = $client->get('/pw/school/class.cfm?studentid='.session('student_id').'&classid='.$course_id, ['cookies' => session('jar')])->getBody();
        $report = explode('<form action="', $response)[1];

        $district = explode('" />', explode('name="District" value="', $report)[1])[0];
        $report_type = explode('" />', explode('name="ReportType" value="', $report)[1])[0];
        $session_id = explode('" />', explode('name="sessionid" value="', $report)[1])[0];
        $report_hash = explode('" />', explode('name="ReportHash" value="', $report)[1])[0];
        $school_code = explode('" />', explode('name="SchoolCode" value="', $report)[1])[0];
        $student_id = explode('" />', explode('name="StudentID" value="', $report)[1])[0];
        $class_id = explode('" />', explode('name="ClassID2" value="', $report)[1])[0];
        $term_id = explode('" />', explode('name="TermID" value="', $report)[1])[0];

        $report_url = '/renweb/reports/parentsweb/parentsweb_reports.cfm?District='.$district.
        '&ReportType='.$report_type.
        '&sessionid='.$session_id.
        '&ReportHash='.$report_hash.
        '&SchoolCode='.$school_code.
        '&StudentID='.$student_id.
        '&ClassID='.$class_id.
        '&TermID='.$term_id;

        $response = $client->get($report_url, ['cookies' => session('jar')])->getBody();

        return view('dashboard.course', ['gradebook' => $response]);
    }
}
