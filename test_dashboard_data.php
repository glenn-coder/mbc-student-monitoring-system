<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Instructor;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$instructor = Instructor::first();
if (!$instructor) die('No instructor');

$assignmentIds = $instructor->assignments()->pluck('id');
$scheduleIds = Schedule::whereIn('instructor_assignment_id', $assignmentIds)->pluck('id');
$sessionIds = AttendanceSession::whereIn('schedule_id', $scheduleIds)->pluck('id');

$studentCount = DB::table('assignment_student')->whereIn('instructor_assignment_id', $assignmentIds)->distinct('student_id')->count('student_id');
$classCount = $assignmentIds->count();

$today = Carbon::today();
$todayRecords = AttendanceRecord::whereIn('attendance_session_id', $sessionIds)
    ->whereDate('created_at', $today)
    ->get();

$todayPresent = $todayRecords->where('status', 'present')->count();
$todayLate = $todayRecords->where('status', 'late')->count();
$todayAbsent = $todayRecords->where('status', 'absent')->count();

$startOfWeek = Carbon::now()->startOfWeek();
$endOfWeek = Carbon::now()->endOfWeek();

$weekRecords = DB::table('attendance_records')
    ->join('attendance_sessions', 'attendance_records.attendance_session_id', '=', 'attendance_sessions.id')
    ->whereIn('attendance_sessions.schedule_id', $scheduleIds)
    ->whereBetween('attendance_sessions.session_date', [$startOfWeek, $endOfWeek])
    ->select('attendance_records.status', 'attendance_sessions.session_date')
    ->get();

$chartData = [
    'present' => [0,0,0,0,0],
    'late' => [0,0,0,0,0],
    'absent' => [0,0,0,0,0]
];

foreach ($weekRecords as $r) {
    $date = Carbon::parse($r->session_date);
    $day = $date->dayOfWeekIso; // 1 = Mon, 5 = Fri
    if ($day >= 1 && $day <= 5) {
        $status = $r->status;
        if (isset($chartData[$status])) {
            $chartData[$status][$day - 1]++;
        }
    }
}

echo json_encode([
    'students' => $studentCount,
    'classes' => $classCount,
    'today' => ['present' => $todayPresent, 'late' => $todayLate, 'absent' => $todayAbsent],
    'chart' => $chartData
], JSON_PRETTY_PRINT);
