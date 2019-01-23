<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveLeft extends Model
{
    protected $table = 'employee_leave_left';
    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo('App\EmployeeModel', 'employee_id');
    }
}
