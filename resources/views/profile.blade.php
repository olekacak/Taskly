<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Profile</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="css/register.css" />
  <style>
    .profile-card {
      max-width: 600px;
      margin: auto;
      padding: 2rem;
      background-color: #fff;
      border-radius: 1rem;
      box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
    }
    .profile-avatar {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #dee2e6;
    }
  </style>
</head>
<body class="bg-light">

  <div class="container py-5">
    <div class="profile-card text-center">
      <!-- Profile Image -->
      <div class="mb-3 position-relative">
        <img src="/assets/img/default-image.png" alt="User Avatar" id="profile-image" class="profile-avatar mb-2">
        <div>
          <input type="file" id="imageUpload" name="image" accept="image/*" style="display: none;" onchange="previewProfileImage(event)">
          <button class="btn btn-sm btn-outline-secondary mt-2" onclick="document.getElementById('imageUpload').click()">
            <i class="bi bi-pencil"></i> Change Avatar
          </button>
        </div>
      </div>

      <!-- Profile Info Form -->
      <form id="profile-form">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" class="form-control" name="name" placeholder="John Doe" value="John Doe" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" class="form-control" name="username" placeholder="johndoe" value="johndoe" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" name="email" placeholder="example@email.com" value="john@example.com" required>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-secondary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>

  <script src="js/profile.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
