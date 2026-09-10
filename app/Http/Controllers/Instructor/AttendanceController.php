<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\InstructorAssignment;
use App\Models\Schedule;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Instructor;

class AttendanceController extends Controller
{
    public function __construct(protected AttendanceService $attendanceService)
    {
    }

    /**
     * Open (or retrieve) today's attendance session for a given schedule.
     * POST /instructor/attendance/open
     */
    public function openSession(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
        ]);

        $schedule = Schedule::with('instructorAssignment.instructor')->findOrFail($request->schedule_id);

        // Ensure the instructor owns this schedule
        $this->authorizeSchedule($schedule);

        $session = $this->attendanceService->openSession($schedule);

        return response()->json([
            'session' => $this->formatSession($session),
        ]);
    }

    /**
     * Process a student ID scan within an attendance session.
     * POST /instructor/attendance/{session}/scan
     */
    public function scan(Request $request, AttendanceSession $session)
    {
        $this->authorizeSession($session);

        $request->validate([
            'student_number' => 'required|string',
        ]);

        $result = $this->attendanceService->recordScan($session, trim($request->student_number));

        return response()->json([
            'status'  => $result['status'],
            'message' => $result['message'],
            'record'  => $result['record'] ? $this->formatRecord($result['record']) : null,
            'summary' => $this->getSessionSummary($session),
        ]);
    }

    /**
     * Get the current session data (used for polling/refresh).
     * GET /instructor/attendance/{session}
     */
    public function session(AttendanceSession $session)
    {
        $this->authorizeSession($session);

        $session->load(['records.student.course', 'schedule.instructorAssignment.students.course']);

        return response()->json([
            'session' => $this->formatSession($session),
            'records' => $session->records->map(fn ($r) => $this->formatRecord($r)),
            'summary' => $this->getSessionSummary($session),
        ]);
    }

    /**
     * Manually trigger absent marking for a specific session (fallback for Windows dev).
     * POST /instructor/attendance/{session}/mark-absent
     */
    public function markAbsent(AttendanceSession $session)
    {
        $this->authorizeSession($session);

        // Only allow if session has ended
        if (!$session->hasEnded()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'The session has not ended yet. Cannot mark students absent before the class ends.',
            ], 422);
        }

        $count = $this->attendanceService->markAbsentForEndedSessions();

        // Re-load the updated session
        $session->refresh()->load(['records.student.course']);

        return response()->json([
            'status'  => 'ok',
            'message' => "Marked {$count} student(s) as Absent.",
            'records' => $session->records->map(fn ($r) => $this->formatRecord($r)),
            'summary' => $this->getSessionSummary($session),
        ]);
    }

    /**
     * Return all historical attendance records for an assignment (Attendance Record tab).
     * GET /instructor/classes/{assignment}/attendance-records
     */
    public function records(InstructorAssignment $assignment)
    {
        $instructor = Instructor::where('user_id', Auth::id())->firstOrFail();

        if ($assignment->instructor_id !== $instructor->id) {
            abort(403);
        }

        $assignment->load(['subject', 'course', 'schedules.attendanceSessions.records.student.course']);

        $sessions = $assignment->schedules->flatMap(function ($schedule) {
            return $schedule->attendanceSessions->map(function ($session) use ($schedule) {
                return [
                    'session_id'   => $session->id,
                    'session_date' => $session->session_date->format('M d, Y'),
                    'day_of_week'  => $schedule->day_of_week,
                    'start_time'   => \Carbon\Carbon::parse($schedule->start_time)->format('g:i A'),
                    'end_time'     => \Carbon\Carbon::parse($schedule->end_time)->format('g:i A'),
                    'status'       => $session->status,
                    'records'      => $session->records->map(fn ($r) => $this->formatRecord($r)),
                ];
            });
        })->sortByDesc('session_date')->values();

        return response()->json(['sessions' => $sessions]);
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    private function authorizeSchedule(Schedule $schedule): void
    {
        $instructor = Instructor::where('user_id', Auth::id())->first();

        if (!$instructor || $schedule->instructorAssignment->instructor_id !== $instructor->id) {
            abort(403);
        }
    }

    private function authorizeSession(AttendanceSession $session): void
    {
        $session->loadMissing('schedule.instructorAssignment');
        $this->authorizeSchedule($session->schedule);
    }

    private function formatSession(AttendanceSession $session): array
    {
        $schedule = $session->schedule;
        return [
            'id'                  => $session->id,
            'session_date'        => $session->session_date->format('M d, Y'),
            'status'              => $session->status,
            'opened_at'           => $session->opened_at?->format('g:i A'),
            'closed_at'           => $session->closed_at?->format('g:i A'),
            'start_time'          => \Carbon\Carbon::parse($schedule->start_time)->format('g:i A'),
            'end_time'            => \Carbon\Carbon::parse($schedule->end_time)->format('g:i A'),
            'grace_period_minutes'=> $schedule->grace_period_minutes,
            'is_open'             => $session->isOpen(),
            'has_ended'           => $session->hasEnded(),
        ];
    }

    private function formatRecord(AttendanceRecord $record): array
    {
        $student = $record->student;
        return [
            'id'             => $record->id,
            'student_id'     => $student->id,
            'student_number' => $student->student_number,
            'full_name'      => trim("{$student->first_name} " . ($student->middle_name ? $student->middle_name[0].'. ' : '') . $student->last_name),
            'course'         => $student->course?->code ?? 'N/A',
            'status'         => $record->status,
            'scanned_at'     => $record->scanned_at?->format('g:i A'),
        ];
    }

    private function getSessionSummary(AttendanceSession $session): array
    {
        $records = AttendanceRecord::where('attendance_session_id', $session->id)->get();

        return [
            'total'   => $records->count(),
            'present' => $records->where('status', 'present')->count(),
            'late'    => $records->where('status', 'late')->count(),
            'absent'  => $records->where('status', 'absent')->count(),
        ];
    }
}
