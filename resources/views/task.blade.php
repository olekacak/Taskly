<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link rel="stylesheet" href="css/task.css">
</head>

<!-- Create Task Modal -->
<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="createTaskForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTaskModalLabel">Create Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="workspaceId" value="1" /> <!-- Replace with actual workspaceId dynamically if needed -->
                <div class="mb-3">
                    <label for="taskTitle" class="form-label">Title</label>
                    <input type="text" class="form-control" id="taskTitle" required>
                </div>
                <div class="mb-3">
                    <label for="taskDescription" class="form-label">Description</label>
                    <textarea class="form-control" id="taskDescription" rows="3"></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="taskDeadline" class="form-label">Deadline</label>
                    <input type="date" class="form-control" id="taskDeadline">
                </div>
                
            </div>
            <div class="modal-footer">
                <button id="openCreateTaskModal" class="btn btn-success">Create Task</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>


<body>
    <div class="container mt-5">
        <h2>Tasks for Workspace: <span id="workspaceName">name.</span></h2>


        <div id="taskList" class="mt-4">
            <!-- Tasks will be loaded here -->
            <!-- Template for rendering task cards -->
<template id="task-template">
    <div class="col">
        <div class="card mb-3 shadow-sm">
            <div class="card-body position-relative">
                <h5 class="card-title task-title"></h5>
                <p class="card-text task-description"></p>
                <p class="card-text"><strong>Status:</strong> <span class="task-status"></span></p> 
                <p class="card-text"><strong>Deadline:</strong> <span class="task-deadline"></span></p>

                <!-- Dropdown -->
                <div class="dropdown position-absolute top-0 end-0 m-2">
                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item submit-task" href="#">Submit</a></li>
                        <li><a class="dropdown-item edit-task" href="#">Edit</a></li>
                        <li><a class="dropdown-item delete-task" href="#">Delete</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Task list render target -->
<div id="task-list" class="row row-cols-1 row-cols-md-2 g-4"></div>

        </div>
        <div class="text-center my-4" id="no-task-message"></div>
        <!-- Create Task Button -->
        <div class="text-center my-4">
            <button class="btn btn-success" id="openCreateTaskBtn">Create Task</button>
        </div>
    </div>

    <!-- Reference to the task.js file -->
    <script src="js/task.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>