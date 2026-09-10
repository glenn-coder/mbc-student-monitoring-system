<?php

namespace App\Console\Commands;

use App\Services\AttendanceService;
use Illuminate\Console\Command;

class MarkAbsentStudents extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'attendance:mark-absent';

    /**
     * The console command description.
     */
    protected $description = 'Mark enrolled students as Absent for attendance sessions whose end time has passed.';

    public function __construct(protected AttendanceService $attendanceService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $count = $this->attendanceService->markAbsentForEndedSessions();

        $this->info("Marked {$count} student(s) as Absent for ended sessions.");

        return Command::SUCCESS;
    }
}
