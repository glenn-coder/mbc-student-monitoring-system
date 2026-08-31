<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        $courses = Course::query()
            ->when($search, function($query, $search) {
                return $query->where('code', 'like', "%{$search}%")
                             ->orWhere('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->appends($request->all());

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:courses',
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $course = Course::create($request->all());

        \App\Models\AuditLog::logAction('created', "Created course {$course->name} ({$course->code})", 'success');

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:courses,code,' . $course->id,
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $course->update($request->all());

        \App\Models\AuditLog::logAction('updated', "Updated course {$course->name} ({$course->code})", 'success');

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        \App\Models\AuditLog::logAction('deleted', "Deleted course {$course->name} ({$course->code})", 'success');
        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }
}
