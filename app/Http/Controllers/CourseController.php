<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\PopulateCourses;
use App\Courses;
use App\Courses_column;
use App\Enrollment;
use App\Http\Controllers\Controller;
use Auth;

class CourseController extends Controller
{
    /**
     * @description  method that create test courses of 50 in quantity in background
     * @return json

    **/
    public function createCoursesBg()
    {
        dispatch(new PopulateCourses());
        return response()->json(
            [
                'result' => 1,
                'data' => [
                    'report' => 'Courses Creating going on in background',
                ],
            ],
            200
        );
    }
    /**
     * @description  method that create test courses of 50 in quantity in background
     * @return json

    **/
    public function get_courses()
    {
        $courses = Courses_column::with('course')->get();
        $return_courses = [];
        foreach ($courses as $course) {
            //dd($course->course->text);
            $enrollment = Enrollment::where('course_id', $course->course_id)
                ->where('user_id', Auth::user()->id)
                ->get()
                ->first();
            if (!empty($enrollment)) {
                //there is enrollment
                array_push($return_courses, [
                    'id' => $course->course->id,
                    'title' => $course->course->text,
                    'short_desc' => $course->short_desc,
                    'course_code' => $course->course_code,
                    'enrolled_at' => $enrollment->created_at,
                    'enrolled_status' => true,
                ]);
            } else {
                //no enrollment yet
                array_push($return_courses, [
                    'id' => $course->course->id,
                    'title' => $course->course->text,
                    'short_desc' => $course->short_desc,
                    'course_code' => $course->course_code,
                    'enrolled_at' => null,
                    'enrolled_status' => false,
                ]);
            }
        }

        return response()->json(
            ['result' => 1, 'data' => ['courses' => $return_courses]],
            200
        );
    }
}