<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use App\Exports\InstructorsExport;

class ReportController extends Controller
{
    public function students(Request $request)
    {
        $query = Student::whereHas('user', function($q) {
            $q->where('status', 'active');
        });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('course')) {
            $query->whereHas('course', function($q) use ($request) {
                $q->where('code', 'like', '%' . $request->course . '%')
                  ->orWhere('name', 'like', '%' . $request->course . '%');
            });
        }
        if ($request->filled('year')) {
            $query->where('year', 'like', '%' . $request->year . '%');
        }

        $metricsQuery = clone $query;
        
        $totalActive = $metricsQuery->count();
        
        // Group by course
        $courseCounts = (clone $metricsQuery)->selectRaw('course_id, count(*) as total')
                    ->groupBy('course_id')
                    ->get();
        $byCourse = [];
        foreach ($courseCounts as $row) {
            $course = \App\Models\Course::find($row->course_id);
            if ($course) {
                $byCourse[$course->code] = $row->total;
            }
        }
        
        // Group by year
        $byYear = (clone $metricsQuery)->selectRaw('year, count(*) as total')
                    ->groupBy('year')
                    ->pluck('total', 'year')->toArray();
                    
        // Group by sex
        $bySexRaw = (clone $metricsQuery)->selectRaw('sex, count(*) as total')
                    ->groupBy('sex')
                    ->pluck('total', 'sex')->toArray();
                    
        $bySex = [];
        foreach ($bySexRaw as $key => $value) {
            $label = $key;
            if ($key === 'M') {
                $label = 'Male';
            } elseif ($key === 'F') {
                $label = 'Female';
            }
            $bySex[$label] = ($bySex[$label] ?? 0) + $value;
        }
        
        $metrics = [
            'total_active' => $totalActive,
            'by_course' => $byCourse,
            'by_year' => $byYear,
            'by_sex' => $bySex,
        ];

        $students = $query->orderBy('last_name')->paginate($request->get('per_page', 5))->appends($request->except('page'));
        return view('admin.reports.students', compact('students', 'metrics'));
    }

    public function exportStudents(Request $request, string $type)
    {
        $query = Student::whereHas('user', function($q) {
            $q->where('status', 'active');
        });
        
        if ($request->filled('course')) {
            $query->where('course', 'like', '%' . $request->course . '%');
        }
        if ($request->filled('year')) {
            $query->where('year', 'like', '%' . $request->year . '%');
        }
        
        $students = $query->orderBy('last_name')->get();

        if ($type === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.students', compact('students'));
            return $pdf->download('student_master_list.pdf');
        } elseif ($type === 'excel') {
            return Excel::download(new StudentsExport($students), 'student_master_list.xlsx');
        } elseif ($type === 'csv') {
            return Excel::download(new StudentsExport($students), 'student_master_list.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        abort(404);
    }

    public function inactiveStudents(Request $request)
    {
        $query = Student::whereHas('user', function($q) {
            $q->where('status', 'inactive');
        });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('course')) {
            $query->whereHas('course', function($q) use ($request) {
                $q->where('code', 'like', '%' . $request->course . '%')
                  ->orWhere('name', 'like', '%' . $request->course . '%');
            });
        }
        if ($request->filled('year')) {
            $query->where('year', 'like', '%' . $request->year . '%');
        }

        $metricsQuery = clone $query;
        
        $totalInactive = $metricsQuery->count();
        
        // Group by course
        $courseCounts = (clone $metricsQuery)->selectRaw('course_id, count(*) as total')
                    ->groupBy('course_id')
                    ->get();
        $byCourse = [];
        foreach ($courseCounts as $row) {
            $course = \App\Models\Course::find($row->course_id);
            if ($course) {
                $byCourse[$course->code] = $row->total;
            }
        }
        
        // Group by year
        $byYear = (clone $metricsQuery)->selectRaw('year, count(*) as total')
                    ->groupBy('year')
                    ->pluck('total', 'year')->toArray();
                    
        // Group by sex
        $bySexRaw = (clone $metricsQuery)->selectRaw('sex, count(*) as total')
                    ->groupBy('sex')
                    ->pluck('total', 'sex')->toArray();
                    
        $bySex = [];
        foreach ($bySexRaw as $key => $value) {
            $label = $key;
            if ($key === 'M') {
                $label = 'Male';
            } elseif ($key === 'F') {
                $label = 'Female';
            }
            $bySex[$label] = ($bySex[$label] ?? 0) + $value;
        }
        
        $metrics = [
            'total_inactive' => $totalInactive,
            'by_course' => $byCourse,
            'by_year' => $byYear,
            'by_sex' => $bySex,
        ];

        $students = $query->orderBy('last_name')->paginate($request->get('per_page', 5))->appends($request->except('page'));
        return view('admin.reports.inactive_students', compact('students', 'metrics'));
    }

    public function exportInactiveStudents(Request $request, string $type)
    {
        $query = Student::whereHas('user', function($q) {
            $q->where('status', 'inactive');
        });
        
        if ($request->filled('course')) {
            $query->where('course', 'like', '%' . $request->course . '%');
        }
        if ($request->filled('year')) {
            $query->where('year', 'like', '%' . $request->year . '%');
        }
        
        $students = $query->orderBy('last_name')->get();

        if ($type === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.inactive_students', compact('students'));
            return $pdf->download('inactive_students_report.pdf');
        } elseif ($type === 'excel') {
            return Excel::download(new StudentsExport($students), 'inactive_students_report.xlsx');
        } elseif ($type === 'csv') {
            return Excel::download(new StudentsExport($students), 'inactive_students_report.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        abort(404);
    }

    public function instructors(Request $request)
    {
        $query = Instructor::whereHas('user', function($q) {
            $q->where('status', 'active');
        })->with('assignments.students');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('instructor_number', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('course')) {
            $query->whereHas('assignments.course', function($q) use ($request) {
                $q->where('code', 'like', '%' . $request->course . '%');
            });
        }
        if ($request->filled('specialization')) {
            $query->where('major_specialization', 'like', '%' . $request->specialization . '%');
        }

        $instructors = $query->orderBy('full_name')->paginate($request->get('per_page', 5))->appends($request->except('page'));
        return view('admin.reports.instructors', compact('instructors'));
    }

    public function exportInstructors(Request $request, string $type)
    {
        $query = Instructor::whereHas('user', function($q) {
            $q->where('status', 'active');
        })->with('assignments.students');

        if ($request->filled('course')) {
            $query->where('course', 'like', '%' . $request->course . '%');
        }
        if ($request->filled('specialization')) {
            $query->where('major_specialization', 'like', '%' . $request->specialization . '%');
        }

        $instructors = $query->orderBy('full_name')->get();

        if ($type === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.instructors', compact('instructors'));
            return $pdf->download('instructor_workload.pdf');
        } elseif ($type === 'excel') {
            return Excel::download(new InstructorsExport($instructors), 'instructor_workload.xlsx');
        } elseif ($type === 'csv') {
            return Excel::download(new InstructorsExport($instructors), 'instructor_workload.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        abort(404);
    }
}
