<table style="width: 100%; border: none;">
    <tr>
        <td colspan="6" style="font-size: 16px; font-weight: bold; text-align: center;">
            MBC Instructor Workload
        </td>
    </tr>
    <tr>
        <td colspan="6">Generated on: {{ now()->format('Y-m-d H:i') }}</td>
    </tr>
    <tr>
        <td colspan="6">Total Instructors: {{ $metrics['total_instructors'] ?? 0 }}</td>
    </tr>
    
    <!-- Summary Metrics -->
    <tr>
        <td colspan="6" style="font-weight: bold; padding-top: 10px;">Summary by Specialization</td>
    </tr>
    @if(isset($metrics['by_specialization']))
        @foreach($metrics['by_specialization'] as $spec => $count)
        <tr>
            <td colspan="5">{{ $spec ?? 'N/A' }}</td>
            <td>{{ $count }}</td>
        </tr>
        @endforeach
    @endif
    
    <tr><td colspan="6"></td></tr>
    
    <tr>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">Instructor Number</th>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">Last Name</th>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">First Name</th>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">Specialization</th>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">Email</th>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">Status</th>
    </tr>
    @foreach($instructors as $instructor)
    <tr>
        <td>{{ $instructor->instructor_number }}</td>
        <td>{{ $instructor->last_name }}</td>
        <td>{{ $instructor->first_name }}</td>
        <td>{{ $instructor->major_specialization }}</td>
        <td>{{ $instructor->email ?? 'N/A' }}</td>
        <td>{{ ucfirst($instructor->user->status ?? 'unknown') }}</td>
    </tr>
    @endforeach
</table>
