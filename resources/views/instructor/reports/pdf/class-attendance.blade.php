<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Class Attendance Summary</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #222; }

        /* ── Header ── */
        .header-container { width: 100%; border-bottom: 2px solid #000080; padding-bottom: 12px; margin-bottom: 20px; }
        table.header-table { width: 100%; border: none; margin-bottom: 0; }
        table.header-table td { border: none; padding: 0; background-color: transparent; vertical-align: middle; }
        .logo-img { height: 35px; vertical-align: middle; margin-right: 12px; }
        .title-text { font-size: 15px; color: #555; padding-left: 12px; border-left: 1px solid #ccc; }
        .date-text { text-align: right; font-size: 11px; font-weight: bold; color: #333; }
        .filter-text { text-align: right; font-size: 10px; color: #777; margin-top: 4px; }

        /* ── Summary cards row ── */
        table.summary-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.summary-table td { border: 1px solid #ddd; padding: 8px 12px; text-align: center; width: 20%; }
        .summary-label { font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: .05em; color: #555; }
        .summary-value { font-size: 22px; font-weight: bold; color: #111; margin-top: 4px; }

        /* ── Data table ── */
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th, table.data-table td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; font-size: 11px; }
        table.data-table thead th { background-color: #2563EB; color: #fff; font-weight: bold; }
        table.data-table tbody tr:nth-child(even) { background-color: #f0f4ff; }

        .footer { text-align: right; font-size: 10px; color: #999; margin-top: 30px; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>

    {{-- ── Header ── --}}
    <div class="header-container">
        <table class="header-table">
            <tr>
                <td style="width: 60%;">
                    <img src="{{ public_path('images/MBC-logo.png') }}" class="logo-img" alt="MBC Logo">
                    <span class="title-text">Class Attendance Summary</span>
                </td>
                <td style="width: 40%;">
                    <div class="date-text">Generated: {{ now()->format('F d, Y H:i') }}</div>
                    @if(!empty($filterLabel))
                        <div class="filter-text">{{ $filterLabel }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- ── Summary metrics ── --}}
    <table class="summary-table">
        <tr>
            <td>
                <div class="summary-label">Total Students</div>
                <div class="summary-value">{{ $summary['totalStudents'] }}</div>
            </td>
            <td>
                <div class="summary-label">Total Present</div>
                <div class="summary-value">{{ $summary['totalPresent'] }}</div>
            </td>
            <td>
                <div class="summary-label">Total Late</div>
                <div class="summary-value">{{ $summary['totalLate'] }}</div>
            </td>
            <td>
                <div class="summary-label">Total Absent</div>
                <div class="summary-value">{{ $summary['totalAbsent'] }}</div>
            </td>
            <td>
                <div class="summary-label">Attendance Rate</div>
                <div class="summary-value">
                    {{ $summary['totalAttendanceRate'] !== null ? $summary['totalAttendanceRate'] . '%' : '—' }}
                </div>
            </td>
        </tr>
    </table>

    {{-- ── Student detail table ── --}}
    <table class="data-table">
        <thead>
            <tr>
                <th>Student Number</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Course</th>
                <th style="text-align:center;">Present</th>
                <th style="text-align:center;">Late</th>
                <th style="text-align:center;">Absent</th>
                <th style="text-align:center;">Attendance Rate</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td>{{ $student->student_number }}</td>
                    <td>{{ $student->last_name }}</td>
                    <td>{{ $student->first_name }}</td>
                    <td>{{ $student->middle_name ?? '' }}</td>
                    <td>{{ $student->course->code ?? 'N/A' }}</td>
                    <td style="text-align:center;">{{ $student->stat_present }}</td>
                    <td style="text-align:center;">{{ $student->stat_late }}</td>
                    <td style="text-align:center;">{{ $student->stat_absent }}</td>
                    <td style="text-align:center;">
                        {{ $student->stat_rate !== null ? $student->stat_rate . '%' : '—' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center; color:#999;">No attendance records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Student Monitoring System — Metro Business College
    </div>
</body>
</html>
