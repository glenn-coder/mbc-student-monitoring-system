<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inactive Students Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        
        /* New Header Design */
        .header-container { 
            width: 100%; 
            border-bottom: 2px solid #000080; 
            padding-bottom: 15px; 
            margin-bottom: 25px; 
        }
        table.header-table { 
            width: 100%; 
            border: none; 
            margin-bottom: 0; 
        }
        table.header-table th, table.header-table td { 
            border: none; 
            padding: 0; 
            background-color: transparent; 
            text-align: left; 
            vertical-align: middle; 
        }
        .logo-img { 
            height: 35px; 
            vertical-align: middle; 
            margin-right: 15px; 
        }
        .title-text { 
            font-size: 16px; 
            color: #555; 
            padding-left: 15px; 
            border-left: 1px solid #ccc; 
            vertical-align: middle; 
        }
        .date-text { 
            text-align: right; 
            font-size: 11px; 
            font-weight: bold; 
            color: #333; 
        }
        .count-text { 
            text-align: right; 
            font-size: 12px; 
            font-weight: normal; 
            color: #555; 
            margin-top: 5px; 
        }
        
        /* Data Table Design */
        table.data-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        table.data-table th, table.data-table td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        table.data-table th { 
            background-color: #f4f4f4; 
        }
        .footer { 
            text-align: right; 
            font-size: 10px; color: #777; 
            margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header-container">
        <table class="header-table">
            <tr>
                <td style="width: 50%;">
                    <!-- Use public_path for DOMPDF to resolve local images -->
                    <img src="{{ public_path('images/MBC-logo.png') }}" class="logo-img" alt="MBC Logo">
                    <span class="title-text">Inactive Students Report</span>
                </td>
                <td style="width: 50%;">
                    <div class="date-text">Generated on: {{ now()->format('Y-m-d H:i') }}</div>
                    <div class="count-text">Total Inactive Students: {{ $students->count() }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Student Number</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Sex</th>
                <th>Course</th>
                <th>Year</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->student_number }}</td>
                    <td>{{ $student->last_name }}</td>
                    <td>{{ $student->first_name }}</td>
                    <td>{{ $student->sex }}</td>
                    <td>{{ $student->course->code ?? 'N/A' }}</td>
                    <td>{{ $student->year }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Student Monitoring System - Metro Business College
    </div>
</body>
</html>
