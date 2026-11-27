<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Attendance Monitoring System')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
          rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background: #142d4e;
            color: white;
        }

        .sidebar a {
            display: block;
            color: #dce6f3;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 7px;
            margin-bottom: 5px;
        }

        .sidebar a:hover {
            background: #244b78;
            color: white;
        }

        .sidebar .brand {
            font-size: 19px;
            font-weight: bold;
            padding: 20px 10px;
        }

        .main-content {
            padding: 25px;
        }

        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,.05);
        }

        .topbar {
            background: white;
            padding: 15px 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,.04);
        }
    </style>

    @stack('styles')
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <aside class="col-md-2 sidebar p-3">

            <div class="brand">
                <i class="bi bi-calendar-check"></i>
                Attendance System
            </div>

            @auth

                @if(auth()->user()->role === 'admin')

                    <a href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.students.index') }}">
                        <i class="bi bi-people"></i>
                        Students
                    </a>

                    <a href="{{ route('admin.classes.index') }}">
                        <i class="bi bi-building"></i>
                        Classes
                    </a>

                    <a href="{{ route('admin.attendance.index') }}">
                        <i class="bi bi-calendar-check"></i>
                        Attendance
                    </a>

                    <a href="{{ route('admin.reports') }}">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        Reports
                    </a>

                @else

                    <a href="{{ route('teacher.dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>

                    <a href="{{ route('teacher.attendance.index') }}">
                        <i class="bi bi-calendar-check"></i>
                        Attendance
                    </a>

                    <a href="{{ route('teacher.reports') }}">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        Reports
                    </a>

                @endif

            @endauth

        </aside>

        <main class="col-md-10 px-0">

            <div class="topbar d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    @yield('page-title', 'Dashboard')
                </h5>

                @auth
                    <div class="d-flex align-items-center gap-3">

                        <span>
                            <i class="bi bi-person-circle"></i>
                            {{ auth()->user()->name }}
                            <small class="text-muted">
                                ({{ ucfirst(auth()->user()->role) }})
                            </small>
                        </span>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <button class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-box-arrow-right"></i>
                                Logout
                            </button>
                        </form>

                    </div>
                @endauth

            </div>

            <div class="main-content">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close"
                                data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>Please check the following:</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')

            </div>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>
