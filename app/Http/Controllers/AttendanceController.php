<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $classes = SchoolClass::query()
            ->when($user->role === 'teacher', function ($query) use ($user) {
                $query->where('teacher_id', $user->id);
            })
            ->orderBy('class_name')
            ->get();

        $selectedClass = null;
        $students = collect();

        $classId = $request->input('school_class_id');
        $date = $request->input('attendance_date', today()->toDateString());

        if ($classId) {
            $selectedClass = SchoolClass::with('students')
                ->when($user->role === 'teacher', function ($query) use ($user) {
                    $query->where('teacher_id', $user->id);
                })
                ->findOrFail($classId);

            $students = $selectedClass->students;

            $records = Attendance::where(
                'school_class_id',
                $selectedClass->id
            )->whereDate('attendance_date', $date)
                ->get()
                ->keyBy('student_id');

            $students->each(function ($student) use ($records) {
                $student->attendance_status =
                    $records->get($student->id)?->status ?? 'present';

                $student->attendance_remarks =
                    $records->get($student->id)?->remarks;
            });
        }

        $history = Attendance::with(['student', 'schoolClass'])
            ->when($user->role === 'teacher', function ($query) use ($user) {
                $query->where('teacher_id', $user->id);
            })
            ->when($classId, function ($query) use ($classId) {
                $query->where('school_class_id', $classId);
            })
            ->whereDate('attendance_date', $date)
            ->latest()
            ->get();

        return view('attendance.index', compact(
            'classes',
            'selectedClass',
            'students',
            'history',
            'date'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'attendance_date' => 'required|date|before_or_equal:today',
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:present,absent,late',
            'attendance.*.remarks' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        $class = SchoolClass::with('students')
            ->when($user->role === 'teacher', function ($query) use ($user) {
                $query->where('teacher_id', $user->id);
            })
            ->findOrFail($data['school_class_id']);

        $validStudentIds = $class->students
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();

        foreach (array_keys($data['attendance']) as $studentId) {
            if (!in_array((string) $studentId, $validStudentIds, true)) {
                abort(422, 'An invalid student was submitted.');
            }
        }

        DB::transaction(function () use ($data, $user, $class) {

            foreach ($data['attendance'] as $studentId => $record) {

                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'school_class_id' => $class->id,
                        'attendance_date' => $data['attendance_date'],
                    ],
                    [
                        'teacher_id' => $user->id,
                        'status' => $record['status'],
                        'remarks' => $record['remarks'] ?? null,
                    ]
                );
            }
        });

        $route = $user->role === 'admin'
            ? 'admin.attendance.index'
            : 'teacher.attendance.index';

        return redirect()->route($route, [
            'school_class_id' => $class->id,
            'attendance_date' => $data['attendance_date'],
        ])->with('success', 'Attendance saved successfully.');
    }

    public function reports(Request $request): View
    {
        $user = Auth::user();

        $query = Attendance::with(['student', 'schoolClass'])
            ->when($user->role === 'teacher', function ($query) use ($user) {
                $query->where('teacher_id', $user->id);
            });

        if ($request->filled('month')) {
            $month = $request->input('month');

            if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month)) {
                abort(422, 'Invalid month format.');
            }

            $query->whereYear('attendance_date', substr($month, 0, 4))
                ->whereMonth('attendance_date', substr($month, 5, 2));
        }

        if ($request->filled('school_class_id')) {
            $class = SchoolClass::when(
                $user->role === 'teacher',
                fn ($q) => $q->where('teacher_id', $user->id)
            )->findOrFail($request->school_class_id);

            $query->where('school_class_id', $class->id);
        }

        $records = $query->latest('attendance_date')->paginate(20);

        return view('attendance.reports', compact('records'));
    }
}
