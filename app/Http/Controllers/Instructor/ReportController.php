<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Exports\AttendanceReportExport;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Instructor;
use App\Models\InstructorAssignment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Class Attendance Summary — main report for the authenticated instructor.
     *
     * GET /instructor/reports
     */
    public function index(Request $request)
    {
        // ── Resolve authenticated instructor ──────────────────────────────────
        $instructor = Instructor::where('user_id', Auth::id())->first();

        if (!$instructor) {
            abort(403, 'No instructor profile found.');
        }

        // All assignment IDs that belong to this instructor (used as a safe scope)
        $allAssignmentIds = $instructor->assignments()->pluck('id');

        // ── Validate selected assignment ──────────────────────────────────────
        // If the request contains an assignment_id, verify it belongs to this instructor.
        $selectedAssignmentId = null;
        $selectedAssignment   = null;

        if ($request->filled('assignment_id')) {
            // Ownership check: must be in the instructor's own assignment IDs
            if ($allAssignmentIds->contains((int) $request->assignment_id)) {
                $selectedAssignmentId = (int) $request->assignment_id;
                $selectedAssignment   = InstructorAssignment::with(['subject', 'course'])
                    ->find($selectedAssignmentId);
            }
            // If the ID is not owned by this instructor, silently ignore it (no 403,
            // just show the full list with no class selected).
        }

        // ── Determine which assignment IDs are in scope for this report ───────
        $scopedAssignmentIds = $selectedAssignmentId
            ? collect([$selectedAssignmentId])
            : $allAssignmentIds;

        // ── Resolve closed attendance sessions within the scope & date range ──
        $scheduleIds = \App\Models\Schedule::whereIn('instructor_assignment_id', $scopedAssignmentIds)
            ->pluck('id');

        $sessionQuery = AttendanceSession::whereIn('schedule_id', $scheduleIds)
            ->where('status', 'closed'); // only finalised sessions

        if ($request->filled('date_from')) {
            $sessionQuery->whereDate('session_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $sessionQuery->whereDate('session_date', '<=', $request->date_to);
        }

        $completedSessionIds = $sessionQuery->pluck('id');

        // ── Summary card totals ───────────────────────────────────────────────
        // Total enrolled students (from pivot, distinct) for the selected scope
        $totalStudents = DB::table('assignment_student')
            ->whereIn('instructor_assignment_id', $scopedAssignmentIds)
            ->distinct('student_id')
            ->count('student_id');

        $recordTotals = AttendanceRecord::whereIn('attendance_session_id', $completedSessionIds)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalPresent = $recordTotals->get('present', 0);
        $totalLate    = $recordTotals->get('late', 0);
        $totalAbsent  = $recordTotals->get('absent', 0);

        // Overall attendance rate across all records: (present + late) / total × 100
        $totalRecords          = $totalPresent + $totalLate + $totalAbsent;
        $totalAttendanceRate   = $totalRecords > 0
            ? round(($totalPresent + $totalLate) / $totalRecords * 100)
            : null;

        // ── Count completed sessions per assignment (for attendance rate) ──────
        // When a specific assignment is selected, count sessions for that assignment.
        // When no assignment is selected (all classes), sum up per-student totals
        // using completed session IDs already resolved.
        $completedSessionCount = $completedSessionIds->count();

        // ── Build the enrolled student list with per-student aggregates ────────
        // Get all students enrolled in the scope
        $enrolledStudentIds = DB::table('assignment_student')
            ->whereIn('instructor_assignment_id', $scopedAssignmentIds)
            ->pluck('student_id')
            ->unique();

        // Per-student record counts from the completed sessions
        $studentRecordCounts = AttendanceRecord::whereIn('attendance_session_id', $completedSessionIds)
            ->whereIn('student_id', $enrolledStudentIds)
            ->selectRaw('student_id, status, count(*) as total')
            ->groupBy('student_id', 'status')
            ->get()
            ->groupBy('student_id');

        // ── Build the paginated student query ─────────────────────────────────
        $studentQuery = \App\Models\Student::whereIn('id', $enrolledStudentIds)
            ->with('course');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $studentQuery->where(function ($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                  ->orWhere('first_name',    'like', "%{$search}%")
                  ->orWhere('last_name',     'like', "%{$search}%")
                  ->orWhereHas('course', function ($q2) use ($search) {
                      $q2->where('code', 'like', "%{$search}%");
                  });
            });
        }

        $perPage  = (int) $request->get('per_page', 10);
        $perPage  = in_array($perPage, [5, 10, 25, 50]) ? $perPage : 10;

        $students = $studentQuery->orderBy('last_name')->orderBy('first_name')
            ->paginate($perPage)
            ->appends($request->except('page'));

        // Attach per-student attendance counts
        $students->getCollection()->transform(function ($student) use ($studentRecordCounts) {
            $counts  = $studentRecordCounts->get($student->id, collect());
            $present = $counts->firstWhere('status', 'present')?->total ?? 0;
            $late    = $counts->firstWhere('status', 'late')?->total    ?? 0;
            $absent  = $counts->firstWhere('status', 'absent')?->total  ?? 0;
            $total   = $present + $late + $absent;

            $student->stat_present = $present;
            $student->stat_late    = $late;
            $student->stat_absent  = $absent;
            // Rate = (present + late) / total records × 100
            // If no sessions yet, rate is N/A
            $student->stat_rate    = $total > 0
                ? round(($present + $late) / $total * 100)
                : null;

            return $student;
        });

        // ── Assignments list for the filter dropdown ───────────────────────────
        $assignments = InstructorAssignment::where('instructor_id', $instructor->id)
            ->with(['subject', 'course'])
            ->get();

        return view('instructor.reports.index', compact(
            'students',
            'assignments',
            'selectedAssignment',
            'selectedAssignmentId',
            'totalStudents',
            'totalPresent',
            'totalLate',
            'totalAbsent',
            'totalAttendanceRate',
            'completedSessionCount',
            'perPage'
        ));
    }

    /**
     * Export the Class Attendance Summary.
     *
     * GET /instructor/reports/export/{type}   — type: pdf | excel | csv
     */
    public function export(Request $request, string $type)
    {
        // ── Resolve instructor ────────────────────────────────────────────────
        $instructor = Instructor::where('user_id', Auth::id())->first();
        if (!$instructor) {
            abort(403, 'No instructor profile found.');
        }

        $allAssignmentIds = $instructor->assignments()->pluck('id');

        // ── Validate assignment ownership ─────────────────────────────────────
        $selectedAssignmentId = null;
        $selectedAssignment   = null;
        if ($request->filled('assignment_id') && $allAssignmentIds->contains((int) $request->assignment_id)) {
            $selectedAssignmentId = (int) $request->assignment_id;
            $selectedAssignment   = InstructorAssignment::with(['subject', 'course'])->find($selectedAssignmentId);
        }

        $scopedAssignmentIds = $selectedAssignmentId
            ? collect([$selectedAssignmentId])
            : $allAssignmentIds;

        // ── Closed sessions in scope ──────────────────────────────────────────
        $scheduleIds = \App\Models\Schedule::whereIn('instructor_assignment_id', $scopedAssignmentIds)->pluck('id');

        $sessionQuery = AttendanceSession::whereIn('schedule_id', $scheduleIds)->where('status', 'closed');
        if ($request->filled('date_from')) {
            $sessionQuery->whereDate('session_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $sessionQuery->whereDate('session_date', '<=', $request->date_to);
        }
        $completedSessionIds = $sessionQuery->pluck('id');

        // ── Summary totals ────────────────────────────────────────────────────
        $totalStudents = DB::table('assignment_student')
            ->whereIn('instructor_assignment_id', $scopedAssignmentIds)
            ->distinct('student_id')
            ->count('student_id');

        $recordTotals = AttendanceRecord::whereIn('attendance_session_id', $completedSessionIds)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalPresent = $recordTotals->get('present', 0);
        $totalLate    = $recordTotals->get('late', 0);
        $totalAbsent  = $recordTotals->get('absent', 0);
        $totalRecords = $totalPresent + $totalLate + $totalAbsent;
        $totalAttendanceRate = $totalRecords > 0
            ? round(($totalPresent + $totalLate) / $totalRecords * 100)
            : null;

        $summary = compact('totalStudents', 'totalPresent', 'totalLate', 'totalAbsent', 'totalAttendanceRate');

        // ── All enrolled students with per-student stats ───────────────────────
        $enrolledStudentIds = DB::table('assignment_student')
            ->whereIn('instructor_assignment_id', $scopedAssignmentIds)
            ->pluck('student_id')
            ->unique();

        $studentRecordCounts = AttendanceRecord::whereIn('attendance_session_id', $completedSessionIds)
            ->whereIn('student_id', $enrolledStudentIds)
            ->selectRaw('student_id, status, count(*) as total')
            ->groupBy('student_id', 'status')
            ->get()
            ->groupBy('student_id');

        $students = \App\Models\Student::whereIn('id', $enrolledStudentIds)
            ->with('course')
            ->orderBy('last_name')->orderBy('first_name')
            ->get()
            ->map(function ($student) use ($studentRecordCounts) {
                $counts  = $studentRecordCounts->get($student->id, collect());
                $present = $counts->firstWhere('status', 'present')?->total ?? 0;
                $late    = $counts->firstWhere('status', 'late')?->total    ?? 0;
                $absent  = $counts->firstWhere('status', 'absent')?->total  ?? 0;
                $total   = $present + $late + $absent;

                $student->stat_present = $present;
                $student->stat_late    = $late;
                $student->stat_absent  = $absent;
                $student->stat_rate    = $total > 0 ? round(($present + $late) / $total * 100) : null;
                return $student;
            });

        // ── Filter label for the PDF header ───────────────────────────────────
        $filterParts = [];
        if ($selectedAssignment) {
            $filterParts[] = ($selectedAssignment->subject->subject_name ?? 'N/A')
                           . ' — ' . ($selectedAssignment->course->code ?? 'N/A');
        }
        if ($request->filled('date_from')) {
            $filterParts[] = 'From: ' . \Carbon\Carbon::parse($request->date_from)->format('M d, Y');
        }
        if ($request->filled('date_to')) {
            $filterParts[] = 'To: ' . \Carbon\Carbon::parse($request->date_to)->format('M d, Y');
        }
        $filterLabel = implode(' | ', $filterParts);

        // ── Dispatch the correct export format ────────────────────────────────
        $filename = 'class_attendance_summary_' . now()->format('Ymd_His');

        if ($type === 'pdf') {
            $pdf = Pdf::loadView('instructor.reports.pdf.class-attendance', compact('students', 'summary', 'filterLabel'))
                      ->setPaper('a4', 'landscape');
            return $pdf->download("{$filename}.pdf");
        }

        if ($type === 'excel') {
            return Excel::download(new AttendanceReportExport($students, $summary), "{$filename}.xlsx");
        }

        if ($type === 'csv') {
            return Excel::download(new AttendanceReportExport($students, $summary), "{$filename}.csv", \Maatwebsite\Excel\Excel::CSV);
        }

        abort(404);
    }
}
