<?php
$u = \App\Models\User::where('name', 'like', '%Trafalgar%')->first();
echo $u->role . ' - ';
echo \App\Models\Instructor::where('user_id', $u->id)->exists() ? 'In Instructor table' : 'Not in Instructor table';
echo ' - ';
echo \App\Models\Student::where('user_id', $u->id)->exists() ? 'In Student table' : 'Not in Student table';
