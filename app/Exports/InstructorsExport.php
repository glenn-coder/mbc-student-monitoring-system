<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class InstructorsExport implements FromCollection, WithHeadings, WithMapping
{
    protected Collection $instructors;

    public function __construct(Collection $instructors)
    {
        $this->instructors = $instructors;
    }

    public function collection(): Collection
    {
        return $this->instructors;
    }

    public function headings(): array
    {
        return [
            'Instructor Number',
            'Full Name',
            'Specialization',
            'Course',
            'Total Assigned Subjects',
            'Total Handled Students',
        ];
    }

    public function map($instructor): array
    {
        $totalSubjects = $instructor->assignments->count();
        $totalStudents = $instructor->assignments->sum(function ($assignment) {
            return $assignment->students->count();
        });

        return [
            $instructor->instructor_number,
            $instructor->full_name,
            $instructor->major_specialization,
            $instructor->course,
            $totalSubjects,
            $totalStudents,
        ];
    }
}
