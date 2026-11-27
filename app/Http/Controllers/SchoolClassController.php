<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolClassController extends Controller
{
    public function index(): View
    {
        $classes = SchoolClass::with('teacher')
            ->withCount('students')
            ->latest()
            ->paginate(10);

        return view('classes.index', compact('classes'));
    }

    public function create(): View
    {
        $teachers = User::where('role', 'teacher')->get();

        return view('classes.create', compact('teachers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'class_name' => 'required|string|max:100',
            'grade_level' => 'required|string|max:50',
            'section' => 'required|string|max:100',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        if (!empty($data['teacher_id']) &&
            User::whereKey($data['teacher_id'])
                ->where('role', 'teacher')->doesntExist()) {
            return back()->withErrors([
                'teacher_id' => 'Please select a teacher account.',
            ])->withInput();
        }

        SchoolClass::create($data);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(SchoolClass $class): View
    {
        $class->load('teacher', 'students');

        $availableStudents = Student::whereDoesntHave(
            'schoolClasses',
            function ($query) use ($class) {
                $query->where('school_classes.id', $class->id);
            }
        )->get();

        return view('classes.show', compact(
            'class',
            'availableStudents'
        ));
    }

    public function edit(SchoolClass $class): View
    {
        $teachers = User::where('role', 'teacher')->get();

        return view('classes.edit', compact('class', 'teachers'));
    }

    public function update(
        Request $request,
        SchoolClass $class
    ): RedirectResponse {

        $data = $request->validate([
            'class_name' => 'required|string|max:100',
            'grade_level' => 'required|string|max:50',
            'section' => 'required|string|max:100',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        if (!empty($data['teacher_id']) &&
            User::whereKey($data['teacher_id'])
                ->where('role', 'teacher')->doesntExist()) {
            return back()->withErrors([
                'teacher_id' => 'Please select a teacher account.',
            ])->withInput();
        }

        $class->update($data);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $class): RedirectResponse
    {
        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class deleted successfully.');
    }

    public function addStudent(
        Request $request,
        SchoolClass $class
    ): RedirectResponse {

        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $class->students()->syncWithoutDetaching([
            $data['student_id']
        ]);

        return back()->with('success', 'Student assigned to class.');
    }

    public function removeStudent(
        SchoolClass $class,
        Student $student
    ): RedirectResponse {

        $class->students()->detach($student->id);

        return back()->with('success', 'Student removed from class.');
    }
}
