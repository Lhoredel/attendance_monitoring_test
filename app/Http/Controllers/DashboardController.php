<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function admin(): View
    {
        return view('admin.dashboard', [
            'totalStudents' => Student::count(),
            'totalTeachers' => User::where('role', 'teacher')->count(),
            'totalClasses' => SchoolClass::count(),
            'todayAttendance' => Attendance::whereDate(
                'attendance_date',
                today()
            )->count(),
        ]);
    }

    public function teacher(): View
    {
        $teacher = Auth::user();

        $classes = SchoolClass::where(
            'teacher_id',
            $teacher->id
        )->withCount('students')->get();

        $classIds = $classes->pluck('id');

        $todayAttendance = Attendance::whereIn(
            'school_class_id',
            $classIds
        )->whereDate('attendance_date', today())->count();

        return view('teacher.dashboard', compact(
            'teacher',
            'classes',
            'todayAttendance'
        ));
    }
}
