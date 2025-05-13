// Handle form submission
document.getElementById('login-form').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent the default form submission

    // Get form data
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // Prepare the data to be sent
    const loginData = {
        login: email, // or username based on your input field
        password: password
    };

    // Send a POST request to the login endpoint
    fetch('login', { // Use the proper route for your login API
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(loginData)
    })
    .then(response => response.json())
   .then(data => {
    if (data.access_token) {
        // Successful login
        alert('Login successful! Access Token: ' + data.access_token);

        console.log('Full login response:', data);

        localStorage.setItem('login_data', JSON.stringify(data));

        console.log('Stored access token:', JSON.parse(localStorage.getItem('login_data'))?.access_token);
        // console.log('Stored  userData:', JSON.parse(localStorage.getItem('login_data'))?.userData);

        window.location.href = '/home';
    } else {
        alert(data.message || 'Login failed!');
    }
})
.catch(error => {
    console.error('Error:', error);
    alert('An error occurred. Please try again.');
});

});
