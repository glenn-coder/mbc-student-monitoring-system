<?php

namespace App\Console\Commands;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Instructor;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DebugAttendance extends Command
{
    protected $signature = 'debug:attendance';
    protected $description = 'Debug attendance stats';

    public function handle()
    {
        $today = Carbon::today('Asia/Manila')->toDateString();
        $this->info("Today (Asia/Manila): $today");
        $this->info("Server UTC time: " . Carbon::now()->toDateTimeString());
        $this->newLine();

        // All sessions
        $allSessions = AttendanceSession::orderBy('session_date', 'desc')->take(10)->get();
        $this->info("=== ALL AttendanceSessions (latest 10) ===");
        if ($allSessions->isEmpty()) {
            $this->warn("  (none)");
        }
        foreach ($allSessions as $s) {
            $this->line("  id={$s->id}  schedule_id={$s->schedule_id}  session_date={$s->session_date}  status={$s->status}");
        }
        $this->newLine();

        // All records
        $allRecords = AttendanceRecord::orderBy('id', 'desc')->take(20)->get();
        $this->info("=== ALL AttendanceRecords (latest 20) ===");
        if ($allRecords->isEmpty()) {
            $this->warn("  (none)");
        }
        foreach ($allRecords as $r) {
            $this->line("  id={$r->id}  session={$r->attendance_session_id}  student={$r->student_id}  status={$r->status}  created_at={$r->created_at}");
        }
        $this->newLine();

        // Per instructor
        $instructors = Instructor::all();
        $this->info("=== Instructors ===");
        foreach ($instructors as $instructor) {
            $this->line("  id={$instructor->id}  user_id={$instructor->user_id}");
        }
        $this->newLine();

        foreach ($instructors as $instructor) {
            $this->info("--- Instructor #{$instructor->id} ---");
            $assignmentIds = $instructor->assignments()->pluck('id');
            $this->line("  Assignment IDs: " . $assignmentIds->implode(','));

            $scheduleIds = Schedule::whereIn('instructor_assignment_id', $assignmentIds)->pluck('id');
            $this->line("  Schedule IDs: " . $scheduleIds->implode(','));

            $instructorSessions = AttendanceSession::whereIn('schedule_id', $scheduleIds)
                ->orderBy('session_date', 'desc')->get();
            $this->line("  ALL Sessions:");
            foreach ($instructorSessions as $s) {
                $this->line("    id={$s->id}  date={$s->session_date}  status={$s->status}");
            }

            $todaySessionIds = AttendanceSession::whereIn('schedule_id', $scheduleIds)
                ->whereDate('session_date', $today)->pluck('id');
            $this->line("  Today's Session IDs: " . ($todaySessionIds->count() ? $todaySessionIds->implode(',') : '(none)'));

            $todayRecords = AttendanceRecord::whereIn('attendance_session_id', $todaySessionIds)->get();
            $this->line("  Today Records: present={$todayRecords->where('status','present')->count()} late={$todayRecords->where('status','late')->count()} absent={$todayRecords->where('status','absent')->count()}");
        }

        // Raw SQL check
        $this->newLine();
        $this->info("=== Raw SQL check ===");
        $raw = DB::select("SELECT ar.id, ar.status, ar.attendance_session_id, DATE(ar.created_at) as created_date, s.session_date 
            FROM attendance_records ar 
            JOIN attendance_sessions s ON s.id = ar.attendance_session_id 
            ORDER BY ar.id DESC LIMIT 20");
        if (empty($raw)) {
            $this->warn("  No records found in attendance_records at all.");
        }
        foreach ($raw as $r) {
            $this->line("  ar.id={$r->id}  status={$r->status}  session_date={$r->session_date}  created_date={$r->created_date}");
        }
    }
}
