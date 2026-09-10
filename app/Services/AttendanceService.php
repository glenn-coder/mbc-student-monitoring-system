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
     * Rules:
     *   start_time <= scan < start_time + grace_period_minutes  → present
     *   start_time + grace_period_minutes <= scan < end_time     → late
     *   scan >= end_time                                          → session ended (null)
     *   scan < start_time                                         → not yet started (null)
     *
     * Returns 'present', 'late', or null if outside the valid window.
     */
    public function resolveStatus(Carbon $scanTime, Schedule $schedule, string $date): ?string
    {
        $tz = 'Asia/Manila';
        $start    = Carbon::parse("{$date} {$schedule->start_time}", $tz);
        $end      = Carbon::parse("{$date} {$schedule->end_time}", $tz);
        $graceEnd = $start->copy()->addMinutes($schedule->grace_period_minutes);

        if ($scanTime->lt($start)) {
            return null; // Too early
        }

        if ($scanTime->gte($end)) {
            return null; // Session ended
        }

        if ($scanTime->lt($graceEnd)) {
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
     * Returns an array with:
     *   'record'  => AttendanceRecord|null
     *   'status'  => 'recorded' | 'duplicate' | 'not_found' | 'window_closed'
     *   'message' => human-readable message
     */
    public function recordScan(AttendanceSession $session, string $studentNumber): array
    {
        $schedule = $session->schedule;
        $date     = $session->session_date->format('Y-m-d');
        $now      = Carbon::now('Asia/Manila');

        // Determine status based on current time
        $status = $this->resolveStatus($now, $schedule, $date);

        if ($status === null) {
            $tz    = 'Asia/Manila';
            $start = Carbon::parse("{$date} {$schedule->start_time}", $tz);
            $end   = Carbon::parse("{$date} {$schedule->end_time}", $tz);

            if ($now->lt($start)) {
                return [
                    'record'  => null,
                    'status'  => 'window_closed',
                    'message' => 'Attendance has not started yet.',
                ];
            }

            return [
                'record'  => null,
                'status'  => 'window_closed',
                'message' => 'The class session has already ended. No new attendance records can be created.',
            ];
        }

        // Find the student by student_number
        $student = Student::where('student_number', $studentNumber)->first();

        if (!$student) {
            return [
                'record'  => null,
                'status'  => 'not_found',
                'message' => "Student with ID \"{$studentNumber}\" not found.",
            ];
        }

        // Check for an existing record (prevent duplicates)
        $existing = AttendanceRecord::where('attendance_session_id', $session->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existing) {
            return [
                'record'  => $existing->load('student.course'),
                'status'  => 'duplicate',
                'message' => "Student {$student->first_name} {$student->last_name} is already recorded as {$existing->status}.",
            ];
        }

        // Create the attendance record
        $record = AttendanceRecord::create([
            'attendance_session_id' => $session->id,
            'student_id'            => $student->id,
            'status'                => $status,
            'scanned_at'            => $now,
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
     * Never overwrites existing Present or Late records.
     * Skips sessions that are already fully processed.
     *
     * Returns the number of Absent records created.
     */
    public function markAbsentForEndedSessions(): int
    {
        $tz    = 'Asia/Manila';
        $now   = Carbon::now($tz);
        $today = $now->toDateString();
        $count = 0;

        // Find open sessions for today whose schedule's end_time has passed
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

            // All enrolled students for this class
            $enrolledStudentIds = $assignment->students->pluck('id');

            // Students that already have a record for this session
            $recordedStudentIds = AttendanceRecord::where('attendance_session_id', $session->id)
                ->pluck('student_id');

            // Students with no record → mark Absent
            $absentStudentIds = $enrolledStudentIds->diff($recordedStudentIds);

            foreach ($absentStudentIds as $studentId) {
                AttendanceRecord::create([
                    'attendance_session_id' => $session->id,
                    'student_id'            => $studentId,
                    'status'                => AttendanceRecord::STATUS_ABSENT,
                    'scanned_at'            => null,
                ]);
                $count++;
            }

            // Close the session
            $session->update([
                'status'    => 'closed',
                'closed_at' => $now,
            ]);
        }

        return $count;
    }
}
