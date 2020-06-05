<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded = [];

    public function courses_column()
    {
        return $this->hasOne(App\Courses_column::class);
    }
}