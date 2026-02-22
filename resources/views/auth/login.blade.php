@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="w-100" style="max-width: 450px;">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="text-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </h2>
                    <p class="text-muted">Welcome to {{ config('app.name', 'Borderless') }}</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                            id="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                            id="password" name="password" required placeholder="Enter your password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </button>
                </form>

                <hr class="my-4">

                <!--<p class="text-center text-muted">-->
                <!--    Don't have an account?-->
                <!--    <a href="{{ route('register') }}" class="text-primary fw-bold">Register here</a>-->
                <!--</p>-->
            </div>
        </div>

        <div class="text-center mt-4 text-muted">
            <small>Default Admin Credentials: admin@admin.com / password</small>
        </div>
    </div>
</div>

<style>
    .min-vh-100 {
        min-height: 100vh;
    }

    .card {
        border-radius: 1rem;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }

    .form-control-lg {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
    }

    .form-control-lg:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .btn-primary {
        background-color: #4e73df;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #2e59a7;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
    }
</style>
@endsection
