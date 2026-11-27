@extends('layouts.app')

@section('title', 'Attendance Reports')
@section('page-title', 'Attendance Reports')

@section('content')

<h4 class="mb-4">Attendance Records</h4>

<div class="card stat-card p-4 mb-4">

    <form method="GET"
          action="{{ auth()->user()->role === 'admin'
              ? route('admin.reports')
              : route('teacher.reports') }}">

        <div class="row">

            <div class="col-md-5 mb-3">

                <label class="form-label">Month</label>

                <input type="month"
                       name="month"
                       class="form-control"
                       value="{{ request('month') }}">

            </div>

            @if(auth()->user()->role === 'admin')

                <div class="col-md-4 mb-3">

                    <label class="form-label">Class ID</label>

                    <input type="number"
                           name="school_class_id"
                           class="form-control"
                           value="{{ request('school_class_id') }}"
                           placeholder="Enter class ID">

                </div>

            @endif

            <div class="col-md-3 mb-3 d-flex align-items-end">

                <button class="btn btn-primary w-100">
                    Filter Reports
                </button>

            </div>

        </div>

    </form>

</div>

<div class="card stat-card p-4">

    <div class="table-responsive">

        <table class="table table-bordered">

            <thead class="table-light">

                <tr>
                    <th>Date</th>
                    <th>Student Number</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </tr>

            </thead>

            <tbody>

                @forelse($records as $record)

                    <tr>

                        <td>
                            {{ $record->attendance_date->format('M d, Y') }}
                        </td>

                        <td>
                            {{ $record->student->student_number }}
                        </td>

                        <td>
                            {{ $record->student->first_name }}
                            {{ $record->student->last_name }}
                        </td>

                        <td>
                            {{ $record->schoolClass->class_name }}
                        </td>

                        <td>

                            @if($record->status === 'present')

                                <span class="badge bg-success">
                                    Present
                                </span>

                            @elseif($record->status === 'absent')

                                <span class="badge bg-danger">
                                    Absent
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    Late
                                </span>

                            @endif

                        </td>

                        <td>{{ $record->remarks ?? 'N/A' }}</td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center">
                            No attendance records found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{ $records->links() }}

    <button onclick="window.print()"
            class="btn btn-success mt-3">
        <i class="bi bi-printer"></i>
        Print Report
    </button>

</div>

@endsection
