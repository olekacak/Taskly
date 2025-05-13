<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Volt Laravel Dashboard - Sign In</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="form-bg-image">
        <div class="auth-box">
            <div class="text-center mb-4">
                <h1 class="h3 mb-3">Welcome back</h1>
                <p>Create a new account or use these credentials:</p>
                <p>Email: <strong>admin@volt.com</strong> Password: <strong>secret</strong></p>
            </div>
            <form id="login-form">
                <div class="mb-4">
                    <label for="email" class="form-label">Your Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" placeholder="example@company.com" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Your Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" placeholder="Password" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <a href="#" class="small">Lost password?</a>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-secondary">Sign in</button>
                </div>
                <div class="text-center mb-3">
                    <span>or</span>
                </div>
                <div class="d-flex justify-content-center">
                    <a href="#" class="btn btn-outline-secondary me-2"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="btn btn-outline-secondary me-2"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="btn btn-outline-secondary"><i class="bi bi-github"></i></a>
                </div>
                <div class="d-flex justify-content-center align-items-center mt-4">
                    <span class="fw-normal">
                        Not registered?
                        <a href="/register" class="fw-bold">Create account</a>
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- Link the external JavaScript file -->
    <script src="js/login.js"></script>
</body>
</html>
