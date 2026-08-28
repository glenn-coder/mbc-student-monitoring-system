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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'student_number' => 'required|string|max:255|unique:students,student_number',
            'sex' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'year' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'password' => ['required', 'string', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = \App\Models\User::where('username', $validated['student_number'])->first();
        if (!$user) {
            $user = \App\Models\User::create([
                'username' => $validated['student_number'],
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
                'role' => 'student',
                'status' => $validated['status'],
            ]);
        }

        Student::create([
            'user_id' => $user->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'student_number' => $validated['student_number'],
            'sex' => $validated['sex'],
            'course_id' => $validated['course_id'],
            'year' => $validated['year'],
        ]);

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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'student_number' => 'required|string|max:255|unique:students,student_number,' . $student->id,
            'sex' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'year' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'password' => ['nullable', 'string', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $student->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'student_number' => $validated['student_number'],
            'sex' => $validated['sex'],
            'course_id' => $validated['course_id'],
            'year' => $validated['year'],
        ]);

        if ($student->user) {
            $userData = [
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'username' => $validated['student_number'],
                'status' => $validated['status'],
            ];
            if (!empty($validated['password'])) {
                $userData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
            }
            $student->user->update($userData);
        }

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        if ($student->user) {
            $student->user->delete();
        }
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Student removed successfully.');
    }
}
