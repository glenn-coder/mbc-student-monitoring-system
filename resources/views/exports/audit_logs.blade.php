<table style="width: 100%; border: none;">
    <tr>
        <td colspan="5" style="font-size: 16px; font-weight: bold; text-align: center;">
            Audit Logs Report
        </td>
    </tr>
    <tr>
        <td colspan="5">Generated on: {{ now()->format('Y-m-d H:i') }}</td>
    </tr>
    <tr>
        <td colspan="5">Total Logs: {{ $metrics['total_logs'] ?? 0 }}</td>
    </tr>
    
    <!-- Summary Metrics -->
    <tr>
        <td colspan="5" style="font-weight: bold; padding-top: 10px;">Summary by Action</td>
    </tr>
    @if(isset($metrics['by_action']))
        @foreach($metrics['by_action'] as $action => $count)
        <tr>
            <td colspan="4">{{ ucfirst($action) }}</td>
            <td>{{ $count }}</td>
        </tr>
        @endforeach
    @endif

    <tr>
        <td colspan="5" style="font-weight: bold; padding-top: 10px;">Summary by Status</td>
    </tr>
    @if(isset($metrics['by_status']))
        @foreach($metrics['by_status'] as $status => $count)
        <tr>
            <td colspan="4">{{ ucfirst($status) }}</td>
            <td>{{ $count }}</td>
        </tr>
        @endforeach
    @endif
    
    <tr><td colspan="5"></td></tr>
    
    <tr>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">Date / Time</th>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">User</th>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">Action</th>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">Description</th>
        <th style="font-weight: bold; border-bottom: 1px solid #000;">Status</th>
    </tr>
    @foreach($logs as $log)
    <tr>
        <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
        <td>{{ $log->user ? $log->user->name : 'System' }}</td>
        <td>{{ ucfirst($log->action) }}</td>
        <td>{{ $log->description }}</td>
        <td>{{ ucfirst($log->status) }}</td>
    </tr>
    @endforeach
</table>
