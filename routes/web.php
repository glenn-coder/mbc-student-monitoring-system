<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'instructor' => redirect()->route('instructor.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            $studentCount = \App\Models\Student::count();
            $instructorCount = \App\Models\Instructor::count();
            $subjectCount = \App\Models\Subject::count();
            $assignmentCount = \App\Models\InstructorAssignment::count();
            return view('admin.dashboard', compact('studentCount', 'instructorCount', 'subjectCount', 'assignmentCount'));
        })->name('admin.dashboard');

        Route::resource('/admin/admins', \App\Http\Controllers\Admin\AdminUserController::class)
            ->names('admin.admins');

        Route::get('/admin/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])
            ->name('admin.audit-logs.index');
        Route::get('/admin/audit-logs/export/{type}', [\App\Http\Controllers\Admin\AuditLogController::class, 'export'])
            ->name('admin.audit-logs.export');

        Route::resource('/admin/students', \App\Http\Controllers\Admin\StudentController::class)
            ->names('admin.students');

        Route::resource('/admin/subjects', \App\Http\Controllers\Admin\SubjectController::class)
            ->names('admin.subjects');

        Route::resource('/admin/courses', \App\Http\Controllers\Admin\CourseController::class)
            ->names('admin.courses');

        Route::resource('/admin/instructors', \App\Http\Controllers\Admin\InstructorController::class)
            ->names('admin.instructors');

        Route::get('/admin/instructors/{instructor}/classes', [\App\Http\Controllers\Admin\InstructorAssignmentController::class, 'instructorClasses'])->name('admin.instructors.classes');
        
        Route::get('/admin/assignments', [\App\Http\Controllers\Admin\InstructorAssignmentController::class, 'index'])->name('admin.assignments.index');
        Route::post('/admin/assignments', [\App\Http\Controllers\Admin\InstructorAssignmentController::class, 'store'])->name('admin.assignments.store');
        Route::put('/admin/assignments/{assignment}', [\App\Http\Controllers\Admin\InstructorAssignmentController::class, 'update'])->name('admin.assignments.update');
        Route::delete('/admin/assignments/{assignment}', [\App\Http\Controllers\Admin\InstructorAssignmentController::class, 'destroy'])->name('admin.assignments.destroy');
        
        Route::get('/admin/assignments/{assignment}/students', [\App\Http\Controllers\Admin\InstructorAssignmentController::class, 'classStudents'])->name('admin.assignments.students');
        Route::get('/admin/assignments/{assignment}/students/add', [\App\Http\Controllers\Admin\InstructorAssignmentController::class, 'addStudents'])->name('admin.assignments.add-students');
        Route::post('/admin/assignments/{assignment}/students', [\App\Http\Controllers\Admin\InstructorAssignmentController::class, 'storeStudents'])->name('admin.assignments.store-students');
        Route::get('/admin/timetable', [\App\Http\Controllers\Admin\TimetableController::class, 'index'])->name('admin.timetable.index');
        Route::post('/admin/timetable', [\App\Http\Controllers\Admin\TimetableController::class, 'store'])->name('admin.timetable.store');
        Route::put('/admin/timetable/{timetable}', [\App\Http\Controllers\Admin\TimetableController::class, 'update'])->name('admin.timetable.update');
        Route::delete('/admin/timetable/{timetable}', [\App\Http\Controllers\Admin\TimetableController::class, 'destroy'])->name('admin.timetable.destroy');
        
        // Reports
        Route::get('/admin/reports/students', [\App\Http\Controllers\Admin\ReportController::class, 'students'])->name('admin.reports.students');
        Route::get('/admin/reports/students/export/{type}', [\App\Http\Controllers\Admin\ReportController::class, 'exportStudents'])->name('admin.reports.students.export');
        
        Route::get('/admin/reports/inactive-students', [\App\Http\Controllers\Admin\ReportController::class, 'inactiveStudents'])->name('admin.reports.inactive-students');
        Route::get('/admin/reports/inactive-students/export/{type}', [\App\Http\Controllers\Admin\ReportController::class, 'exportInactiveStudents'])->name('admin.reports.inactive-students.export');
        
        Route::get('/admin/reports/instructors', [\App\Http\Controllers\Admin\ReportController::class, 'instructors'])->name('admin.reports.instructors');
        Route::get('/admin/reports/instructors/export/{type}', [\App\Http\Controllers\Admin\ReportController::class, 'exportInstructors'])->name('admin.reports.instructors.export');
    });

    Route::middleware(['role:instructor'])->group(function () {
        Route::get('/instructor/classes', [\App\Http\Controllers\Instructor\ClassController::class, 'index'])->name('instructor.classes.index');
        Route::get('/instructor/classes/{assignment}', [\App\Http\Controllers\Instructor\ClassController::class, 'show'])->name('instructor.classes.show');
        Route::get('/instructor/classes/{assignment}/attendance-records', [\App\Http\Controllers\Instructor\AttendanceController::class, 'records'])->name('instructor.attendance.records');

        // Attendance session routes
        Route::post('/instructor/attendance/open', [\App\Http\Controllers\Instructor\AttendanceController::class, 'openSession'])->name('instructor.attendance.open');
        Route::get('/instructor/attendance/{session}', [\App\Http\Controllers\Instructor\AttendanceController::class, 'session'])->name('instructor.attendance.session');
        Route::post('/instructor/attendance/{session}/scan', [\App\Http\Controllers\Instructor\AttendanceController::class, 'scan'])->name('instructor.attendance.scan');
        Route::post('/instructor/attendance/{session}/manual', [\App\Http\Controllers\Instructor\AttendanceController::class, 'manualRecord'])->name('instructor.attendance.manual');
        Route::post('/instructor/attendance/{session}/mark-absent', [\App\Http\Controllers\Instructor\AttendanceController::class, 'markAbsent'])->name('instructor.attendance.mark-absent');
        Route::get('/instructor/dashboard', function () {
            $studentCount = \App\Models\Student::count();
            $instructorCount = \App\Models\Instructor::count();
            $subjectCount = \App\Models\Subject::count();
            $assignmentCount = \App\Models\InstructorAssignment::count();
            
            $instructor = \App\Models\Instructor::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();
            $schedules = collect();
            
            if ($instructor) {
                $assignmentIds = $instructor->assignments()->pluck('id');
                $schedules = \App\Models\Schedule::whereIn('instructor_assignment_id', $assignmentIds)
                    ->with('instructorAssignment.subject', 'instructorAssignment.course')
                    ->get();
            }
            
            $now = \Carbon\Carbon::now();
            $currentDayOfWeek = strtolower($now->format('l'));
            $currentTime = $now->format('H:i:s');

            $dayMap = [
                'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4,
                'friday' => 5, 'saturday' => 6, 'sunday' => 7
            ];
            
            if (isset($dayMap[$currentDayOfWeek])) {
                $currentDayIndex = $dayMap[$currentDayOfWeek];
                
                $schedules = $schedules->map(function ($schedule) use ($dayMap, $currentDayIndex, $currentTime) {
                    $scheduleDayStr = strtolower($schedule->day_of_week);
                    $scheduleDayIndex = $dayMap[$scheduleDayStr] ?? 1;
                    
                    $daysUntil = $scheduleDayIndex - $currentDayIndex;
                    
                    // If the schedule day is earlier in the week, it will be next week.
                    if ($daysUntil < 0) {
                        $daysUntil += 7;
                    }
                    
                    $schedule->days_until = $daysUntil;
                    return $schedule;
                })->sortBy([
                    ['days_until', 'asc'],
                    ['start_time', 'asc'],
                ])->values();
            }

            $nextSchedule = $schedules->first();
            $upcomingSchedules = $schedules->skip(1)->take(3);

            return view('instructor.dashboard', compact('studentCount', 'instructorCount', 'subjectCount', 'assignmentCount', 'nextSchedule', 'upcomingSchedules'));
        })->name('instructor.dashboard');
    });

    Route::middleware(['role:student'])->group(function () {
        Route::get('/student/dashboard', function () {
            return view('student.dashboard');
        })->name('student.dashboard');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
