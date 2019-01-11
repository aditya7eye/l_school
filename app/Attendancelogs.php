<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendancelogs extends Model
{
    protected $table = 'attendancelogs';
    protected $primaryKey = 'AttendanceLogId';
    public $timestamps = false;
}
