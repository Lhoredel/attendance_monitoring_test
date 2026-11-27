@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Administrator Dashboard')

@section('content')

<h4 class="fw-bold mb-4">Welcome, Administrator!</h4>

<div class="row g-4">

    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="text-muted">Total Students</div>
            <h2 class="fw-bold text-primary">
                {{ $totalStudents }}
            </h2>
            <i class="bi bi-people fs-2 text-primary"></i>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="text-muted">Total Teachers</div>
            <h2 class="fw-bold text-success">
                {{ $totalTeachers }}
            </h2>
            <i class="bi bi-person-badge fs-2 text-success"></i>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="text-muted">Total Classes</div>
            <h2 class="fw-bold text-warning">
                {{ $totalClasses }}
            </h2>
            <i class="bi bi-building fs-2 text-warning"></i>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="text-muted">Attendance Today</div>
            <h2 class="fw-bold text-info">
                {{ $todayAttendance }}
            </h2>
            <i class="bi bi-calendar-check fs-2 text-info"></i>
        </div>
    </div>

</div>

<div class="card stat-card mt-4 p-4">

    <h5>System Overview</h5>

    <p class="text-muted">
        Welcome to the Attendance Monitoring System.
        Use the sidebar to manage students, classes,
        attendance records, and reports.
    </p>

    <a href="{{ route('users.index') }}" class="btn btn-primary">
        Manage Users
    </a>

</div>

@endsection
