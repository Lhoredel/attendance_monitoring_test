@extends('layouts.app')

@section('title', 'Add Student')
@section('page-title', 'Add New Student')

@section('content')

<div class="card stat-card p-4">

    <h4 class="mb-4">Student Information</h4>

    <form action="{{ route('admin.students.store') }}" method="POST">

        @csrf

        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label">Student Number</label>

                <input type="text"
                       name="student_number"
                       class="form-control"
                       value="{{ old('student_number') }}"
                       required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">First Name</label>

                <input type="text"
                       name="first_name"
                       class="form-control"
                       value="{{ old('first_name') }}"
                       required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Last Name</label>

                <input type="text"
                       name="last_name"
                       class="form-control"
                       value="{{ old('last_name') }}"
                       required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>

                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ old('email') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Gender</label>

                <select name="gender" class="form-select">
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Grade Level</label>

                <select name="grade_level" class="form-select" required>
                    <option value="">Select Grade</option>

                    @foreach(range(1, 12) as $grade)
                        <option value="Grade {{ $grade }}">
                            Grade {{ $grade }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Section</label>

                <input type="text"
                       name="section"
                       class="form-control"
                       placeholder="Example: Section A"
                       value="{{ old('section') }}"
                       required>
            </div>

        </div>

        <button class="btn btn-primary">
            Save Student
        </button>

        <a href="{{ route('admin.students.index') }}"
           class="btn btn-secondary">
            Cancel
        </a>

    </form>

</div>

@endsection
