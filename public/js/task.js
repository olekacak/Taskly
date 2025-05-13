// Function to fetch tasks by workspaceId
function fetchTasksByWorkspace() {
    const token = localStorage.getItem('access_token'); // Get the access token

    const workspaceId = localStorage.getItem('selectedWorkspaceId');
    //console.log('Stored access_token:', JSON.parse(localStorage.getItem('login_data'))?.access_token);
    //console.log('Received workspaceId:', workspaceId);

    fetch(`/task/getTask?workspaceId=${workspaceId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('login_data'))?.access_token, 
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to fetch tasks');
        }
        return response.json();
    })
    .then(data => {
        console.log(' task for based on workspaceId:', data);

        if (Array.isArray(data) && data.length > 0) {
            displayTasks(data); // Call function to display tasks
        } else {
            console.log('No tasks found for this workspace');
            document.getElementById('task-list').innerHTML = '';  // Clear task list
            document.getElementById('no-task-message').innerHTML = '<p>There are no tasks yet, create a new one?</p>'; // Show message above create button
        }
    })
    .catch(error => {
        console.error('Error fetching tasks:', error);
        document.getElementById('task-list').innerHTML = '';
        document.getElementById('no-task-message').innerHTML = '<p>There are no tasks yet, create a new one?</p>';
    });
}

// Function to display tasks with time remaining
function displayTasks(tasks) {
    const taskListContainer = document.getElementById('task-list');
    const taskTemplate = document.getElementById('task-template');

    // Clear existing tasks
    taskListContainer.innerHTML = '';

    tasks.forEach(task => {
        const clone = taskTemplate.content.cloneNode(true);
        clone.querySelector('.task-title').textContent = task.title;
        clone.querySelector('.task-description').textContent = task.description;

        const deadlineElement = clone.querySelector('.task-deadline');
        if (task.status === 1) {
            // If status is complete, show "Task Complete" with the task's deleted_date
            const deletedDate = new Date(task.deleted_date);
            deadlineElement.textContent = `Task Complete at: ${deletedDate.toLocaleString()}`;
        } else {
            // If status is not complete, show the remaining time
            const deadline = task.deadline ?? 'Not Set';
            const deadlineDate = new Date(deadline);

            // Update the task every second to show real-time countdown
            const remainingTimeInterval = setInterval(() => {
                const remainingTime = getRemainingTime(deadlineDate);
                deadlineElement.textContent = remainingTime;
                if (remainingTime === 'Task expired') {
                    clearInterval(remainingTimeInterval);  // Stop updating if the task is expired
                }
            }, 1000);  // Update every second for more accuracy
        }

        const statusElement = clone.querySelector('.task-status');
        if (statusElement) {
            if (task.status === null) {
                statusElement.textContent = 'Ongoing'; // If status is null, consider it "Ongoing"
            } else if (task.status === 0) {
                statusElement.textContent = 'Expired';
            } else if (task.status === 1) {
                statusElement.textContent = 'Complete';
            } else {
                statusElement.textContent = 'Unknown';
            }
        }

        const submitBtn = clone.querySelector('.submit-task');
        if (submitBtn) {
            submitBtn.addEventListener('click', (e) => {
                e.preventDefault();
                submitTaskModal(task); // Call your modal function and pass the task
            });
        }

        // Set event listeners for editing and deleting tasks
        const editBtn = clone.querySelector('.edit-task');
        editBtn.dataset.task = JSON.stringify(task);
        editBtn.addEventListener('click', e => {
            e.preventDefault();
            openEditModal(task);
        });

        const deleteBtn = clone.querySelector('.delete-task');
        deleteBtn.dataset.taskId = task.taskId;
        deleteBtn.addEventListener('click', async e => {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this task?')) {
                await deleteTask(task.taskId);
            }
        });

        taskListContainer.appendChild(clone);
    });

    document.getElementById('no-task-message').innerHTML = '';
}

// Function to get the remaining time in a human-readable format (including seconds)
function getRemainingTime(deadlineDate) {
    const now = new Date();
    const timeDiff = deadlineDate - now; // Time difference in milliseconds

    if (timeDiff <= 0) {
        return 'Task expired at: ' + deadlineDate.toLocaleString(); // Task expired
    }

    const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000); // Calculate seconds

    let timeString = '';
    if (days > 0) {
        timeString += `${days} day${days > 1 ? 's' : ''} `;
    }
    if (hours > 0) {
        timeString += `${hours} hour${hours > 1 ? 's' : ''} `;
    }
    if (minutes > 0) {
        timeString += `${minutes} minute${minutes > 1 ? 's' : ''} `;
    }
    if (seconds >= 0) {
        timeString += `${seconds} second${seconds !== 1 ? 's' : ''}`;
    }

    return timeString.trim() + ' remaining';
}

function submitTaskModal(task) {
    // You could show a Bootstrap modal or custom modal here
    const confirmed = confirm(`Are you sure you want to mark "${task.title}" as complete?`);
    if (!confirmed) return;

    fetch('/task/postComplete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('login_data'))?.access_token, 
        },
        body: JSON.stringify({ taskId: task.taskId })
    })
    .then(response => response.json().then(data => ({
        status: response.status,
        ok: response.ok,
        body: data
    })))
    .then(result => {
        if (result.ok) {
            alert('Task marked as complete.');
            fetchTasksByWorkspace();
        } else {
            alert(result.body.message || 'Failed to complete task.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while completing the task.');
    });
}

// Example usage: Fetch tasks for the selected workspaceId
const workspaceId = localStorage.getItem('selectedWorkspaceId');
console.log("Sending workspaceId:", workspaceId);
if (workspaceId) {
    fetchTasksByWorkspace(workspaceId);
} else {
    console.log('No workspace ID selected.');
}

document.addEventListener("DOMContentLoaded", () => {
    console.log("JS loaded");

    // Open modal for creating a task
    document.getElementById("openCreateTaskBtn").addEventListener("click", () => {
        showTaskModal();
        resetTaskForm();
        setTaskIdInput(null);
        setModalMode(false); // Create Mode

         // Set workspaceId to the selected one from localStorage
        const selectedWorkspaceId = localStorage.getItem('selectedWorkspaceId');
        document.getElementById("workspaceId").value = selectedWorkspaceId;
    });

    // Handle form submission (create or update)
    document.getElementById("createTaskForm").addEventListener("submit", async function (e) {
        e.preventDefault();

        const workspaceId = document.getElementById("workspaceId").value;
        const title = document.getElementById("taskTitle").value;
        const description = document.getElementById("taskDescription").value;
        const deadline = document.getElementById("taskDeadline").value.trim() || null;

        console.log("Form submitted with values:", {
            workspaceId,
            title,
            description,
            deadline
        });

        const taskIdInput = document.getElementById("taskId");
        const taskIdValue = taskIdInput ? taskIdInput.value : null;

        const isUpdate = !!taskIdValue;
        const url = "task/postTask";
        const method = "POST";

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    "Content-Type": "application/json",
                    'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('login_data'))?.access_token,
                },
                body: JSON.stringify({
                    workspaceId: parseInt(workspaceId),
                    title,
                    description,
                    deadline,
                    taskId: isUpdate ? taskIdValue : undefined
                }),
            });

            const data = await response.json();

            if (response.ok) {
                alert(isUpdate ? "Task updated successfully!" : "Task created successfully!");
                location.reload();
            } else {
                alert("Error: " + data.message);
            }
        } catch (error) {
            alert("Request failed.");
            console.error(error);
        }
    });

    // Load workspace name from localStorage
    const workspaceData = JSON.parse(localStorage.getItem('workspaceData'));
    const selectedWorkspaceId = localStorage.getItem('selectedWorkspaceId');

    if (workspaceData && selectedWorkspaceId) {
        const selectedWorkspace = workspaceData.find(workspace => workspace.workspaceId == selectedWorkspaceId);
        document.getElementById('workspaceName').textContent = selectedWorkspace?.name ?? 'Unknown Workspace';
    } else {
        document.getElementById('workspaceName').textContent = 'Unknown Workspace';
    }

    // Fetch tasks if workspace ID is available
    if (selectedWorkspaceId) {
        fetchTasksByWorkspace();
    } else {
        console.log('No workspace ID selected.');
    }
});

// === Helper Functions ===

// Show Bootstrap modal
function showTaskModal() {
    const modal = new bootstrap.Modal(document.getElementById('createTaskModal'));
    modal.show();
}

// Set modal title and button text
function setModalMode(isEdit) {
    document.getElementById('createTaskModalLabel').textContent = isEdit ? 'Edit Task' : 'Create Task';
    document.getElementById("openCreateTaskModal").textContent = isEdit ? "Update Task" : "Create Task";
}

// Add or remove taskId hidden input
function setTaskIdInput(taskId) {
    let taskIdInput = document.getElementById("taskId");
    if (taskId === null && taskIdInput) {
        taskIdInput.remove();
    } else {
        if (!taskIdInput) {
            taskIdInput = document.createElement("input");
            taskIdInput.type = "hidden";
            taskIdInput.id = "taskId";
            taskIdInput.name = "taskId";
            document.getElementById("createTaskForm").appendChild(taskIdInput);
        }
        taskIdInput.value = taskId;
    }
}

// Clear form fields
function resetTaskForm() {
    document.getElementById("createTaskForm").reset();
}

// Open modal for editing a task
function openEditModal(task) {
    showTaskModal();

    document.getElementById("taskTitle").value = task.title;
    document.getElementById("taskDescription").value = task.description;
    document.getElementById("taskDeadline").value = task.deadline ?? '';
    document.getElementById("workspaceId").value = task.workspaceId;

    setTaskIdInput(task.taskId);
    setModalMode(true); // Edit Mode
}

// Delete task
async function deleteTask(taskId) {
    try {
        const response = await fetch(`task/deleteTask/${taskId}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('login_data'))?.access_token,
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            alert('Task deleted successfully.');
            location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Delete failed.');
        console.error(error);
    }
}
