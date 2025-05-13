document.addEventListener('DOMContentLoaded', function () {
  fetch('/workspaces') // Replace with your actual API
    .then(response => response.json())
    .then(data => displayWorkspaces(data))
    .catch(error => console.error('Error fetching workspaces:', error));
});

function displayWorkspaces(workspaces) {
  const container = document.getElementById('workspace-container');

  if (!workspaces.length) {
    container.innerHTML = `<p class="text-muted">No workspaces found.</p>`;
    return;
  }

  workspaces.forEach(ws => {
    const col = document.createElement('div');
    col.className = 'col-sm-6 col-lg-4';

    col.innerHTML = `
      <div class="card workspace-card h-100">
        <div class="card-body">
          <h5 class="card-title">${ws.name}</h5>
          <p class="card-text">${ws.description || 'No description provided.'}</p>
          <a href="/workspaces/${ws.id}" class="btn btn-primary btn-sm">Open</a>
        </div>
      </div>
    `;

    container.appendChild(col);
  });
}
