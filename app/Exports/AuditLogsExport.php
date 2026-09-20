<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AuditLogsExport implements FromView, ShouldAutoSize
{
    /** @var \Illuminate\Support\Collection|array */
    protected $logs;

    /** @var array */
    protected $metrics;

    public function __construct(\Illuminate\Support\Collection|array $logs, array $metrics = [])
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
