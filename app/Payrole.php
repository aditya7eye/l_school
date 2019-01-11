<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payrole extends Model
{
    protected $table = 'payrole';
    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo('App\EmployeeModel', 'employee_id');
    }
}
