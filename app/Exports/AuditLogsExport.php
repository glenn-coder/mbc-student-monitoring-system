<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class AuditLogsExport implements FromCollection, WithHeadings, WithMapping
{
    protected Collection $logs;

    public function __construct(Collection $logs)
    {
        $this->logs = $logs;
    }

    public function collection(): Collection
    {
        return $this->logs;
    }

    public function headings(): array
    {
        return [
            'Date / Time',
            'User',
            'Action',
            'Description',
            'Status',
        ];
    }

    public function map($log): array
    {
        return [
            $log->created_at->format('M d, Y h:i A'),
            $log->user ? $log->user->name : 'System',
            ucfirst($log->action),
            $log->description,
            ucfirst($log->status),
        ];
    }
}
