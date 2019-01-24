<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempEmployeeLeaves extends Model
{
    protected $table = 'temp_leave';
    public $timestamps = false;

    public function school()
    {
        return $this->belongsTo('App\SchoolMaster', 'school_id');
    }
}
