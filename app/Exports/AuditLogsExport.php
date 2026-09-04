<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AuditLogsExport implements FromView, ShouldAutoSize
{
    protected $logs;
    protected $metrics;

    public function __construct($logs, $metrics = [])
    {
        $this->logs = $logs;
        $this->metrics = $metrics;
    }

    public function view(): View
    {
        return view('exports.audit_logs', [
            'logs' => $this->logs,
            'metrics' => $this->metrics,
        ]);
    }
}
