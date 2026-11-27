@extends('layouts.app')

@section('title', 'Attendance')
@section('page-title', 'Attendance Monitoring')

@section('content')

<h4 class="mb-4">Record Student Attendance</h4>

<div class="card stat-card p-4 mb-4">

    <form method="GET"
          action="{{ auth()->user()->role === 'admin'
              ? route('admin.attendance.index')
              : route('teacher.attendance.index') }}">

        <div class="row">

            <div class="col-md-5 mb-3">

                <label class="form-label">Select Class</label>

                <select name="school_class_id"
                        class="form-select"
                        required>

                    <option value="">Choose Class</option>

                    @foreach($classes as $class)

                        <option value="{{ $class->id }}"
                            @selected(request('school_class_id') == $class->id)>
                            {{ $class->class_name }}
                            - {{ $class->section }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="col-md-4 mb-3">

                <label class="form-label">Attendance Date</label>

                <input type="date"
                       name="attendance_date"
                       class="form-control"
                       value="{{ $date }}"
                       max="{{ today()->toDateString() }}"
                       required>

            </div>

            <div class="col-md-3 mb-3 d-flex align-items-end">

                <button class="btn btn-primary w-100">
                    Load Students
                </button>

            </div>

        </div>

    </form>

</div>

@if($selectedClass)

    <div class="card stat-card p-4">

        <h5 class="mb-3">
            {{ $selectedClass->class_name }}
            - {{ $selectedClass->section }}
        </h5>

        <form method="POST"
              action="{{ auth()->user()->role === 'admin'
                  ? route('admin.attendance.store')
                  : route('teacher.attendance.store') }}">

            @csrf

            <input type="hidden"
                   name="school_class_id"
                   value="{{ $selectedClass->id }}">

            <input type="hidden"
                   name="attendance_date"
                   value="{{ $date }}">

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>Student Number</th>
                            <th>Student Name</th>
                            <th>Attendance Status</th>
                            <th>Remarks</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($students as $student)

                            <tr>

                                <td>
                                    {{ $student->student_number }}
                                </td>

                                <td>
                                    {{ $student->first_name }}
                                    {{ $student->last_name }}
                                </td>

                                <td>

                                    <select
                                        name="attendance[{{ $student->id }}][status]"
                                        class="form-select">

                                        <option value="present"
                                            @selected($student->attendance_status === 'present')>
                                            Present
                                        </option>

                                        <option value="absent"
                                            @selected($student->attendance_status === 'absent')>
                                            Absent
                                        </option>

                                        <option value="late"
                                            @selected($student->attendance_status === 'late')>
                                            Late
                                        </option>

                                    </select>

                                </td>

                                <td>

                                    <input type="text"
                                           name="attendance[{{ $student->id }}][remarks]"
                                           class="form-control"
                                           value="{{ $student->attendance_remarks ?? '' }}"
                                           placeholder="Optional remarks">

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="text-center">
                                    No students assigned to this class.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if($students->isNotEmpty())

                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i>
                    Save Attendance
                </button>

            @endif

        </form>

    </div>

@endif

@endsection
