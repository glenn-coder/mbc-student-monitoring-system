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
            
            $recentStudents = \App\Models\Student::with('course')
                ->latest()
                ->paginate(5, ['*'], 'students_page')
                ->fragment('recent-students-table');
                
            $recentAssignments = \App\Models\InstructorAssignment::with(['instructor', 'subject', 'course'])
                ->withCount('students')
                ->latest()
                ->paginate(5, ['*'], 'assignments_page')
                ->fragment('recent-assignments-table');
                
            return view('admin.dashboard', compact('studentCount', 'instructorCount', 'subjectCount', 'assignmentCount', 'recentStudents', 'recentAssignments'));
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
        Route::get('/instructor/reports', [\App\Http\Controllers\Instructor\ReportController::class, 'index'])->name('instructor.reports.index');
        Route::get('/instructor/reports/export/{type}', [\App\Http\Controllers\Instructor\ReportController::class, 'export'])->name('instructor.reports.export')->where('type', 'pdf|excel|csv');

        // Student Attendance Report
        Route::get('/instructor/reports/student-attendance', [\App\Http\Controllers\Instructor\ReportController::class, 'studentAttendance'])->name('instructor.reports.student-attendance');
        Route::get('/instructor/reports/student-attendance/export/{type}', [\App\Http\Controllers\Instructor\ReportController::class, 'studentAttendanceExport'])->name('instructor.reports.student-attendance.export')->where('type', 'pdf|excel|csv');

        Route::get('/instructor/classes', [\App\Http\Controllers\Instructor\ClassController::class, 'index'])->name('instructor.classes.index');
        Route::get('/instructor/classes/{assignment}', [\App\Http\Controllers\Instructor\ClassController::class, 'show'])->name('instructor.classes.show');
        Route::post('/instructor/classes/{assignment}/students', [\App\Http\Controllers\Instructor\ClassController::class, 'addStudent'])->name('instructor.classes.students.add');
        Route::get('/instructor/classes/{assignment}/attendance-records', [\App\Http\Controllers\Instructor\AttendanceController::class, 'records'])->name('instructor.attendance.records');

        // Attendance session routes
        Route::post('/instructor/attendance/open', [\App\Http\Controllers\Instructor\AttendanceController::class, 'openSession'])->name('instructor.attendance.open');
        Route::get('/instructor/attendance/{session}', [\App\Http\Controllers\Instructor\AttendanceController::class, 'session'])->name('instructor.attendance.session');
        Route::post('/instructor/attendance/{session}/scan', [\App\Http\Controllers\Instructor\AttendanceController::class, 'scan'])->name('instructor.attendance.scan');
        Route::post('/instructor/attendance/{session}/manual', [\App\Http\Controllers\Instructor\AttendanceController::class, 'manualRecord'])->name('instructor.attendance.manual');
        Route::post('/instructor/attendance/{session}/mark-absent', [\App\Http\Controllers\Instructor\AttendanceController::class, 'markAbsent'])->name('instructor.attendance.mark-absent');
        Route::delete('/instructor/attendance/{session}', [\App\Http\Controllers\Instructor\AttendanceController::class, 'deleteSession'])->name('instructor.attendance.delete');
        Route::delete('/instructor/attendance/record/{record}', [\App\Http\Controllers\Instructor\AttendanceController::class, 'deleteRecord'])->name('instructor.attendance.record.delete');
        // Live stats API for dashboard card polling (supports date_from / date_to query params)
        Route::get('/instructor/dashboard/stats', function (\Illuminate\Http\Request $request) {
            $instructor = \App\Models\Instructor::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();

            $todayPresent = 0;
            $todayLate    = 0;
            $todayAbsent  = 0;

            if ($instructor) {
                $fallback    = \Carbon\Carbon::today('Asia/Manila')->toDateString();
                $dateFrom    = $request->query('date_from', $fallback);
                $dateTo      = $request->query('date_to',   $fallback);

                // Sanitise: ensure date_to is never before date_from
                if ($dateTo < $dateFrom) {
                    $dateTo = $dateFrom;
                }

                $assignmentIds  = $instructor->assignments()->pluck('id');
                $scheduleIds    = \App\Models\Schedule::whereIn('instructor_assignment_id', $assignmentIds)->pluck('id');

                $sessionIds = \App\Models\AttendanceSession::whereIn('schedule_id', $scheduleIds)
                    ->whereBetween('session_date', [$dateFrom, $dateTo])
                    ->pluck('id');

                $records = \App\Models\AttendanceRecord::whereIn('attendance_session_id', $sessionIds)->get();

                $todayPresent = $records->where('status', 'present')->count();
                $todayLate    = $records->where('status', 'late')->count();
                $todayAbsent  = $records->where('status', 'absent')->count();
            }

            return response()->json([
                'todayPresent' => $todayPresent,
                'todayLate'    => $todayLate,
                'todayAbsent'  => $todayAbsent,
            ]);
        })->name('instructor.dashboard.stats');

        Route::get('/instructor/dashboard', function () {
            $instructor = \App\Models\Instructor::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();
            
            $studentCount = 0;
            $classCount = 0;
            $todayPresent = 0;
            $todayLate = 0;
            $todayAbsent = 0;
            $chartData = [
                'present' => [0,0,0,0,0,0],
                'late' => [0,0,0,0,0,0],
                'absent' => [0,0,0,0,0,0]
            ];
            $schedules = collect();
            $recentRecords = collect();
            $uniqueSubjects = collect();
            
            if ($instructor) {
                $subjectIds = $instructor->assignments()->pluck('subject_id')->unique();
                $uniqueSubjects = \App\Models\Subject::whereIn('id', $subjectIds)->get();

                $assignmentsQuery = $instructor->assignments();
                if (request()->filled('subject_id')) {
                    $assignmentsQuery->where('subject_id', request('subject_id'));
                }
                
                $assignmentIds = $assignmentsQuery->pluck('id');
                $classCount = $assignmentIds->count();
                
                $studentCount = \Illuminate\Support\Facades\DB::table('assignment_student')
                    ->whereIn('instructor_assignment_id', $assignmentIds)
                    ->distinct('student_id')
                    ->count('student_id');
                    
                $scheduleIds = \App\Models\Schedule::whereIn('instructor_assignment_id', $assignmentIds)->pluck('id');
                $sessionIds = \App\Models\AttendanceSession::whereIn('schedule_id', $scheduleIds)->pluck('id');
                
                // Top cards logic moved down
                
                $reqDateFrom = request('date_from');
                $reqDateTo = request('date_to');
                
                // If only one date is provided, use it for both to create a single-day range
                if (request()->filled('date_from') && !request()->filled('date_to')) {
                    $reqDateTo = $reqDateFrom;
                } elseif (!request()->filled('date_from') && request()->filled('date_to')) {
                    $reqDateFrom = $reqDateTo;
                }
                
                $startDate = $reqDateFrom 
                    ? \Carbon\Carbon::parse($reqDateFrom)->startOfDay() 
                    : \Carbon\Carbon::now()->startOfWeek();
                    
                $endDate = $reqDateTo 
                    ? \Carbon\Carbon::parse($reqDateTo)->endOfDay() 
                    : \Carbon\Carbon::now()->endOfWeek();
                
                $weekRecords = \Illuminate\Support\Facades\DB::table('attendance_records')
                    ->join('attendance_sessions', 'attendance_records.attendance_session_id', '=', 'attendance_sessions.id')
                    ->whereIn('attendance_sessions.schedule_id', $scheduleIds)
                    ->whereBetween('attendance_sessions.session_date', [$startDate, $endDate])
                    ->select('attendance_records.status', 'attendance_sessions.session_date')
                    ->get();
                    
                foreach ($weekRecords as $r) {
                    $date = \Carbon\Carbon::parse($r->session_date);
                    $day = $date->dayOfWeekIso; // 1 = Mon, 6 = Sat
                    if ($day >= 1 && $day <= 6) {
                        $status = $r->status;
                        if (isset($chartData[$status])) {
                            $chartData[$status][$day - 1]++;
                        }
                    }
                }

                $schedules = \App\Models\Schedule::whereIn('instructor_assignment_id', $assignmentIds)
                    ->with('instructorAssignment.subject', 'instructorAssignment.course')
                    ->get();
                    
                $latestSessionQuery = \App\Models\AttendanceSession::whereIn('schedule_id', $scheduleIds);
                
                if (request()->filled('date_from') || request()->filled('date_to')) {
                    $latestSessionQuery->whereBetween('session_date', [$startDate, $endDate]);
                }
                
                $latestSession = $latestSessionQuery
                    ->with('schedule')
                    ->orderBy('session_date', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->first();
                    
                $latestSessionId = $latestSession ? $latestSession->id : null;
                $latestSessionAssignmentId = $latestSession ? $latestSession->schedule->instructor_assignment_id : null;
                
                $todayRecords = \App\Models\AttendanceRecord::where('attendance_session_id', $latestSessionId)->get();
                $todayPresent = $todayRecords->where('status', 'present')->count();
                $todayLate = $todayRecords->where('status', 'late')->count();
                $todayAbsent = $todayRecords->where('status', 'absent')->count();

                $recordsQuery = \App\Models\AttendanceRecord::with(['student', 'session.schedule.instructorAssignment.subject', 'session.schedule.instructorAssignment.course']);
                    
                if (request()->filled('date_from') || request()->filled('date_to')) {
                    $recordsQuery->whereHas('session', function($q) use ($scheduleIds, $startDate, $endDate) {
                        $q->whereIn('schedule_id', $scheduleIds)
                          ->whereBetween('session_date', [$startDate, $endDate]);
                    });
                } else {
                    $recordsQuery->where('attendance_session_id', $latestSessionId);
                }
                    
                $perPage = (int) request('per_page', 5);
                $perPage = in_array($perPage, [5, 10, 25, 50, 100]) ? $perPage : 5;

                $recentRecords = $recordsQuery
                    ->join('students', 'attendance_records.student_id', '=', 'students.id')
                    ->orderBy('students.last_name', 'asc')
                    ->orderBy('students.first_name', 'asc')
                    ->select('attendance_records.*') // ensure we don't accidentally select student columns
                    ->paginate($perPage)
                    ->withQueryString();
            } else {
                $perPage = 5;
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

            return view('instructor.dashboard', compact(
                'studentCount', 'classCount', 'todayPresent', 'todayLate', 'todayAbsent',
                'chartData', 'nextSchedule', 'upcomingSchedules', 'recentRecords', 'uniqueSubjects', 'perPage', 'latestSessionId', 'latestSessionAssignmentId'
            ));
        })->name('instructor.dashboard');
    });

    Route::middleware(['role:student'])->group(function () {
        Route::get('/student/dashboard', function (\Illuminate\Http\Request $request) {
            $user    = \Illuminate\Support\Facades\Auth::user();
            $student = \App\Models\Student::where('user_id', $user->id)->first();

            $present = 0;
            $late    = 0;
            $absent  = 0;
            $recentRecords = collect();
            $uniqueSubjects = collect();

            if ($student) {
                // Get unique subjects for the student for the filter dropdown
                $assignmentIds = \Illuminate\Support\Facades\DB::table('assignment_student')
                    ->where('student_id', $student->id)
                    ->pluck('instructor_assignment_id');
                
                $subjectIds = \App\Models\InstructorAssignment::whereIn('id', $assignmentIds)->pluck('subject_id')->unique();
                $uniqueSubjects = \App\Models\Subject::whereIn('id', $subjectIds)->get();

                // Only count records belonging to CLOSED sessions
                $recordsQuery = \App\Models\AttendanceRecord::where('student_id', $student->id)
                    ->whereHas('session', function($q) use ($request) {
                        $q->where('status', 'closed');
                        
                        if ($request->filled('date_from')) {
                            $q->whereDate('session_date', '>=', $request->date_from);
                        }
                        if ($request->filled('date_to')) {
                            $q->whereDate('session_date', '<=', $request->date_to);
                        }
                    })
                    ->with([
                        'session.schedule.instructorAssignment.subject',
                        'session.schedule.instructorAssignment.instructor'
                    ]);

                if ($request->filled('subject_id')) {
                    $recordsQuery->whereHas('session.schedule.instructorAssignment', function($q) use ($request) {
                        $q->where('subject_id', $request->subject_id);
                    });
                }

                $countsQuery = clone $recordsQuery;
                $records = $countsQuery->get();

                $present = $records->where('status', 'present')->count();
                $late    = $records->where('status', 'late')->count();
                $absent  = $records->where('status', 'absent')->count();
                $total   = $records->count();

                $attendanceRate = $total > 0
                    ? round((($present + $late) / $total) * 100, 1)
                    : 0;

                $recentRecords = $recordsQuery
                    ->orderByDesc('created_at')
                    ->paginate(5)
                    ->withQueryString();
            } else {
                $attendanceRate = 0;
            }

            return view('student.dashboard', compact(
                'present', 'late', 'absent', 'attendanceRate', 'recentRecords', 'uniqueSubjects'
            ));
        })->name('student.dashboard');

        Route::get('/student/attendance', function (\Illuminate\Http\Request $request) {
            $user    = \Illuminate\Support\Facades\Auth::user();
            $student = \App\Models\Student::where('user_id', $user->id)->first();

            $records = collect();
            $uniqueSubjects = collect();
            if ($student) {
                $assignmentIds = \Illuminate\Support\Facades\DB::table('assignment_student')
                    ->where('student_id', $student->id)
                    ->pluck('instructor_assignment_id');
                
                $subjectIds = \App\Models\InstructorAssignment::whereIn('id', $assignmentIds)->pluck('subject_id')->unique();
                $uniqueSubjects = \App\Models\Subject::whereIn('id', $subjectIds)->get();

                $perPage = request('per_page', 5);
                $recordsQuery = \App\Models\AttendanceRecord::where('student_id', $student->id)
                    ->whereHas('session', function($q) use ($request) {
                        $q->where('status', 'closed');
                        
                        if ($request->filled('date_from')) {
                            $q->whereDate('session_date', '>=', $request->date_from);
                        }
                        if ($request->filled('date_to')) {
                            $q->whereDate('session_date', '<=', $request->date_to);
                        }
                    })
                    ->with([
                        'session.schedule.instructorAssignment.subject',
                        'session.schedule.instructorAssignment.instructor'
                    ]);

                if ($request->filled('subject_id')) {
                    $recordsQuery->whereHas('session.schedule.instructorAssignment', function($q) use ($request) {
                        $q->where('subject_id', $request->subject_id);
                    });
                }

                $records = $recordsQuery
                    ->orderByDesc('created_at')
                    ->paginate($perPage)
                    ->withQueryString();
            }

            return view('student.attendance', compact('records', 'uniqueSubjects'));
        })->name('student.attendance');

        Route::get('/student/schedule', function () {
            $user = \Illuminate\Support\Facades\Auth::user();
            $student = \App\Models\Student::where('user_id', $user->id)->first();
            
            $schedules = collect();
            if ($student) {
                $assignmentIds = \Illuminate\Support\Facades\DB::table('assignment_student')
                    ->where('student_id', $student->id)
                    ->pluck('instructor_assignment_id');
                    
                $schedules = \App\Models\Schedule::whereIn('instructor_assignment_id', $assignmentIds)
                    ->with(['instructorAssignment.subject', 'instructorAssignment.instructor', 'instructorAssignment.course'])
                    ->get()
                    ->sortBy(function ($schedule) {
                        $days = ['Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7];
                        return $days[$schedule->day_of_week] ?? 8;
                    });
            }
            
            return view('student.schedule', compact('schedules'));
        })->name('student.schedule');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
