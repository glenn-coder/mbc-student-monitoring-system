<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentAttendanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private Collection $records) {}

    public function collection(): Collection
    {
        return $this->records;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Student Number',
            'Name',
            'Course',
            'Year',
            'Subject',
            'Timestamp',
            'Method',
            'Status',
        ];
    }

    /**
     * @param  \App\Models\AttendanceRecord  $record
     */
    public function map($record): array
    {
        $session  = $record->session;
        $schedule = $session?->schedule;
        $subject  = $schedule?->instructorAssignment?->subject;
        $course   = $schedule?->instructorAssignment?->course;
        $student  = $record->student;

        $name = $student
            ? trim(
                $student->last_name . ', ' . $student->first_name
                . ($student->middle_name ? ' ' . mb_substr($student->middle_name, 0, 1) . '.' : '')
              )
            : '—';

        return [
            $session?->session_date?->format('M d, Y')          ?? '—',
            $student?->student_number                             ?? '—',
            $name,
            $course?->code                                        ?? 'N/A',
            $student?->year                                       ?? 'N/A',
            $subject?->subject_name                               ?? 'N/A',
            $record->scanned_at ? $record->scanned_at->format('g:i A') : '—',
            ucfirst($record->attendance_method ?? 'system'),
            ucfirst($record->status),
        ];
    }
}
