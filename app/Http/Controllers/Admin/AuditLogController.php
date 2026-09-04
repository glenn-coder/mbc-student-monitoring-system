<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AuditLogsExport;

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

    public function export(Request $request, $type)
    {
        $search = $request->input('search');
        $actionFilter = $request->input('action');
        $statusFilter = $request->input('status');

        $query = AuditLog::with('user')
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
            ->latest();

                $metrics = $this->calculateAuditMetrics($query);
        $logs = $query->get();

        if ($type === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.audit_logs', compact('logs', 'metrics'));
            return $pdf->download('audit_logs.pdf');
        } elseif ($type === 'excel') {
            return Excel::download(new AuditLogsExport($logs, $metrics), 'audit_logs.xlsx');
        } elseif ($type === 'csv') {
            return Excel::download(new AuditLogsExport($logs, $metrics), 'audit_logs.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        abort(404);
    }

    private function calculateAuditMetrics($query)
    {
        $metricsQuery = clone $query;
        $total = $metricsQuery->count();
        
        $byAction = (clone $metricsQuery)->selectRaw('action, count(*) as total')
                    ->groupBy('action')
                    ->pluck('total', 'action')->toArray();
                    
        $byStatus = (clone $metricsQuery)->selectRaw('status, count(*) as total')
                    ->groupBy('status')
                    ->pluck('total', 'status')->toArray();
                    
        return [
            'total_logs' => $total,
            'by_action' => $byAction,
            'by_status' => $byStatus,
        ];
    }
}