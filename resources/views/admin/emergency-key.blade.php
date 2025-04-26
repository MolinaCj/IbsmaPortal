<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Emergency Pass Key</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (optional, for key icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="alert alert-warning text-center shadow-sm rounded">
            <h3><i class="fa fa-key"></i> Emergency Pass Key</h3>
            <p class="mt-3">Please copy and store this key in a safe place. You will need it for password or username recovery:</p>
            <div class="bg-light p-3 rounded fs-5 fw-bold">
                {{ $emergencyKey }}
            </div>
            <p class="mt-3 text-danger">You won’t be able to see this key again. Write it down and keep it secure.</p>
            <a href="{{ route('login_admin.login') }}" class="btn btn-success mt-3">Go to Login</a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

