<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Homepage</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="d-flex" id="wrapper">

        <!-- Sidebar -->
        <div class="bg-dark text-white sidebar" id="sidebar">
            <div class="sidebar-header">
                <h4>My Dashboard</h4>
            </div>
            <ul class="list-unstyled">
                <li><a href="#" class="sidebar-link" data-content="home"><i class="bi bi-house-door"></i> <span>Home</span></a></li>
                <li><a href="#" class="sidebar-link" data-content="profile"><i class="bi bi-person"></i> <span>Profile</span></a></li>
                <li><a href="#" class="sidebar-link" data-content="logout"><i class="bi bi-box-arrow-left"></i> <span>Logout</span></a></li>
            </ul>
        </div>

        <!-- Sidebar Toggle Button -->
        <div class="sidebar-toggle">
            <i class="bi bi-chevron-left"></i>
        </div>

        <!-- Main Content Wrapper -->
        <div id="page-content-wrapper">

            <!-- Workspace Card Template -->
            <template id="workspaceTemplate">
                <div class="col">
                    <div class="card h-100 position-relative">
                        <div class="card-body">
                            <div class="dropdown position-absolute top-0 end-0 m-2">
                                <!-- Separate button for viewing tasks (not part of dropdown) -->
                                <!-- <button class="btn btn-secondary open-task" data-id="">View Tasks</button> -->

                                <!-- Ellipsis Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item open-task" href="#">View Tasks</a></li>
                                        <li><a class="dropdown-item edit-workspace" href="#">Edit</a></li>
                                        <li><a class="dropdown-item delete-workspace" href="#">Delete</a></li>
                                    </ul>
                                </div>

                            </div>

                            <h5 class="card-title">__NAME__</h5>
                            <p class="card-text">__DESCRIPTION__</p>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Main Content Wrapper -->
            <div id="mainContentWrapper" class="container mt-4">
                <!-- Home Content Container -->
                <div id="homeContent" class="d-none">
                    <h2 class="mb-3">My Workspaces</h2>
                    <div id="workspaceCards" class="row row-cols-1 row-cols-md-2 g-4">
                        <!-- JS will inject workspaces here -->
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <button id="createWorkspaceBtn" class="btn btn-secondary">Create New Workspace</button>
                    </div>
                </div>

                <!-- Profile Content Container -->
                <div id="profileContent" class="d-none">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header text-center">
                                    <h3>Profile</h3>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <img src="https://via.placeholder.com/150" alt="Profile Image" class="img-fluid rounded-circle">
                                    </div>
                                    <h4 id="profileName">John Doe</h4>
                                    <p id="profileEmail">Email: john.doe@example.com</p>
                                    <p id="profilePhone">Phone: +123 456 7890</p>
                                    <p id="profileAddress">Address: 1234 Elm Street, Springfield, USA</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="createWorkspaceModal" tabindex="-1" aria-labelledby="createWorkspaceModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createWorkspaceModalLabel">Create New Workspace</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="workspaceForm">
                            <div class="mb-3">
                                <label for="workspaceName" class="form-label">Workspace Name</label>
                                <input type="text" class="form-control" id="workspaceName" required>
                            </div>
                            <div class="mb-3">
                                <label for="workspaceDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="workspaceDescription"></textarea>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-secondary">Create Workspace</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Logout Confirmation Modal -->
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="logoutModalLabel">Logout Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>You have been logged out. Redirecting to the login page...</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>