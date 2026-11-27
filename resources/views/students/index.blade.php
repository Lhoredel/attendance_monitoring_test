@extends('layouts.app')

@section('title', 'Students')
@section('page-title', 'Student Management')

@section('content')

<div class="d-flex justify-content-between mb-4">

    <h4>Students List</h4>

    <a href="{{ route('admin.students.create') }}"
       class="btn btn-primary">
        <i class="bi bi-plus-circle"></i>
        Add Student
    </a>

</div>

<div class="card stat-card p-3">

    <div class="table-responsive">

        <table class="table table-hover align-middle">

            <thead class="table-light">
                <tr>
                    <th>Student Number</th>
                    <th>Full Name</th>
                    <th>Grade Level</th>
                    <th>Section</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($students as $student)

                    <tr>

                        <td>{{ $student->student_number }}</td>

                        <td>
                            {{ $student->first_name }}
                            {{ $student->last_name }}
                        </td>

                        <td>{{ $student->grade_level }}</td>

                        <td>{{ $student->section }}</td>

                        <td>{{ $student->email ?? 'N/A' }}</td>

                        <td>

                            <a href="{{ route('admin.students.show', $student) }}"
                               class="btn btn-info btn-sm">
                                View
                            </a>

                            <a href="{{ route('admin.students.edit', $student) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('admin.students.destroy', $student) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Delete this student?')">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm">
                                    Delete
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center">
                            No students found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{ $students->links() }}

</div>

@endsection
