<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Helper;

class DashboardController extends Controller
{
    /**
     * Get the dashboard.
     *
     * @return view
     */
    public function getDashboard(Request $request)
    {
        $client = Helper::client();

        $response = $client->get('/pw/student/', ['cookies' => session('jar')]);
        $table = explode('<th class="col_instructor"><a href="javascript:void(0);">Instructor</a><span class="sort_arrow"></span><span class="sort_arrow_up"></span></th>', $response->getBody());
        $rows = explode('<tr', explode('</tbody>', explode('<tbody>', $table[1])[1])[0]);

        $classes = [];
        array_shift($rows);

        foreach ($rows as $row) {
            $extracted = explode('">', explode('col_subject"', $row)[1]);
            $classid = explode('classid=', $extracted[0])[1];
            $studentid = explode('&', explode('studentid=', $extracted[0])[1])[0];

            $name = explode('</a>', $extracted[1])[0];
            $grade = explode('</a>', $extracted[3])[0];
            $email = explode('"', explode('mailto:', $extracted[4])[1])[0];
            $instructor = explode('</a>', $extracted[5])[0];

            if (empty($grade)) {
                $grade = 'N/A';
            }

            array_push($classes, [
                'classid' => $classid,
                'studentid' => $studentid,
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
            $brief = '<b>Looking good!</b> Give yourself a high five.';
            $alert = 'success';
        }

        return view('dashboard', ['classes' => $classes, 'brief' => $brief, 'alert' => $alert]);
    }

}
