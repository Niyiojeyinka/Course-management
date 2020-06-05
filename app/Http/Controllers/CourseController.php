<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\PopulateCourses;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    /**
     * @description  method that create test courses of 50 in quantity in background
     * @return void

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
}