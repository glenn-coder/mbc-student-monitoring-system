<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $perPage = $request->input('per_page', 5);

        $subjects = Subject::query()
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('subject_code', 'like', "%{$search}%")
                      ->orWhere('subject_name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($statusFilter, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate($perPage);

        return view('admin.subjects.index', compact('subjects', 'search', 'perPage', 'statusFilter'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_code' => 'required|string|max:255|unique:subjects,subject_code',
            'subject_name' => 'required|string|max:255',
            'units' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $subject = Subject::create($validated);

        \App\Models\AuditLog::logAction('created', "Created subject {$subject->subject_name} ({$subject->subject_code})", 'success');

        return redirect()->route('admin.subjects.index')->with('success', 'Subject added successfully.');
    }

    public function show(Subject $subject)
    {
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'subject_code' => 'required|string|max:255|unique:subjects,subject_code,' . $subject->id,
            'subject_name' => 'required|string|max:255',
            'units' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $subject->update($validated);

        \App\Models\AuditLog::logAction('updated', "Updated subject {$subject->subject_name} ({$subject->subject_code})", 'success');

        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        \App\Models\AuditLog::logAction('deleted', "Deleted subject {$subject->subject_name} ({$subject->subject_code})", 'success');
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Subject removed successfully.');
    }
}
