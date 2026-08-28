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
        $perPage = $request->input('per_page', 5);

        $assignments = InstructorAssignment::with(['instructor', 'subject', 'course'])
            ->when($search, function($query, $search) {
                return $query->whereHas('instructor', function($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%");
                })->orWhereHas('subject', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
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
            'subject_id' => 'required|exists:subjects,id',
            'course_id' => 'required|exists:courses,id',
            'year' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
        ]);

        InstructorAssignment::create($validated);

        return redirect()->route('admin.assignments.index')->with('success', 'Faculty assignment created successfully.');
    }

    public function update(Request $request, InstructorAssignment $assignment)
    {
        $validated = $request->validate([
            'instructor_id' => 'required|exists:instructors,id',
            'subject_id' => 'required|exists:subjects,id',
            'course_id' => 'required|exists:courses,id',
            'year' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
        ]);

        $assignment->update($validated);

        return redirect()->route('admin.assignments.index')->with('success', 'Faculty assignment updated successfully.');
    }

    public function destroy(InstructorAssignment $assignment)
    {
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
        $assignment->load(['instructor', 'subject', 'students']);
        
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

        return redirect()->route('admin.assignments.index')->with('success', 'Students updated successfully.');
    }
}
