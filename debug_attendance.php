<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$app->boot();

use App\Models\Instructor;
use App\Models\Schedule;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use Carbon\Carbon;

echo "=== ATTENDANCE DEBUG ===" . PHP_EOL;

$today = Carbon::today('Asia/Manila')->toDateString();
echo "Today (Asia/Manila): $today" . PHP_EOL;
echo "Server UTC time: " . Carbon::now()->toDateTimeString() . PHP_EOL;
echo PHP_EOL;

// Check ALL attendance sessions
$allSessions = AttendanceSession::orderBy('session_date', 'desc')->take(10)->get(['id','schedule_id','session_date','status']);
echo "=== ALL AttendanceSessions (latest 10) ===" . PHP_EOL;
foreach ($allSessions as $s) {
    echo "  id={$s->id}  schedule_id={$s->schedule_id}  session_date={$s->session_date}  status={$s->status}" . PHP_EOL;
}
echo PHP_EOL;

// Check ALL attendance records
$allRecords = AttendanceRecord::orderBy('id', 'desc')->take(20)->get(['id','attendance_session_id','student_id','status','created_at']);
echo "=== ALL AttendanceRecords (latest 20) ===" . PHP_EOL;
if ($allRecords->count() === 0) {
    echo "  (none)" . PHP_EOL;
} else {
    foreach ($allRecords as $r) {
        echo "  id={$r->id}  session={$r->attendance_session_id}  student={$r->student_id}  status={$r->status}  created_at={$r->created_at}" . PHP_EOL;
    }
}
echo PHP_EOL;

// Check instructor
$instructors = Instructor::all(['id','user_id']);
echo "=== Instructors ===" . PHP_EOL;
foreach ($instructors as $i) {
    echo "  id={$i->id}  user_id={$i->user_id}" . PHP_EOL;
}
echo PHP_EOL;

// Per instructor
foreach ($instructors as $instructor) {
    echo "--- Instructor #{$instructor->id} ---" . PHP_EOL;
    $assignmentIds = $instructor->assignments()->pluck('id');
    echo "  Assignment IDs: " . $assignmentIds->implode(',') . PHP_EOL;

    $scheduleIds = Schedule::whereIn('instructor_assignment_id', $assignmentIds)->pluck('id');
    echo "  Schedule IDs: " . $scheduleIds->implode(',') . PHP_EOL;

    // Sessions for today
    $todaySessionIds = AttendanceSession::whereIn('schedule_id', $scheduleIds)
        ->whereDate('session_date', $today)
        ->pluck('id');
    echo "  Today's Session IDs: " . ($todaySessionIds->count() ? $todaySessionIds->implode(',') : '(none)') . PHP_EOL;

    // All sessions for this instructor
    $instructorSessions = AttendanceSession::whereIn('schedule_id', $scheduleIds)
        ->orderBy('session_date', 'desc')
        ->get(['id','session_date','status']);
    echo "  ALL Sessions for instructor:" . PHP_EOL;
    foreach ($instructorSessions as $s) {
        echo "    id={$s->id}  date={$s->session_date}  status={$s->status}" . PHP_EOL;
    }

    // Records for today
    $todayRecords = AttendanceRecord::whereIn('attendance_session_id', $todaySessionIds)->get(['id','status']);
    echo "  Today Records: present={$todayRecords->where('status','present')->count()} late={$todayRecords->where('status','late')->count()} absent={$todayRecords->where('status','absent')->count()}" . PHP_EOL;
}
