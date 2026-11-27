@extends('layouts.app')

@section('title', 'Add Class')
@section('page-title', 'Create Class')

@section('content')

<div class="card stat-card p-4">

    <h4 class="mb-4">Class Information</h4>

    <form action="{{ route('admin.classes.store') }}" method="POST">

        @csrf

        <div class="mb-3">
            <label class="form-label">Class Name</label>

            <input type="text"
                   name="class_name"
                   class="form-control"
                   placeholder="Example: Grade 3 - Section A"
                   value="{{ old('class_name') }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Grade Level</label>

            <select name="grade_level" class="form-select" required>

                <option value="">Select Grade Level</option>

                @foreach(range(1, 12) as $grade)
                    <option value="Grade {{ $grade }}">
                        Grade {{ $grade }}
                    </option>
                @endforeach

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Section</label>

            <input type="text"
                   name="section"
                   class="form-control"
                   placeholder="Example: Section A"
                   value="{{ old('section') }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Assign Teacher</label>

            <select name="teacher_id" class="form-select">

                <option value="">Select Teacher</option>

                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">
                        {{ $teacher->name }}
                    </option>
                @endforeach

            </select>
        </div>

        <button class="btn btn-primary">Save Class</button>

        <a href="{{ route('admin.classes.index') }}"
           class="btn btn-secondary">Cancel</a>

    </form>

</div>

@endsection
