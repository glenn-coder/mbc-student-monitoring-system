<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InstructorsExport implements FromView, ShouldAutoSize
{
    /**
     * @var Collection
     */
    protected $instructors;

    /**
     * @var array
     */
    protected $metrics;

    public function __construct(Collection $instructors, array $metrics = [])
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
