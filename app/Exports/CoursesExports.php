<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class CoursesExports implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect(
            app(\App\Http\Controllers\CourseController::class)->return_courses()
        );
    }
}