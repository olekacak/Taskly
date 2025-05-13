document.getElementById('signup-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const userId = formData.get('userId');

    // If it's a create operation, ensure password & confirmation are included
    if (!userId && (!formData.get('password') || !formData.get('password_confirmation'))) {
        alert('Password and confirmation are required for new user.');
        return;
    }

    try {
        const response = await fetch('/user/postUser', { // Adjust URL if needed
            method: 'POST',
            body: formData,
        });

        const data = await response.json();

        if (response.ok) {
            alert(data.message || 'Success!');
            console.log(data);
            // Redirect to the login page after successful registration
            window.location.href = '/login'; 
        } else {
            alert(data.message || data.error || 'Something went wrong.');
        }
    } catch (err) {
        console.error('Error:', err);
        alert('An error occurred. Please try again.');
    }
});

// Handle image preview
function previewImage(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    
    reader.onload = function(e) {
        const avatar = document.getElementById('avatar-preview');
        avatar.src = e.target.result;  // Update the image source to the selected file
    };
    
    if (file) {
        reader.readAsDataURL(file);
    }
}
