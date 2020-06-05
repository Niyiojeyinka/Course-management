<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $guarded = [];
    public function course()
    {
        return $this->belongsTo(App\Course::class);
    }

    public function user()
    {
        return $this->belongsToMany(App\User::class);
    }
}