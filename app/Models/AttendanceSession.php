<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AttendanceSession extends Model
{
    protected $fillable = [
        'schedule_id',
        'session_date',
        'opened_at',
        'closed_at',
        'status',
    ];

    protected $casts = [
        'session_date' => 'date',
        'opened_at'   => 'datetime',
        'closed_at'   => 'datetime',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function records()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    /**
     * Determine if the session window is currently open for scanning.
     * Returns true when now is between start_time and end_time of the schedule.
     */
    public function isOpen(): bool
    {
        if ($this->status === 'closed') {
            return false;
        }

        $schedule = $this->schedule;
        $now = Carbon::now('Asia/Manila');
        $date = $this->session_date->format('Y-m-d');

        $start = Carbon::parse("{$date} {$schedule->start_time}", 'Asia/Manila');
        $end   = Carbon::parse("{$date} {$schedule->end_time}", 'Asia/Manila');

        return $now->between($start, $end);
    }

    /**
     * Whether the session has ended (end_time has passed).
     */
    public function hasEnded(): bool
    {
        $schedule = $this->schedule;
        $now = Carbon::now('Asia/Manila');
        $date = $this->session_date->format('Y-m-d');
        $end = Carbon::parse("{$date} {$schedule->end_time}", 'Asia/Manila');

        return $now->greaterThanOrEqualTo($end);
    }
}
