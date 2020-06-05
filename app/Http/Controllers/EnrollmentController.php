<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enrollment;
use App\Course;
use Auth;

class EnrollmentController extends Controller
{
    public function enroll(Request $request)
    {
        // return response()->json(['re' => $request->course_ids]);
        //loop through
        //check if is valid course

        $reports = [];
        foreach ($request->course_ids as $course_id) {
            $valid_course = Course::where('id', $course_id)
                ->get()
                ->first();
            //  dd($valid_course);
            if (empty($valid_course)) {
                array_push($reports, [
                    'course_id' => $course_id,
                    'status' => 'Failed',
                ]);
            } else {
                Enrollment::create([
                    'course_id' => $course_id,
                    'user_id' => Auth::user()->id,
                ])->save();

                array_push($reports, [
                    'course_id' => $course_id,
                    'status' => 'success',
                ]);
            }
        }
        return response()->json(
            [
                'report' => 1,
                'data' => ['enrollments_status' => $reports],
            ],
            201
        );
    }
}