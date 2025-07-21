<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeSchedule extends Model
{
    use HasFactory;
    protected $fillable = ['keuringen_id', 'employee_id', 'date', 'start_time', 'end_time', 'working_day'];

    public function employe(){
        return $this->belongsTo(Employe::class);
    }
}
