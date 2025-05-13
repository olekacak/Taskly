function previewProfileImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('profile-image').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    const loginData = JSON.parse(localStorage.getItem('login_data'));

    if (!loginData || !loginData.userData || !loginData.userData.userId) {
        alert("User ID not found. Please log in again.");
        return;
    }

    const userId = loginData.userData.userId;

    try {
        const response = await fetch('/user/getUser', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + loginData.access_token
            },
            body: JSON.stringify({ userId })
        });

        const result = await response.json();

        if (response.ok) {
            const user = result.user;
            document.querySelector('input[name="name"]').value = user.name || '';
            document.querySelector('input[name="username"]').value = user.username || '';
            document.querySelector('input[name="email"]').value = user.email || '';

            if (user.image) {
                document.getElementById('profile-image').src = `/storage/${user.image}`;
            }

            // Display joined date if needed
            const joinedDateElement = document.getElementById('joined-date');
            if (joinedDateElement && user.created_date) {
                const formatted = new Date(user.created_date).toLocaleDateString();
                joinedDateElement.textContent = `Joined: ${formatted}`;
            }

        } else {
            alert(result.message || "Failed to load profile.");
        }
    } catch (error) {
        console.error("Error fetching user:", error);
        alert("An error occurred while loading the profile.");
    }
});

document.getElementById('profile-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const loginData = JSON.parse(localStorage.getItem('login_data'));
    if (!loginData || !loginData.userData || !loginData.access_token) {
        alert("User not authenticated. Please log in again.");
        return;
    }

    const form = e.target;
    const formData = new FormData();

    formData.append('userId', loginData.userData.userId);
    formData.append('name', form.name.value);
    formData.append('username', form.username.value);
    formData.append('email', form.email.value);

    const imageInput = document.getElementById('imageUpload');
    if (imageInput.files.length > 0) {
        formData.append('image', imageInput.files[0]);
    }

    try {
        const response = await fetch('/user/postUser', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + loginData.access_token
                // Note: No 'Content-Type', browser sets it with FormData
            },
            body: formData
        });

        const result = await response.json();

        if (response.ok) {
            alert("Profile updated successfully!");
            // Optionally update localStorage if name/username/email changed
            loginData.userData.name = form.name.value;
            loginData.userData.username = form.username.value;
            loginData.userData.email = form.email.value;
            localStorage.setItem('login_data', JSON.stringify(loginData));
        } else {
            alert(result.message || "Failed to update profile.");
        }
    } catch (error) {
        console.error("Update error:", error);
        alert("An error occurred while updating the profile.");
    }
});
