<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="form-bg-image">
        <div class="auth-box">
            <div class="text-center mb-4">
                <h1 class="h3 mb-3">Create Account</h1>
                <p>Fill in the form below to create a new account.</p>
            </div>
            <form id="signup-form" enctype="multipart/form-data">

                <!-- Profile Image -->
                <div class="mb-1 text-center">
                    <label for="image" class="form-label">Choose Profile Image (optional)</label>

                    <!-- Avatar Preview that acts as clickable to open file input -->
                    <div class="avatar-preview-container mt-3">
                        <img id="avatar-preview" src="/assets/img/default-image.png" alt="Profile Image Preview" class="img-fluid rounded-circle avatar-preview" onclick="document.getElementById('image').click();">
                    </div>

                    <!-- Hidden file input to trigger when avatar is clicked -->
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" style="display: none;" onchange="previewImage(event)">
                </div>

                <!-- Full Name -->
                <div class="mb-4">
                    <label for="name" class="form-label">Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
                    </div>
                </div>

                <!-- Username -->
                <div class="mb-4">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="form-label">Your Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="example@company.com" required>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label">Your Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                    </div>
                </div>

                <!-- Terms -->
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to the <a href="#">terms and conditions</a>
                    </label>
                </div>

                <!-- Submit -->
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-secondary">Sign Up</button>
                </div>

                <!-- Social Links -->
                <div class="text-center mb-3"><span>or</span></div>
                <div class="d-flex justify-content-center">
                    <a href="#" class="btn btn-outline-secondary me-2"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="btn btn-outline-secondary me-2"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="btn btn-outline-secondary"><i class="bi bi-github"></i></a>
                </div>

                <!-- Login link -->
                <div class="d-flex justify-content-center align-items-center mt-4">
                    <span class="fw-normal">
                        Already have an account?
                        <a href="/login" class="fw-bold">Sign In</a>
                    </span>
                </div>
            </form>
        </div>
    </div>

<script src="js/register.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
