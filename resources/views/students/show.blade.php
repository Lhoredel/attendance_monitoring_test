@extends('layouts.app')

@section('title', 'Student Details')
@section('page-title', 'Student Details')

@section('content')

<div class="card stat-card p-4">

    <h4>{{ $student->first_name }} {{ $student->last_name }}</h4>

    <hr>

    <p><strong>Student Number:</strong> {{ $student->student_number }}</p>

    <p><strong>Email:</strong> {{ $student->email ?? 'N/A' }}</p>

    <p><strong>Gender:</strong> {{ $student->gender ?? 'N/A' }}</p>

    <p><strong>Grade:</strong> {{ $student->grade_level }}</p>

    <p><strong>Section:</strong> {{ $student->section }}</p>

    <h5 class="mt-4">Attendance Summary</h5>

    <p>Present: {{ $student->attendances->where('status', 'present')->count() }}</p>

    <p>Absent: {{ $student->attendances->where('status', 'absent')->count() }}</p>

    <p>Late: {{ $student->attendances->where('status', 'late')->count() }}</p>

    <a href="{{ route('admin.students.index') }}"
       class="btn btn-secondary mt-3">
        Back
    </a>

</div>

@endsection
