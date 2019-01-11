<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model
{
    protected $table = 'employees';
    protected $primaryKey = 'EmployeeId';
    public $timestamps = false;

    public function school()
    {
        return $this->belongsTo('App\SchoolMaster', 'school_id');
    }

    public function emp_type()
    {
        return $this->belongsTo('App\EmployeeType', 'employee_type_id');
    }
}
