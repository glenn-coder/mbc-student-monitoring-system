<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'instructor_assignment_id',
        'day_of_week',
        'start_time',
        'end_time',
        'room',
        'status',
    ];

    public function instructorAssignment()
    {
        return $this->belongsTo(InstructorAssignment::class);
    }
}
