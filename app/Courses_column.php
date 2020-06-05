<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Courses_column extends Model
{
    protected $table = 'courses_colums';
    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(App\Course::class);
    }
}