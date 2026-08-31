<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['code', 'name', 'status'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function assignments()
    {
        return $this->hasMany(InstructorAssignment::class);
    }
}
