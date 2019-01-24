<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempPayrole extends Model
{
    protected $table = 'temp_payrole';
    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo('App\EmployeeModel', 'employee_id');
    }
}
