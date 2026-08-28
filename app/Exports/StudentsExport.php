<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $students;

    public function __construct(Collection $students)
    {
        $this->students = $students;
    }

    public function collection()
    {
        return $this->students;
    }

    public function headings(): array
    {
        return [
            'Student Number',
            'Last Name',
            'First Name',
            'Sex',
            'Course',
            'Year',
        ];
    }

    public function map($student): array
    {
        return [
            $student->student_number,
            $student->last_name,
            $student->first_name,
            $student->sex,
            $student->course,
            $student->year,
        ];
    }
}
