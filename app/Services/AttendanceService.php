<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Schedule;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    /**
     * Determine the attendance status for a given scan time relative to a schedule.
     *
     * Boundaries:
     *   scan_time <  start_time                          → null  (not started yet)
     *   start_time <= scan_time <= start_time + grace    → present
     *   start_time + grace < scan_time < end_time        → late
     *   scan_time >= end_time                            → null  (session ended)
     *
     * The grace boundary is INCLUSIVE for "present" (e.g. 9:15 AM with 15-min grace
     * from a 9:00 AM start is still "Present"; 9:15:01 AM is "Late").
     *
     * Returns 'present', 'late', or null when outside the attendance window.
     */
    public function resolveStatus(Carbon $scanTime, Schedule $schedule, string $date): ?string
    {
        $tz       = 'Asia/Manila';
        $start    = Carbon::parse("{$date} {$schedule->start_time}", $tz);
        $end      = Carbon::parse("{$date} {$schedule->end_time}", $tz);
        $graceEnd = $start->copy()->addMinutes($schedule->grace_period_minutes);

        if ($scanTime->lt($start)) {
            return null; // Too early
        }

        if ($scanTime->gte($end)) {
            return null; // Session ended
        }

        // Grace boundary is inclusive: scanTime <= graceEnd → present
        if ($scanTime->lte($graceEnd)) {
            return AttendanceRecord::STATUS_PRESENT;
        }

        return AttendanceRecord::STATUS_LATE;
    }

    /**
     * Open (or retrieve) today's attendance session for a given schedule.
     * Creates a new session if one does not yet exist for today.
     */
    public function openSession(Schedule $schedule): AttendanceSession
    {
        $today = Carbon::now('Asia/Manila')->toDateString();

        $session = AttendanceSession::firstOrCreate(
            [
                'schedule_id'  => $schedule->id,
                'session_date' => $today,
            ],
            [
                'opened_at' => Carbon::now('Asia/Manila'),
                'status'    => 'open',
            ]
        );

        // If the session exists but was closed, reopen it (instructor action)
        if ($session->status === 'closed') {
            $session->update(['status' => 'open', 'closed_at' => null]);
        }

        return $session->load('schedule');
    }

    /**
     * Record a student scan in a given attendance session.
     *
     * @return array{record: AttendanceRecord|null, status: string, message: string}
     */
    public function recordScan(AttendanceSession $session, string $studentNumber): array
    {
        // ── Guard: closed session ──────────────────────────────────────────────
        if ($session->status === 'closed') {
            return [
                'record'  => null,
                'status'  => 'window_closed',
                'message' => 'Attendance session has ended.',
            ];
        }

        $schedule = $session->schedule;
        $date     = $session->session_date->format('Y-m-d');
        $now      = Carbon::now('Asia/Manila');

        // ── Time window ────────────────────────────────────────────────────────
        $status = $this->resolveStatus($now, $schedule, $date);

        if ($status === null) {
            $start = Carbon::parse("{$date} {$schedule->start_time}", 'Asia/Manila');
            return [
                'record'  => null,
                'status'  => 'window_closed',
                'message' => $now->lt($start)
                    ? 'Attendance is not yet available. The class has not started.'
                    : 'Attendance session has ended.',
            ];
        }

        // ── Find student ───────────────────────────────────────────────────────
        $student = Student::where('student_number', $studentNumber)->first();

        if (!$student) {
            return [
                'record'  => null,
                'status'  => 'not_found',
                'message' => 'Student not found.',
            ];
        }

        // ── Enrollment check ───────────────────────────────────────────────────
        $enrollmentCheck = $this->checkEnrollment($schedule, $student->id);
        if ($enrollmentCheck !== null) {
            return $enrollmentCheck;
        }

        // ── Duplicate check ────────────────────────────────────────────────────
        $existing = $this->findExistingRecord($session->id, $student->id);
        if ($existing) {
            return [
                'record'  => $existing->load('student.course'),
                'status'  => 'duplicate',
                'message' => 'Attendance already recorded for this student.',
            ];
        }

        // ── Create record ──────────────────────────────────────────────────────
        $record = AttendanceRecord::create([
            'attendance_session_id' => $session->id,
            'student_id'            => $student->id,
            'status'                => $status,
            'scanned_at'            => $now,
            'attendance_method'     => AttendanceRecord::METHOD_SCAN,
        ]);

        return [
            'record'  => $record->load('student.course'),
            'status'  => 'recorded',
            'message' => "Recorded as {$status}.",
        ];
    }

    /**
     * Record manual attendance in a given session (instructor selects student by ID).
     *
     * Uses the same validation rules, time window, and status calculation as
     * recordScan(). The resulting record is tagged attendance_method = METHOD_MANUAL.
     *
     * @return array{record: AttendanceRecord|null, status: string, message: string}
     */
    public function recordManual(AttendanceSession $session, int $studentId): array
    {
        // ── Guard: closed session ──────────────────────────────────────────────
        if ($session->status === 'closed') {
            return [
                'record'  => null,
                'status'  => 'window_closed',
                'message' => 'Attendance session has ended.',
            ];
        }

        $schedule = $session->schedule;
        $date     = $session->session_date->format('Y-m-d');
        $now      = Carbon::now('Asia/Manila');

        // ── Time window ────────────────────────────────────────────────────────
        $status = $this->resolveStatus($now, $schedule, $date);

        if ($status === null) {
            $start = Carbon::parse("{$date} {$schedule->start_time}", 'Asia/Manila');
            return [
                'record'  => null,
                'status'  => 'window_closed',
                'message' => $now->lt($start)
                    ? 'Attendance is not yet available. The class has not started.'
                    : 'Attendance session has ended.',
            ];
        }

        // ── Find student ───────────────────────────────────────────────────────
        $student = Student::find($studentId);

        if (!$student) {
            return [
                'record'  => null,
                'status'  => 'not_found',
                'message' => 'Student not found.',
            ];
        }

        // ── Enrollment check ───────────────────────────────────────────────────
        $enrollmentCheck = $this->checkEnrollment($schedule, $student->id);
        if ($enrollmentCheck !== null) {
            return $enrollmentCheck;
        }

        // ── Duplicate check ────────────────────────────────────────────────────
        $existing = $this->findExistingRecord($session->id, $student->id);
        if ($existing) {
            return [
                'record'  => $existing->load('student.course'),
                'status'  => 'duplicate',
                'message' => 'Attendance already recorded for this student.',
            ];
        }

        // ── Create record ──────────────────────────────────────────────────────
        $record = AttendanceRecord::create([
            'attendance_session_id' => $session->id,
            'student_id'            => $student->id,
            'status'                => $status,
            'scanned_at'            => $now,
            'attendance_method'     => AttendanceRecord::METHOD_MANUAL,
        ]);

        return [
            'record'  => $record->load('student.course'),
            'status'  => 'recorded',
            'message' => "Recorded as {$status}.",
        ];
    }

    /**
     * Mark all enrolled students without an attendance record as Absent
     * for all sessions whose schedule's end_time has passed today.
     *
     * Absent records are tagged attendance_method = METHOD_SYSTEM.
     * Never overwrites existing Present or Late records.
     *
     * Returns the total number of Absent records created.
     */
    public function markAbsentForEndedSessions(): int
    {
        $tz    = 'Asia/Manila';
        $now   = Carbon::now($tz);
        $today = $now->toDateString();
        $count = 0;

        $endedSessions = AttendanceSession::with(['schedule.instructorAssignment.students'])
            ->where('session_date', $today)
            ->where('status', 'open')
            ->get()
            ->filter(function (AttendanceSession $session) use ($now, $today, $tz) {
                $end = Carbon::parse("{$today} {$session->schedule->end_time}", $tz);
                return $now->greaterThanOrEqualTo($end);
            });

        foreach ($endedSessions as $session) {
            $assignment = $session->schedule->instructorAssignment;

            if (!$assignment) {
                continue;
            }

            $enrolledStudentIds = $assignment->students->pluck('id');
            $recordedStudentIds = AttendanceRecord::where('attendance_session_id', $session->id)
                ->pluck('student_id');

            $absentStudentIds = $enrolledStudentIds->diff($recordedStudentIds);

            foreach ($absentStudentIds as $studentId) {
                AttendanceRecord::create([
                    'attendance_session_id' => $session->id,
                    'student_id'            => $studentId,
                    'status'                => AttendanceRecord::STATUS_ABSENT,
                    'scanned_at'            => null,
                    'attendance_method'     => AttendanceRecord::METHOD_SYSTEM,
                ]);
                $count++;
            }

            $session->update([
                'status'    => 'closed',
                'closed_at' => $now,
            ]);
        }

        return $count;
    }

    // ─── Private helpers ───────────────────────────────────────────────────────

    /**
     * Verify that a student (by ID) is enrolled in the class attached to the schedule.
     * Returns an error array if not enrolled, or null if the check passes.
     *
     * @return array|null
     */
    private function checkEnrollment(Schedule $schedule, int $studentId): ?array
    {
        $assignment = $schedule->instructorAssignment;

        if (!$assignment) {
            return [
                'record'  => null,
                'status'  => 'not_enrolled',
                'message' => 'Student is not enrolled in this class.',
            ];
        }

        $isEnrolled = DB::table('assignment_student')
            ->where('instructor_assignment_id', $assignment->id)
            ->where('student_id', $studentId)
            ->exists();

        if (!$isEnrolled) {
            return [
                'record'  => null,
                'status'  => 'not_enrolled',
                'message' => 'Student is not enrolled in this class.',
            ];
        }

        return null; // Enrolled — no error
    }

    /**
     * Find an existing attendance record for a session + student pair.
     */
    private function findExistingRecord(int $sessionId, int $studentId): ?AttendanceRecord
    {
        return AttendanceRecord::where('attendance_session_id', $sessionId)
            ->where('student_id', $studentId)
            ->first();
    }
}
