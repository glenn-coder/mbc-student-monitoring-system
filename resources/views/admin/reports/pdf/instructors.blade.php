<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Instructor Workload Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        
        /* New Header Design */
        .header-container { width: 100%; border-bottom: 2px solid #000080; padding-bottom: 15px; margin-bottom: 25px; }
        table.header-table { width: 100%; border: none; margin-bottom: 0; }
        table.header-table th, table.header-table td { border: none; padding: 0; background-color: transparent; text-align: left; vertical-align: middle; }
        
        .logo-img { height: 35px; vertical-align: middle; margin-right: 15px; }
        .title-text { font-size: 16px; color: #555; padding-left: 15px; border-left: 1px solid #ccc; vertical-align: middle; }
        .date-text { text-align: right; font-size: 11px; font-weight: bold; color: #333; }
        .count-text { text-align: right; font-size: 12px; font-weight: normal; color: #555; margin-top: 5px; }
        
        /* Data Table Design */
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th, table.data-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table.data-table th { background-color: #f4f4f4; }
        .text-center { text-align: center; }
        .footer { text-align: right; font-size: 10px; color: #777; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header-container">
        <table class="header-table">
            <tr>
                <td style="width: 50%;">
                    <!-- Use public_path for DOMPDF to resolve local images -->
                    <img src="{{ public_path('images/MBC-logo.png') }}" class="logo-img" alt="MBC Logo">
                    <span class="title-text">Instructor Workload Report</span>
                </td>
                <td style="width: 50%;">
                    <div class="date-text">Generated on: {{ now()->format('Y-m-d H:i') }}</div>
                    <div class="count-text">Total Active Instructors: {{ $instructors->count() }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Instructor Number</th>
                <th>Name</th>
                <th>Specialization</th>
                <th>Course</th>
                <th class="text-center">Assigned Subjects</th>
                <th class="text-center">Total Students</th>
            </tr>
        </thead>
        <tbody>
            @foreach($instructors as $instructor)
                <tr>
                    <td>{{ $instructor->instructor_number }}</td>
                    <td>{{ $instructor->full_name }}</td>
                    <td>{{ $instructor->major_specialization }}</td>
                    <td>{{ $instructor->course }}</td>
                    <td class="text-center">{{ $instructor->assignments->count() }}</td>
                    <td class="text-center">{{ $instructor->assignments->sum(function($a) { return $a->students->count(); }) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Student Monitoring System - Metro Business College
    </div>
</body>
</html>
