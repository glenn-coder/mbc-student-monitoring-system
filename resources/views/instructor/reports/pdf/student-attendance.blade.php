<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Attendance Report</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #222; }

        /* ── Header ── */
        .header-container { width: 100%; border-bottom: 2px solid #000080; padding-bottom: 12px; margin-bottom: 20px; }
        table.header-table { width: 100%; border: none; margin-bottom: 0; }
        table.header-table td { border: none; padding: 0; background-color: transparent; vertical-align: middle; }
        .logo-img  { height: 35px; vertical-align: middle; margin-right: 12px; }
        .title-text { font-size: 15px; color: #555; padding-left: 12px; border-left: 1px solid #ccc; }
        .date-text  { text-align: right; font-size: 11px; font-weight: bold; color: #333; }
        .filter-text { text-align: right; font-size: 10px; color: #777; margin-top: 4px; }

        /* ── Summary cards row ── */
        table.summary-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.summary-table td { border: 1px solid #ddd; padding: 8px 12px; text-align: center; width: 20%; }
        .summary-label { font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: .05em; color: #555; }
        .summary-value { font-size: 20px; font-weight: bold; color: #111; margin-top: 4px; }

        /* ── Data table ── */
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th,
        table.data-table td  { border: 1px solid #ddd; padding: 5px 7px; text-align: left; font-size: 10px; }
        table.data-table thead th { background-color: #2563EB; color: #fff; font-weight: bold; }
        table.data-table tbody tr:nth-child(even) { background-color: #f0f4ff; }

        .badge-present { color: #166534; font-weight: bold; }
        .badge-late    { color: #92400e; font-weight: bold; }
        .badge-absent  { color: #991b1b; font-weight: bold; }

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
                    <span class="title-text">Student Attendance Report</span>
                </td>
                <td style="width: 40%;">
                    <div class="date-text">Generated: {{ now()->format('F d, Y H:i') }}</div>
                    <div class="date-text" style="font-weight:normal; font-size:10px; color:#555;">
                        Instructor: {{ $instructor->full_name }}
                    </div>
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
                <div class="summary-label">Total Records</div>
                <div class="summary-value">{{ number_format($summary['totalRecords']) }}</div>
            </td>
            <td>
                <div class="summary-label">Present</div>
                <div class="summary-value">{{ number_format($summary['totalPresent']) }}</div>
            </td>
            <td>
                <div class="summary-label">Late</div>
                <div class="summary-value">{{ number_format($summary['totalLate']) }}</div>
            </td>
            <td>
                <div class="summary-label">Absent</div>
                <div class="summary-value">{{ number_format($summary['totalAbsent']) }}</div>
            </td>
            <td>
                <div class="summary-label">Attendance Rate</div>
                <div class="summary-value">
                    {{ $summary['attendanceRate'] !== null ? $summary['attendanceRate'] . '%' : '—' }}
                </div>
            </td>
        </tr>
    </table>

    {{-- ── Record detail table ── --}}
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Student Number</th>
                <th>Name</th>
                <th>Course / Year</th>
                <th>Subject</th>
                <th>Timestamp</th>
                <th>Method</th>
                <th style="text-align:center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                @php
                    $session  = $record->session;
                    $schedule = $session?->schedule;
                    $subject  = $schedule?->instructorAssignment?->subject;
                    $course   = $schedule?->instructorAssignment?->course;
                    $student  = $record->student;
                @endphp
                <tr>
                    <td>{{ $session?->session_date?->format('M d, Y') ?? '—' }}</td>
                    <td>{{ $student?->student_number ?? '—' }}</td>
                    <td>
                        {{ $student ? ($student->last_name . ', ' . $student->first_name . ($student->middle_name ? ' ' . mb_substr($student->middle_name, 0, 1) . '.' : '')) : '—' }}
                    </td>
                    <td>
                        {{ $course?->code ?? 'N/A' }}
                        @if($student?->year) — {{ $student->year }} @endif
                    </td>
                    <td>{{ $subject?->subject_name ?? 'N/A' }}</td>
                    <td>{{ $record->scanned_at ? $record->scanned_at->format('g:i A') : '—' }}</td>
                    <td>{{ ucfirst($record->attendance_method ?? 'system') }}</td>
                    <td style="text-align:center;">
                        @if($record->status === 'present')
                            <span class="badge-present">Present</span>
                        @elseif($record->status === 'late')
                            <span class="badge-late">Late</span>
                        @else
                            <span class="badge-absent">Absent</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; color:#999;">No attendance records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Student Monitoring System — Metro Business College
    </div>
</body>
</html>
