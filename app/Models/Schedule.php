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
        'grace_period_minutes',
        'room',
        'status',
    ];

    protected $casts = [
        'grace_period_minutes' => 'integer',
    ];

    public function instructorAssignment()
    {
        return $this->belongsTo(InstructorAssignment::class);
    }

    public function attendanceSessions()
    {
        return $this->hasMany(AttendanceSession::class);
    }
}

