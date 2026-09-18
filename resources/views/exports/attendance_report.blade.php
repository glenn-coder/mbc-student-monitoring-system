<table>
    <thead>
        <tr>
            <th>Summary</th>
            <th></th>
        </tr>
        <tr>
            <th>Total Students</th>
            <th>Total Present</th>
            <th>Total Late</th>
            <th>Total Absent</th>
            <th>Attendance Rate</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $summary['totalStudents'] }}</td>
            <td>{{ $summary['totalPresent'] }}</td>
            <td>{{ $summary['totalLate'] }}</td>
            <td>{{ $summary['totalAbsent'] }}</td>
            <td>{{ $summary['totalAttendanceRate'] !== null ? $summary['totalAttendanceRate'] . '%' : 'N/A' }}</td>
        </tr>
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th>Student Number</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Course</th>
            <th>Present</th>
            <th>Late</th>
            <th>Absent</th>
            <th>Attendance Rate</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $student)
        <tr>
            <td>{{ $student->student_number }}</td>
            <td>{{ $student->last_name }}</td>
            <td>{{ $student->first_name }}</td>
            <td>{{ $student->middle_name ?? '' }}</td>
            <td>{{ $student->course->code ?? 'N/A' }}</td>
            <td>{{ $student->stat_present }}</td>
            <td>{{ $student->stat_late }}</td>
            <td>{{ $student->stat_absent }}</td>
            <td>{{ $student->stat_rate !== null ? $student->stat_rate . '%' : 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
