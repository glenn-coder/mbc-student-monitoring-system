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

        $metrics = $this->calculateStudentMetrics($query, 'active');

        $students = $query->orderBy('last_name')->paginate($request->get('per_page', 5))->appends($request->except('page'));
        return view('admin.reports.students', compact('students', 'metrics'));
    }

    public function exportStudents(Request $request, string $type)
    {
        $query = Student::whereHas('user', function($q) {
            $q->where('status', 'active');
        });
        
        if ($request->filled('course')) {
            $query->whereHas('course', function($q) use ($request) {
                $q->where('code', 'like', '%' . $request->course . '%')
                  ->orWhere('name', 'like', '%' . $request->course . '%');
            });
        }
        if ($request->filled('year')) {
            $query->where('year', 'like', '%' . $request->year . '%');
        }
        
        $metrics = $this->calculateStudentMetrics($query, 'active');
        $students = $query->orderBy('last_name')->get();

        if ($type === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.students', compact('students', 'metrics'));
            return $pdf->download('student_master_list.pdf');
        } elseif ($type === 'excel') {
            return Excel::download(new StudentsExport($students, $metrics, 'active'), 'student_master_list.xlsx');
        } elseif ($type === 'csv') {
            return Excel::download(new StudentsExport($students, $metrics, 'active'), 'student_master_list.csv', \Maatwebsite\Excel\Excel::CSV);
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

        $metrics = $this->calculateStudentMetrics($query, 'inactive');

        $students = $query->orderBy('last_name')->paginate($request->get('per_page', 5))->appends($request->except('page'));
        return view('admin.reports.inactive_students', compact('students', 'metrics'));
    }

    public function exportInactiveStudents(Request $request, string $type)
    {
        $query = Student::whereHas('user', function($q) {
            $q->where('status', 'inactive');
        });
        
        if ($request->filled('course')) {
            $query->whereHas('course', function($q) use ($request) {
                $q->where('code', 'like', '%' . $request->course . '%')
                  ->orWhere('name', 'like', '%' . $request->course . '%');
            });
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

        $metrics = $this->calculateInstructorMetrics($query);
        $instructors = $query->orderBy('full_name')->paginate($request->get('per_page', 5))->appends($request->except('page'));
        return view('admin.reports.instructors', compact('instructors', 'metrics'));
    }

    public function exportInstructors(Request $request, string $type)
    {
        $query = Instructor::whereHas('user', function($q) {
            $q->where('status', 'active');
        })->with('assignments.students');

        if ($request->filled('course')) {
            $query->whereHas('assignments.course', function($q) use ($request) {
                $q->where('code', 'like', '%' . $request->course . '%');
            });
        }
        if ($request->filled('specialization')) {
            $query->where('major_specialization', 'like', '%' . $request->specialization . '%');
        }

        $metrics = $this->calculateInstructorMetrics($query);
        $instructors = $query->orderBy('full_name')->get();

        if ($type === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.instructors', compact('instructors', 'metrics'));
            return $pdf->download('instructor_workload.pdf');
        } elseif ($type === 'excel') {
            return Excel::download(new InstructorsExport($instructors, $metrics), 'instructor_workload.xlsx');
        } elseif ($type === 'csv') {
            return Excel::download(new InstructorsExport($instructors, $metrics), 'instructor_workload.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        abort(404);
    }

    private function calculateStudentMetrics($query, $type)
    {
        $metricsQuery = clone $query;
        $total = $metricsQuery->count();
        
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
        
        $byYear = (clone $metricsQuery)->selectRaw('year, count(*) as total')
                    ->groupBy('year')
                    ->pluck('total', 'year')->toArray();
                    
        $bySexRaw = (clone $metricsQuery)->selectRaw('sex, count(*) as total')
                    ->groupBy('sex')
                    ->pluck('total', 'sex')->toArray();
                    
        $bySex = [];
        foreach ($bySexRaw as $key => $value) {
            $label = $key === 'M' ? 'Male' : ($key === 'F' ? 'Female' : $key);
            $bySex[$label] = ($bySex[$label] ?? 0) + $value;
        }
        
        return [
            'total_' . $type => $total,
            'by_course' => $byCourse,
            'by_year' => $byYear,
            'by_sex' => $bySex,
        ];
    }

    private function calculateInstructorMetrics($query)
    {
        $metricsQuery = clone $query;
        $total = $metricsQuery->count();
        
        $bySpecialization = (clone $metricsQuery)->selectRaw('major_specialization, count(*) as total')
                    ->groupBy('major_specialization')
                    ->pluck('total', 'major_specialization')->toArray();
                    
        return [
            'total_instructors' => $total,
            'by_specialization' => $bySpecialization,
        ];
    }
}