<table style="width: 100%; border: none;">
    <tr>
        <td colspan="8" style="font-size: 16px; font-weight: bold; text-align: center;">
            MBC Students Master List ({{ ucfirst($type) }})
        </td>
    </tr>
    <tr>
        <td colspan="8">Generated on: {{ now()->format('Y-m-d H:i') }}</td>
    </tr>
    <tr>
        <td colspan="8">Total Students: {{ $metrics['total_'.$type] }}</td>
    </tr>
    
    <!-- Summary Metrics -->
    <tr>
        <td colspan="8" style="font-weight: bold; padding-top: 10px;">Summary by Course</td>
    </tr>
    @foreach($metrics['by_course'] as $course => $count)
    <tr>
        <td colspan="6">{{ $course }}</td>
        <td>{{ $count }}</td>
    </tr>
    @endforeach

    <tr>
        <td colspan="8" style="font-weight: bold; padding-top: 10px;">Summary by Year</td>
    </tr>
    @foreach($metrics['by_year'] as $year => $count)
    <tr>
        <td colspan="6">{{ $year }}</td>
        <td>{{ $count }}</td>
    </tr>
    @endforeach

    <tr>
        <td colspan="8" style="font-weight: bold; padding-top: 10px;">Summary by Sex</td>
    </tr>
    @foreach($metrics['by_sex'] as $sex => $count)
    <tr>
        <td colspan="6">{{ $sex }}</td>
        <td>{{ $count }}</td>
    </tr>
    @endforeach
    
    <tr><td colspan="8"></td></tr>
    
    <tr>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">Student Number</th>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">Last Name</th>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">First Name</th>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">Middle Name</th>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">Email</th>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">Sex</th>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">Course</th>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">Year</th>
    </tr>
    @foreach($students as $student)
    <tr>
        <td>{{ $student->student_number }}</td>
        <td>{{ $student->last_name }}</td>
        <td>{{ $student->first_name }}</td>
        <td>{{ $student->middle_name ?? '' }}</td>
        <td>{{ $student->email ?? 'N/A' }}</td>
        <td>{{ $student->sex }}</td>
        <td>{{ $student->course->code ?? 'N/A' }}</td>
        <td>{{ $student->year }}</td>
    </tr>
    @endforeach
</table>
