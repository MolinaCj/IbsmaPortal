{{-- @extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card shadow-lg p-4 border-0 rounded-4" style="width: 400px;">
        <h4 class="text-center text-success"><i class="fa fa-user-plus"></i> Register Admin</h4>
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form action="{{ route('admin.register.submit') }}" method="POST">
            @csrf
            <!-- Username Input -->
            <div class="mb-3">
                <label for="admin-username" class="form-label fw-semibold">Username</label>
                <input type="text" name="username" id="admin-username" class="form-control shadow-sm" placeholder="Enter username" required>
            </div>

            <!-- Password Input -->
            <div class="mb-3">
                <label for="admin-password" class="form-label fw-semibold">Password</label>
                <input type="password" name="password" id="admin-password" class="form-control shadow-sm" placeholder="Enter password" required>
            </div>

            <!-- Confirm Password Input -->
            <div class="mb-3">
                <label for="admin-password-confirm" class="form-label fw-semibold">Confirm Password</label>
                <input type="password" name="password_confirmation" id="admin-password-confirm" class="form-control shadow-sm" placeholder="Confirm password" required>
            </div>

            <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm">
                <i class="fa fa-user-plus"></i> Register
            </button>
        </form>
        <div class="text-center mt-3">
            <a href="{{ route('admin.login.submit') }}" class="text-success fw-bold">Already have an account? Login</a>
        </div>
    </div>
</div>
@endsection --}}
