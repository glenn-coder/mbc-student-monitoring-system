<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\InstructorAssignment;

class TimetableController extends Controller
{
    public function index()
    {
        $assignments = InstructorAssignment::with(['instructor', 'subject', 'course'])->get();

        $schedules = Schedule::with(['instructorAssignment.instructor', 'instructorAssignment.subject', 'instructorAssignment.course'])
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return view('admin.timetable.index', compact('assignments', 'schedules', 'days'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'instructor_assignment_id' => 'required|exists:instructor_assignments,id',
            'days' => 'required|array',
            'days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'status' => 'required|in:active,inactive',
        ]);

        foreach ($request->days as $day) {
            Schedule::create([
                'instructor_assignment_id' => $request->instructor_assignment_id,
                'day_of_week' => $day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => $request->status,
            ]);
        }

        return back()->with('success', 'Schedule added successfully.');
    }

    public function update(Request $request, Schedule $timetable)
    {
        $request->validate([
            'instructor_assignment_id' => 'required|exists:instructor_assignments,id',
            'days' => 'required|array',
            'days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'status' => 'required|in:active,inactive',
        ]);

        $days = $request->days;

        $timetable->update([
            'instructor_assignment_id' => $request->instructor_assignment_id,
            'day_of_week' => array_shift($days),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $request->status,
        ]);

        foreach ($days as $day) {
            Schedule::create([
                'instructor_assignment_id' => $request->instructor_assignment_id,
                'day_of_week' => $day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => $request->status,
            ]);
        }

        return back()->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $timetable)
    {
        $timetable->delete();
        return back()->with('success', 'Schedule removed successfully.');
    }
}
