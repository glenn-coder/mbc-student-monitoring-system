<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Instructor;
use App\Models\Subject;
use App\Models\Student;
use App\Models\InstructorAssignment;

class InstructorAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $instructorId = $request->input('instructor_id');
        $subjectId = $request->input('subject_id');
        $yearFilter = $request->input('year');
        $perPage = $request->input('per_page', 5);

        $assignments = InstructorAssignment::with(['instructor', 'subject', 'course'])
            ->withCount(['students' => function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('status', 'active');
                });
            }])
            ->when($search, function($query, $search) {
                return $query->whereHas('instructor', function($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%");
                })->orWhereHas('subject', function($q) use ($search) {
                    $q->where('subject_name', 'like', "%{$search}%")
                      ->orWhere('subject_code', 'like', "%{$search}%");
                });
            })
            ->when($instructorId, function($query, $instructorId) {
                return $query->where('instructor_id', $instructorId);
            })
            ->when($subjectId, function($query, $subjectId) {
                return $query->where('subject_id', $subjectId);
            })
            ->when($yearFilter, function($query, $year) {
                return $query->where('year', $year);
            })
            ->latest()
            ->paginate($perPage)
            ->appends($request->all());

        $instructors = Instructor::whereHas('user', function($query) {
            $query->where('status', 'active');
        })->get();
        $subjects = Subject::where('status', 'active')->get();
        $courses = \App\Models\Course::all();

        return view('admin.assignments.index', compact('assignments', 'instructors', 'subjects', 'courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'instructor_id' => 'required|exists:instructors,id',
            'subject_id' => [
                'required',
                'exists:subjects,id',
                \Illuminate\Validation\Rule::unique('instructor_assignments'),
            ],
            'course_id' => 'required|exists:courses,id',
            'year' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
        ], [
            'subject_id.unique' => 'This subject is already assigned to an instructor.',
        ]);

        $assignment = InstructorAssignment::create($validated);
        $assignment->load(['instructor', 'subject', 'course']);

        \App\Models\AuditLog::logAction('created', "Created assignment for instructor {$assignment->instructor->full_name} to subject {$assignment->subject->subject_code}", 'success');

        return redirect()->route('admin.assignments.index')->with('success', 'Faculty assignment created successfully.');
    }

    public function update(Request $request, InstructorAssignment $assignment)
    {
        $validated = $request->validate([
            'instructor_id' => 'required|exists:instructors,id',
            'subject_id' => [
                'required',
                'exists:subjects,id',
                \Illuminate\Validation\Rule::unique('instructor_assignments')->ignore($assignment->id),
            ],
            'course_id' => 'required|exists:courses,id',
            'year' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
        ], [
            'subject_id.unique' => 'This subject is already assigned to an instructor.',
        ]);

        $assignment->update($validated);
        $assignment->load(['instructor', 'subject', 'course']);

        \App\Models\AuditLog::logAction('updated', "Updated assignment for instructor {$assignment->instructor->full_name} to subject {$assignment->subject->subject_code}", 'success');

        return redirect()->route('admin.assignments.index')->with('success', 'Faculty assignment updated successfully.');
    }

    public function destroy(InstructorAssignment $assignment)
    {
        $assignment->load(['instructor', 'subject']);
        \App\Models\AuditLog::logAction('deleted', "Deleted assignment for instructor {$assignment->instructor->full_name} to subject {$assignment->subject->subject_code}", 'success');

        $assignment->delete();

        return back()->with('success', 'Assignment deleted successfully.');
    }

    public function instructorClasses(Instructor $instructor)
    {
        $assignments = $instructor->assignments()->with('subject')->get();
        
        return view('admin.instructors.classes', compact('instructor', 'assignments'));
    }

    public function classStudents(InstructorAssignment $assignment)
    {
        $assignment->load(['instructor', 'subject', 'students' => function ($query) {
            $query->whereHas('user', function ($q) {
                $q->where('status', 'active');
            })->orderBy('last_name');
        }]);
        
        return view('admin.assignments.students', compact('assignment'));
    }

    public function addStudents(InstructorAssignment $assignment)
    {
        $assignment->load(['instructor', 'subject']);
        $students = Student::whereHas('user', function($query) {
            $query->where('status', 'active');
        })->orderBy('last_name')->get();
        
        // Get IDs of already enrolled students
        $enrolledStudentIds = $assignment->students->pluck('id')->toArray();

        return view('admin.assignments.add-students', compact('assignment', 'students', 'enrolledStudentIds'));
    }

    public function storeStudents(Request $request, InstructorAssignment $assignment)
    {
        $validated = $request->validate([
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        // Sync will remove any students not in the array and add any new ones
        $assignment->students()->sync($validated['student_ids'] ?? []);

        $assignment->load(['instructor', 'subject']);
        \App\Models\AuditLog::logAction('updated', "Updated students for assignment {$assignment->subject->subject_code} ({$assignment->instructor->full_name})", 'success');

        return redirect()->route('admin.assignments.index')->with('success', 'Students updated successfully.');
    }
}
