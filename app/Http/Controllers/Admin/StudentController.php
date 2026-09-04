<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $courseFilter = $request->input('course_id');
        $yearFilter = $request->input('year');
        $perPage = $request->input('per_page', 5);

        $students = Student::query()
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('student_number', 'like', "%{$search}%")
                      ->orWhere('course', 'like', "%{$search}%");
                });
            })
            ->when($courseFilter, function ($query, $courseFilter) {
                return $query->where('course_id', $courseFilter);
            })
            ->when($yearFilter, function ($query, $yearFilter) {
                return $query->where('year', 'like', "%{$yearFilter}%");
            })
            ->latest()
            ->paginate($perPage);

        // Fetch courses and years for the filter dropdowns
        $courses = \App\Models\Course::all();
        $years = Student::select('year')->distinct()->pluck('year')->filter();

        return view('admin.students.index', compact('students', 'courses', 'years'));
    }

    public function create()
    {
        $courses = \App\Models\Course::all();
        return view('admin.students.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'     => 'required|string|max:255',
            'middle_name'    => 'nullable|string|max:255',
            'last_name'      => 'required|string|max:255',
            'student_number' => 'required|string|max:255|unique:students,student_number',
            'sex'            => 'required|string|max:255',
            'course_id'      => 'required|exists:courses,id',
            'email'          => 'nullable|email|max:255|unique:students,email',
            'year'           => 'required|string|max:255',
            'status'         => 'required|in:active,inactive',
        ]);

        // Auto-generate password: CourseCode-StudentNumber
        $course = \App\Models\Course::findOrFail($validated['course_id']);
        $autoPassword = $course->code . '-' . $validated['student_number'];

        $user = \App\Models\User::where('username', $validated['student_number'])->first();
        if (!$user) {
            $user = \App\Models\User::create([
                'username' => $validated['student_number'],
                'name'     => $validated['first_name'] . ' ' . $validated['last_name'],
                'password' => \Illuminate\Support\Facades\Hash::make($autoPassword),
                'role'     => 'student',
                'status'   => $validated['status'],
            ]);
        }

        $student = Student::create([
            'user_id'        => $user->id,
            'first_name'     => $validated['first_name'],
            'middle_name'    => $validated['middle_name'] ?? null,
            'last_name'      => $validated['last_name'],
            'student_number' => $validated['student_number'],
            'sex'            => $validated['sex'],
            'course_id'      => $validated['course_id'],
            'email'          => $validated['email'],
            'year'           => $validated['year'],
        ]);

        \App\Models\AuditLog::logAction('created', "Created student {$student->first_name} {$student->last_name} ({$student->student_number})", 'success');

        return redirect()->route('admin.students.index')->with('success', 'Student added successfully.');
    }

    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $courses = \App\Models\Course::all();
        return view('admin.students.edit', compact('student', 'courses'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name'      => 'required|string|max:255',
            'middle_name'     => 'nullable|string|max:255',
            'last_name'       => 'required|string|max:255',
            'student_number'  => 'required|string|max:255|unique:students,student_number,' . $student->id,
            'sex'             => 'required|string|max:255',
            'course_id'       => 'required|exists:courses,id',
            'email'           => 'nullable|email|max:255|unique:students,email,' . $student->id,
            'year'            => 'required|string|max:255',
            'status'          => 'required|in:active,inactive',
            'password_action' => 'nullable|in:none,reset',
        ]);

        $student->update([
            'first_name'     => $validated['first_name'],
            'middle_name'    => $validated['middle_name'] ?? null,
            'last_name'      => $validated['last_name'],
            'student_number' => $validated['student_number'],
            'sex'            => $validated['sex'],
            'course_id'      => $validated['course_id'],
            'email'          => $validated['email'],
            'year'           => $validated['year'],
        ]);

        if ($student->user) {
            $userData = [
                'name'     => $validated['first_name'] . ' ' . $validated['last_name'],
                'username' => $validated['student_number'],
                'status'   => $validated['status'],
            ];

            $passwordAction = $validated['password_action'] ?? 'none';

            if ($passwordAction === 'reset') {
                // Reset to auto-generated: CourseCode-StudentNumber
                $course = \App\Models\Course::find($validated['course_id']);
                $defaultPassword = $course->code . '-' . $validated['student_number'];
                $userData['password'] = \Illuminate\Support\Facades\Hash::make($defaultPassword);
            }
            // 'none' → do nothing with password

            $student->user->update($userData);
        }

        \App\Models\AuditLog::logAction('updated', "Updated student {$student->first_name} {$student->last_name} ({$student->student_number})", 'success');

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        if ($student->user) {
            $student->user->delete();
        }
        \App\Models\AuditLog::logAction('deleted', "Deleted student {$student->first_name} {$student->last_name} ({$student->student_number})", 'success');

        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Student removed successfully.');
    }
}
