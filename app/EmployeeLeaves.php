<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeLeaves extends Model
{
    protected $table = 'leave';
    public $timestamps = false;

    public function school()
    {
        return $this->belongsTo('App\SchoolMaster', 'school_id');
    }
}
