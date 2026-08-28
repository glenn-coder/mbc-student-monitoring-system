<?php

use Illuminate\Support\Facades\DB;

$affectedStudents = DB::update("UPDATE users SET role = 'student' WHERE id IN (SELECT user_id FROM students)");
$affectedInstructors = DB::update("UPDATE users SET role = 'instructor' WHERE id IN (SELECT user_id FROM instructors)");
echo "Students updated: $affectedStudents\n";
echo "Instructors updated: $affectedInstructors\n";
