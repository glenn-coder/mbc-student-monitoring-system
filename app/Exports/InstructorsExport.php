<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InstructorsExport implements FromView, ShouldAutoSize
{
    protected $instructors;
    protected $metrics;

    public function __construct($instructors, $metrics = [])
    {
        $this->instructors = $instructors;
        $this->metrics = $metrics;
    }

    public function view(): View
    {
        return view('exports.instructors', [
            'instructors' => $this->instructors,
            'metrics' => $this->metrics,
        ]);
    }
}
