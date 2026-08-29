<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $perPage = (int) $request->input('per_page', 5);

        $admins = User::query()->where('role', 'admin')
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($statusFilter, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate($perPage);

        return view('admin.admins.index', compact('admins', 'search', 'perPage', 'statusFilter'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|max:255|unique:users,email',
            'status' => 'required|in:active,inactive',
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $admin = User::query()->create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'status' => $validated['status'],
        ]);

        \App\Models\AuditLog::logAction('created', "Created admin user {$admin->name} ({$admin->username})", 'success');

        return redirect()->route('admin.admins.index')->with('success', 'Admin user created successfully.');
    }

    public function edit(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'status' => 'required|in:active,inactive',
            'password' => ['nullable', 'string', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $admin->name = $validated['name'];
        $admin->username = $validated['username'];
        $admin->email = $validated['email'] ?? null;
        $admin->status = $validated['status'];
        
        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        \App\Models\AuditLog::logAction('updated', "Updated admin user {$admin->name} ({$admin->username})", 'success');

        return redirect()->route('admin.admins.index')->with('success', 'Admin user updated successfully.');
    }

    public function destroy(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }
        
        if (Auth::id() === $admin->id) {
            return redirect()->route('admin.admins.index')->with('error', 'You cannot delete your own account.');
        }

        \App\Models\AuditLog::logAction('deleted', "Deleted admin user {$admin->name} ({$admin->username})", 'success');
        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin user deleted successfully.');
    }
}
