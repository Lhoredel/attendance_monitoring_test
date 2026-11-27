@extends('layouts.app')

@section('title', 'Edit Student')
@section('page-title', 'Edit Student')

@section('content')

<div class="card stat-card p-4">

    <h4 class="mb-4">Update Student Information</h4>

    <form action="{{ route('admin.students.update', $student) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="row">

            @foreach([
                'student_number' => 'Student Number',
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
                'email' => 'Email',
                'grade_level' => 'Grade Level',
                'section' => 'Section'
            ] as $field => $label)

                <div class="col-md-6 mb-3">

                    <label class="form-label">{{ $label }}</label>

                    <input type="text"
                           name="{{ $field }}"
                           class="form-control"
                           value="{{ old($field, $student->$field) }}"
                           @if(in_array($field, [
                               'student_number',
                               'first_name',
                               'last_name',
                               'grade_level',
                               'section'
                           ])) required @endif>

                </div>

            @endforeach

            <div class="col-md-6 mb-3">

                <label class="form-label">Gender</label>

                <select name="gender" class="form-select">

                    <option value="">Select Gender</option>

                    @foreach(['Male', 'Female', 'Other'] as $gender)

                        <option value="{{ $gender }}"
                            @selected(old('gender', $student->gender) === $gender)>
                            {{ $gender }}
                        </option>

                    @endforeach

                </select>

            </div>

        </div>

        <button class="btn btn-primary">Update Student</button>

        <a href="{{ route('admin.students.index') }}"
           class="btn btn-secondary">
            Cancel
        </a>

    </form>

</div>

@endsection
