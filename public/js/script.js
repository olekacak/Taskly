document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.querySelector('.sidebar-toggle');
    const toggleIcon = toggleButton.querySelector('i');
    const pageContentWrapper = document.getElementById('page-content-wrapper');
    const homeContent = document.getElementById('homeContent');
    const profileContent = document.getElementById('profileContent');
    const workspaceCards = document.getElementById('workspaceCards');
    const workspaceForm = document.getElementById('workspaceForm');
    const createWorkspaceBtn = document.getElementById('createWorkspaceBtn');

    function showSection(section) {
        homeContent.classList.add('d-none');
        profileContent.classList.add('d-none');
        section.classList.remove('d-none');
    }

    toggleButton.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');
        toggleIcon.classList.toggle('bi-chevron-left');
        toggleIcon.classList.toggle('bi-chevron-right');
    });

    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    sidebarLinks.forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const contentType = link.getAttribute('data-content');

            if (contentType === 'home') {
                showSection(homeContent);
                workspaceCards.innerHTML = '';
                const loginData = JSON.parse(localStorage.getItem('login_data'));
                const token = loginData?.access_token;

                // console.log('Loading home content... Access Token:', token);
                // console.log('Stored userData:', loginData?.userData);

                fetch('/workspace/getWorkspace', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error("Failed to load workspace");
                        return response.json();
                    })
                    .then(data => {
                        localStorage.setItem('workspaceData', JSON.stringify(data));
                        console.log('Workspace data:', data);
                        if (Array.isArray(data)) {
                            const template = document.getElementById('workspaceTemplate');

                            data.forEach(workspace => {
                                const clone = template.content.cloneNode(true);
                                clone.querySelector('.card-title').textContent = workspace.name ?? 'Untitled Workspace';
                                clone.querySelector('.card-text').textContent = workspace.description ?? 'No description';

                                const taskBtn = clone.querySelector('.open-task');
                                taskBtn.setAttribute('data-id', workspace.workspaceId);

                                const editLink = clone.querySelector('.edit-workspace');
                                editLink.dataset.workspaceId = workspace.workspaceId;
                                editLink.dataset.name = workspace.name;
                                editLink.dataset.description = workspace.description;
                                console.log('Workspace id data before edit:', workspace.workspaceId);

                                const deleteLink = clone.querySelector('.delete-workspace');
                                deleteLink.setAttribute('data-id', workspace.workspaceId);

                                workspaceCards.appendChild(clone);
                            });

                        } else {
                            workspaceCards.innerHTML = `<p>No workspace data found.</p>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading workspaces:', error);
                        workspaceCards.innerHTML = `<p>Error loading workspaces.</p>`;
                    });
            } else if (contentType === 'profile') {
                window.location.href = '/profile';
            } else if (contentType === 'logout') {
                const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
                logoutModal.show();

                setTimeout(function () {
                    window.location.href = '/login';
                }, 2000);
            }
        });
    });

    if (createWorkspaceBtn && workspaceForm) {
        createWorkspaceBtn.addEventListener('click', () => {
            const modal = new bootstrap.Modal(document.getElementById('createWorkspaceModal'));
            modal.show();
        });

        workspaceForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const name = document.getElementById('workspaceName').value.trim();
    const description = document.getElementById('workspaceDescription').value.trim();
    const workspaceId = document.getElementById('workspaceId').value;

    if (!name) {
        alert('Workspace name is required.');
        return;
    }

    const loginData = JSON.parse(localStorage.getItem('login_data'));
    const token = loginData?.access_token;
    const userId = loginData?.userData?.userId;

    const url = workspaceId ? '/workspace/postWorkspace' : '/workspace/postWorkspace';
    const payload = workspaceId
        ? { name, description, workspaceId }
        : { name, description, userId };

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify(payload)
    })
        .then(response => response.json())
        .then(data => {
            const modalEl = document.getElementById('createWorkspaceModal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            modalInstance.hide();
            workspaceForm.reset();
            document.getElementById('workspaceId').value = ''; // Clear after use

            if (data.workspace) {
                alert(workspaceId ? 'Workspace updated!' : 'Workspace created!');
                // Refresh
                const defaultLink = document.querySelector('.sidebar-link[data-content="home"]');
                if (defaultLink) defaultLink.click();
            } else {
                alert('Failed to save workspace.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving workspace.');
        });
});

    }

    pageContentWrapper.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('open-task')) {
            const workspaceId = e.target.getAttribute('data-id');
            console.log('Navigating to tasks for workspace:', workspaceId);
        }

        if (e.target && e.target.classList.contains('edit-workspace')) {
            const workspaceId = e.target.getAttribute('workspaceId');
            console.log('Editing workspace with ID:', workspaceId);
        }

        if (e.target && e.target.classList.contains('delete-workspace')) {
            const workspaceId = e.target.getAttribute('data-id');
            console.log('Deleting workspace with ID:', workspaceId);
        }
    });

    // Auto-load home content on first visit
    const defaultLink = document.querySelector('.sidebar-link[data-content="home"]');
    if (defaultLink) {
        defaultLink.click();
    }
});



// Handle Edit Workspace
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('edit-workspace')) {
        e.preventDefault();

        const workspaceId = e.target.dataset.workspaceId;
        const currentName = e.target.dataset.name;
        const currentDescription = e.target.dataset.description;

        document.getElementById('workspaceId').value = workspaceId;
        document.getElementById('workspaceName').value = currentName;
        document.getElementById('workspaceDescription').value = currentDescription;
        document.getElementById('createWorkspaceModalLabel').textContent = 'Edit Workspace';
        document.getElementById('workspaceFormSubmit').textContent = 'Update Workspace';

        const modal = new bootstrap.Modal(document.getElementById('createWorkspaceModal'));
        modal.show();
    }
});

document.getElementById('createWorkspaceModal').addEventListener('hidden.bs.modal', () => {
    document.getElementById('workspaceForm').reset();
    document.getElementById('workspaceId').value = '';
    document.getElementById('createWorkspaceModalLabel').textContent = 'Create New Workspace';
    document.getElementById('workspaceFormSubmit').textContent = 'Create Workspace';
});


// Add an event listener for delete actions
document.addEventListener('click', function (event) {
    if (event.target && event.target.classList.contains('delete-workspace')) {
        const workspaceId = event.target.getAttribute('data-id');
        if (workspaceId) {
            // Ask for confirmation before deleting
            if (confirm('Are you sure you want to delete this workspace?')) {
                const loginData = JSON.parse(localStorage.getItem('login_data'));
                const accessToken = loginData?.access_token;

                if (accessToken) {
                    // Make the API call to delete the workspace
                    fetch(`workspace/deleteWorkspace/${workspaceId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + accessToken
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message === 'Workspace deleted successfully') {
                                // Remove the workspace element from the DOM
                                const workspaceElement = event.target.closest('.col');
                                if (workspaceElement) {
                                    workspaceElement.remove();
                                }
                                alert('Workspace deleted successfully!');
                            } else {
                                alert('Failed to delete workspace: ' + (data.message || 'Unknown error'));
                            }
                        })
                        .catch(error => {
                            console.error('Error deleting workspace:', error);
                            alert('Error deleting workspace');
                        });
                } else {
                    alert('Access token is missing!');
                }
            }
        } else {
            alert('Workspace ID not found');
        }
    }
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('open-task')) {
        const workspaceId = e.target.getAttribute('data-id');
        console.log('Navigating to tasks for workspace:', workspaceId);
        if (workspaceId) {
            localStorage.setItem('selectedWorkspaceId', workspaceId);
            window.location.href = '/task';
        }
    }
});
