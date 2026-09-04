<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class InstructorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $emailFilter = $request->input('email');
        $specializationFilter = $request->input('specialization');
        $perPage = $request->input('per_page', 5);

        $instructors = Instructor::query()
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('instructor_number', 'like', "%{$search}%")
                      ->orWhere('major_specialization', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($emailFilter, function ($query, $emailFilter) {
                return $query->where('email', 'like', "%{$emailFilter}%");
            })
            ->when($specializationFilter, function ($query, $specializationFilter) {
                return $query->where('major_specialization', 'like', "%{$specializationFilter}%");
            })
            ->latest()
            ->paginate($perPage);

        return view('admin.instructors.index', compact('instructors', 'search', 'perPage'));
    }

    public function create()
    {
        return view('admin.instructors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'            => 'required|string|max:255',
            'instructor_number'    => 'required|string|max:255|unique:instructors,instructor_number',
            'major_specialization' => 'required|string|max:255',
            'sex'                  => 'required|string',
            'email'                => 'nullable|email|max:255|unique:instructors,email',
            'status'               => 'required|in:active,inactive',
        ]);

        // Auto-generate password: INSTR-{instructor_number}
        $autoPassword = 'INSTR-' . $validated['instructor_number'];

        // Check if user already exists
        $user = User::where('username', $validated['instructor_number'])->first();
        if (!$user) {
            $user = User::create([
                'username' => $validated['instructor_number'],
                'name'     => $validated['full_name'],
                'password' => Hash::make($autoPassword),
                'role'     => 'instructor',
                'status'   => $validated['status'],
            ]);
        }

        $instructor = Instructor::create([
            'user_id'              => $user->id,
            'full_name'            => $validated['full_name'],
            'instructor_number'    => $validated['instructor_number'],
            'major_specialization' => $validated['major_specialization'],
            'sex'                  => $validated['sex'],
            'email'                => $validated['email'],
        ]);

        \App\Models\AuditLog::logAction('created', "Created instructor {$instructor->full_name} ({$instructor->instructor_number})", 'success');

        return redirect()->route('admin.instructors.index')->with('success', 'Instructor added successfully.');
    }

    public function show(Instructor $instructor)
    {
        return view('admin.instructors.show', compact('instructor'));
    }

    public function edit(Instructor $instructor)
    {
        return view('admin.instructors.edit', compact('instructor'));
    }

    public function update(Request $request, Instructor $instructor)
    {
        $validated = $request->validate([
            'full_name'            => 'required|string|max:255',
            'instructor_number'    => 'required|string|max:255|unique:instructors,instructor_number,' . $instructor->id,
            'major_specialization' => 'required|string|max:255',
            'sex'                  => 'required|string',
            'email'                => 'nullable|email|max:255|unique:instructors,email,' . $instructor->id,
            'status'               => 'required|in:active,inactive',
            'password_action'      => 'nullable|in:none,reset',
        ]);

        $instructor->update([
            'full_name'            => $validated['full_name'],
            'instructor_number'    => $validated['instructor_number'],
            'major_specialization' => $validated['major_specialization'],
            'sex'                  => $validated['sex'],
            'email'                => $validated['email'],
        ]);

        if ($instructor->user) {
            $userData = [
                'name'     => $validated['full_name'],
                'username' => $validated['instructor_number'],
                'status'   => $validated['status'],
            ];

            if (($validated['password_action'] ?? 'none') === 'reset') {
                // Reset to auto-generated: INSTR-{instructor_number}
                $userData['password'] = Hash::make('INSTR-' . $validated['instructor_number']);
            }
            // 'none' → do nothing with password

            $instructor->user->update($userData);
        }

        \App\Models\AuditLog::logAction('updated', "Updated instructor {$instructor->full_name} ({$instructor->instructor_number})", 'success');

        return redirect()->route('admin.instructors.index')->with('success', 'Instructor updated successfully.');
    }

    public function destroy(Instructor $instructor)
    {
        if ($instructor->user) {
            $instructor->user->delete();
        }
        \App\Models\AuditLog::logAction('deleted', "Deleted instructor {$instructor->full_name} ({$instructor->instructor_number})", 'success');
        $instructor->delete();

        return redirect()->route('admin.instructors.index')->with('success', 'Instructor removed successfully.');
    }
}
