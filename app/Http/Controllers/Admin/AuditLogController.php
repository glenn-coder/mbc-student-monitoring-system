<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $actionFilter = $request->input('action');
        $statusFilter = $request->input('status');
        $perPage = (int) $request->input('per_page', 10);

        $logs = AuditLog::with('user')
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('action', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('user', function($u) use ($search) {
                          $u->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->when($actionFilter, function ($query, $action) {
                return $query->where('action', $action);
            })
            ->when($statusFilter, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate($perPage);

        return view('admin.audit_logs.index', compact('logs'));
    }
}
