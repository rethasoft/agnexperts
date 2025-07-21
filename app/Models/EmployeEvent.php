<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Inspections\Models\Inspection;

class EmployeEvent extends Model
{
    use HasFactory;
    protected $fillable = [
        'inspection_id',
        'employee_id',
        'title',
        'start',
        'end',
        'color'
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }

}
