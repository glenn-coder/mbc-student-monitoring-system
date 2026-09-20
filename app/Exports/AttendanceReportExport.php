<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AttendanceReportExport implements FromView, ShouldAutoSize
{
    protected Collection $students;
    protected array $summary;

    /**
     * @param \Illuminate\Support\Collection $students  — full (un-paginated) collection with stat_* attributes attached
     * @param array $summary  — keys: totalStudents, totalPresent, totalLate, totalAbsent, totalAttendanceRate
     */
    public function __construct(Collection $students, array $summary)
    {
        $this->students = $students;
        $this->summary  = $summary;
    }

    public function view(): View
    {
        return view('exports.attendance_report', [
            'students' => $this->students,
            'summary'  => $this->summary,
        ]);
    }
}
