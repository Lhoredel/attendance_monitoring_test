@extends('layouts.app')

@section('title', 'Teacher Dashboard')
@section('page-title', 'Teacher Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="card stat-card p-3">
            <div class="text-muted">Attendance Records Today</div>
            <h2 class="fw-bold text-primary mb-0">{{ $todayAttendance }}</h2>
        </div>
    </div>
</div>

<section class="card stat-card p-4 mb-4">
    <h2 class="h5 mb-3">My Profile</h2>
    <p class="mb-1"><strong>Name:</strong> {{ $teacher->name }}</p>
    <p class="mb-1"><strong>Email:</strong> {{ $teacher->email }}</p>
    <p class="mb-1"><strong>Role:</strong> {{ ucfirst($teacher->role) }}</p>
    <p class="mb-0"><strong>Status:</strong> {{ $teacher->status }}</p>
</section>

<section class="card stat-card p-4">
    <h2 class="h5 mb-3">My Classes</h2>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Class</th><th>Grade</th><th>Section</th><th>Students</th></tr></thead>
            <tbody>
                @forelse($classes as $class)
                    <tr>
                        <td>{{ $class->class_name }}</td>
                        <td>{{ $class->grade_level }}</td>
                        <td>{{ $class->section }}</td>
                        <td>{{ $class->students_count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">No classes are assigned to you.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
