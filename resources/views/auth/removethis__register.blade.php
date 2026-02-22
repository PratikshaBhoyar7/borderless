@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="w-100" style="max-width: 500px;">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="text-primary">
                        <i class="bi bi-person-plus-fill"></i> Register
                    </h2>
                    <p class="text-muted">Create a new {{ config('app.name', 'Borderless') }} account</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name') }}" required placeholder="Enter your full name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

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
                            id="password" name="password" required placeholder="Enter your password (min 8 characters)">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control form-control-lg" id="password_confirmation"
                            name="password_confirmation" required placeholder="Confirm your password">
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-person-plus-fill"></i> Register
                    </button>
                </form>

                <hr class="my-4">

                <p class="text-center text-muted">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-primary fw-bold">Login here</a>
                </p>
            </div>
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
