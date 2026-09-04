<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentsExport implements FromView, ShouldAutoSize
{
    /** @var \Illuminate\Support\Collection|array */
    protected $students;

    /** @var array */
    protected $metrics;

    /** @var string */
    protected $type;

    /**
     * @param \Illuminate\Support\Collection|array $students
     * @param array $metrics
     * @param string $type
     */
    public function __construct($students, $metrics = [], $type = 'active')
    {
        $this->students = $students;
        $this->metrics = $metrics;
        $this->type = $type;
    }

    public function view(): View
    {
        return view('exports.students', [
            'students' => $this->students,
            'metrics' => $this->metrics,
            'type' => $this->type,
        ]);
    }
}
