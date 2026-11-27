@extends('layouts.app')

@section('title', 'Class Details')
@section('page-title', 'Class Details')

@section('content')

<div class="card stat-card p-4 mb-4">

    <h4>{{ $class->class_name }}</h4>

    <p><strong>Grade:</strong> {{ $class->grade_level }}</p>

    <p><strong>Section:</strong> {{ $class->section }}</p>

    <p><strong>Teacher:</strong> {{ $class->teacher?->name ?? 'Unassigned' }}</p>

    <hr>

    <h5>Assign Student</h5>

    <form action="{{ route('admin.classes.students.add', $class) }}"
          method="POST"
          class="d-flex gap-2">

        @csrf

        <select name="student_id" class="form-select" required>

            <option value="">Select Student</option>

            @foreach($availableStudents as $student)

                <option value="{{ $student->id }}">
                    {{ $student->student_number }} -
                    {{ $student->first_name }} {{ $student->last_name }}
                </option>

            @endforeach

        </select>

        <button class="btn btn-primary">Assign</button>

    </form>

</div>

<div class="card stat-card p-4">

    <h5>Enrolled Students</h5>

    <table class="table">

        <thead>
            <tr>
                <th>Student Number</th>
                <th>Student Name</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

            @forelse($class->students as $student)

                <tr>

                    <td>{{ $student->student_number }}</td>

                    <td>
                        {{ $student->first_name }}
                        {{ $student->last_name }}
                    </td>

                    <td>

                        <form action="{{ route(
                            'admin.classes.students.remove',
                            [$class, $student]
                        ) }}"
                              method="POST"
                              onsubmit="return confirm('Remove student from this class?')">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm">
                                Remove
                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="3" class="text-center">
                        No students assigned.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

    <a href="{{ route('admin.classes.index') }}"
       class="btn btn-secondary">Back</a>

</div>

@endsection
