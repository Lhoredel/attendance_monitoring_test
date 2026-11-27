@extends('layouts.app')

@section('title', 'Classes')
@section('page-title', 'Class Management')

@section('content')

<div class="d-flex justify-content-between mb-4">

    <h4>Classes and Sections</h4>

    <a href="{{ route('admin.classes.create') }}"
       class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Class
    </a>

</div>

<div class="card stat-card p-3">

    <table class="table table-hover">

        <thead>
            <tr>
                <th>Class Name</th>
                <th>Grade Level</th>
                <th>Section</th>
                <th>Teacher</th>
                <th>Students</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

            @forelse($classes as $class)

                <tr>
                    <td>{{ $class->class_name }}</td>
                    <td>{{ $class->grade_level }}</td>
                    <td>{{ $class->section }}</td>
                    <td>{{ $class->teacher?->name ?? 'Unassigned' }}</td>
                    <td>{{ $class->students_count }}</td>

                    <td>
                        <a href="{{ route('admin.classes.show', $class) }}"
                           class="btn btn-info btn-sm">View</a>

                        <a href="{{ route('admin.classes.edit', $class) }}"
                           class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('admin.classes.destroy', $class) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Delete this class?')">

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
                        No classes available.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

    {{ $classes->links() }}

</div>

@endsection
