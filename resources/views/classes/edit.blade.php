@extends('layouts.app')

@section('title', 'Edit Class')
@section('page-title', 'Edit Class')

@section('content')

<div class="card stat-card p-4">

    <form action="{{ route('admin.classes.update', $class) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Class Name</label>

            <input type="text"
                   name="class_name"
                   class="form-control"
                   value="{{ old('class_name', $class->class_name) }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Grade Level</label>

            <input type="text"
                   name="grade_level"
                   class="form-control"
                   value="{{ old('grade_level', $class->grade_level) }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Section</label>

            <input type="text"
                   name="section"
                   class="form-control"
                   value="{{ old('section', $class->section) }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Teacher</label>

            <select name="teacher_id" class="form-select">

                <option value="">Unassigned</option>

                @foreach($teachers as $teacher)

                    <option value="{{ $teacher->id }}"
                        @selected(old('teacher_id', $class->teacher_id) == $teacher->id)>
                        {{ $teacher->name }}
                    </option>

                @endforeach

            </select>
        </div>

        <button class="btn btn-primary">Update Class</button>

        <a href="{{ route('admin.classes.index') }}"
           class="btn btn-secondary">Cancel</a>

    </form>

</div>

@endsection
