<?php

namespace App\Http\Controllers;

use Auth;
use App\Courses;
use App\Enrollment;
use App\Courses_column;
use Illuminate\Http\Request;
use App\Exports\CoursesExports;
use Illuminate\Support\Facades\Log;
use App\Events\PopulateCoursesEvent;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\PopulateCourses as PopulateCourses;

class CourseController extends Controller
{
    /**
     * @description  method that create test courses of 50 in quantity in background
     * @return json

    **/
    public function createCoursesBg()
    {
        //dispatch(new PopulateCourses());

        //PopulateCourses::dispatch()->delay(now()->addMinutes(1));

        event(new PopulateCoursesEvent());

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

    public function return_courses()
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
        return $return_courses;
    }

    /**
     * @description  method that create test courses of 50 in quantity in background
     * @return json

    **/
    public function get_courses()
    {
        return response()->json(
            [
                'result' => 1,
                'data' => ['courses' => $this->return_courses()],
            ],
            200
        );
    }

    public function downloadExcel()
    {
        return Excel::download(new CoursesExports(), 'courses.xlsx');
    }
}