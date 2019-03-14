<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DatePeriod;
use DateInterval;

class Attendancelogs extends Model
{
    protected $table = 'attendancelogs';
    protected $primaryKey = 'AttendanceLogId';
    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo('App\EmployeeModel', 'EmployeeId');
    }

    public static function getSundays($y, $m)
    {
        return new DatePeriod(new DateTime("first sunday of $y-$m"), DateInterval::createFromDateString('next sunday'), new DateTime("last day of $y-$m 23:59:59"));

    }
}
