<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorAssignment extends Model
{
    protected $fillable = [
        'instructor_id',
        'subject_id',
        'course_id',
        'year',
        'semester',
    ];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'assignment_student');
    }
}
