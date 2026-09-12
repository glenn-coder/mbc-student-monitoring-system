<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Instructor;
use App\Models\InstructorAssignment;
use App\Models\Subject;
use App\Models\AttendanceSession;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $instructor = Instructor::where('user_id', Auth::id())->first();
        $subjects = collect();
        
        if ($instructor) {
            $perPage = $request->input('per_page', 5);
            
            $query = InstructorAssignment::where('instructor_id', $instructor->id)
                ->with(['subject', 'course', 'students']);
                
            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('subject', function($q) use ($search) {
                        $q->where('subject_code', 'like', "%{$search}%")
                          ->orWhere('subject_name', 'like', "%{$search}%");
                    })->orWhereHas('course', function($q) use ($search) {
                        $q->where('code', 'like', "%{$search}%")
                          ->orWhere('name', 'like', "%{$search}%");
                    })->orWhere('year', 'like', "%{$search}%")
                      ->orWhere('semester', 'like', "%{$search}%");
                });
            }
            
            // Subject filter
            if ($request->filled('subject_id')) {
                $query->where('subject_id', $request->subject_id);
            }
            
            // Year filter
            if ($request->filled('year')) {
                $query->where('year', $request->year);
            }
            
            $assignments = $query->paginate($perPage);
            
            // Get unique subjects assigned to this instructor for the filter dropdown
            $subjectIds = InstructorAssignment::where('instructor_id', $instructor->id)
                ->pluck('subject_id')
                ->unique();
            $subjects = Subject::whereIn('id', $subjectIds)->get();
            
        } else {
            // Fallback if no instructor profile found
            $assignments = InstructorAssignment::where('id', -1)->paginate(5);
        }
        
        return view('instructor.classes.index', compact('assignments', 'subjects'));
    }

    public function show(int $id)
    {
        $instructor = Instructor::where('user_id', Auth::id())->first();
        if (!$instructor) {
            abort(403);
        }

        $assignment = InstructorAssignment::where('id', $id)
            ->where('instructor_id', $instructor->id)
            ->with(['subject', 'course', 'students.course', 'schedules'])
            ->firstOrFail();

        // Load today's open attendance session for each schedule
        $today = Carbon::now('Asia/Manila')->toDateString();
        foreach ($assignment->schedules as $schedule) {
            $schedule->setRelation(
                'todaySession',
                AttendanceSession::where('schedule_id', $schedule->id)
                    ->where('session_date', $today)
                    ->first()
            );
        }

        return view('instructor.classes.show', compact('assignment'));
    }
}

