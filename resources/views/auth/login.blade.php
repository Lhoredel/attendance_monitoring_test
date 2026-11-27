<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login - Attendance Monitoring System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #142d4e, #3478bd);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 35px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 35px rgba(0,0,0,.2);
        }

        .login-icon {
            font-size: 50px;
            color: #245b94;
        }
    </style>
</head>

<body>

<div class="login-card">

    <div class="text-center mb-4">

        <div class="login-icon">📋</div>

        <h3 class="fw-bold">Attendance Monitoring</h3>

        <p class="text-muted">
            Sign in to your account
        </p>

    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">

        @csrf

        <div class="mb-3">

            <label class="form-label">Email Address</label>

            <input type="email"
                   name="email"
                   class="form-control"
                   placeholder="Enter your email"
                   value="{{ old('email') }}"
                   required
                   autofocus>

        </div>

        <div class="mb-4">

            <label class="form-label">Password</label>

            <input type="password"
                   name="password"
                   class="form-control"
                   placeholder="Enter your password"
                   required>

        </div>

        <button type="submit" class="btn btn-primary w-100">
            Login
        </button>

    </form>

    <p class="text-center text-muted mt-4 mb-0">
        Attendance Monitoring System
    </p>

</div>

</body>
</html>
