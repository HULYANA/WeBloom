<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" type="text/css" href="shared/assets/css/login-style.css">
</head>
<body>
<header></header>
<main>
    <div class="login-container">
        <img src="student/assets/imgs/login_logo.png" alt="Login logo" class="login-logo">
        <form action="shared/loginQuery.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Sign In">
        </form>
        <div class="signup-container">  
            <p>Don't have an account yet? <a href="#" id="register-link">Sign up</a></p>
        </div>
        <div id="register-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <form id="register-form" action="shared/assets/php/register.php" method="post">
            <h2>Register</h2>
            <div class="name-container">
                <input type="text" id="reg-firstname" name="firstname" placeholder="First Name" required>
                <input type="text" id="reg-lastname" name="lastname" placeholder="Last Name" required>
            </div>
            <input type="email" id="reg-email" name="email" placeholder="Email Address" required>
            <div class="name-gender-container">
                <select name="gender" id="gender" required>
                    <option value="" disabled selected>Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <input type="text" id="reg-username" name="username" placeholder="Username" required>
            </div>
            <select name="programID" id="reg-programID" required>
                <option value="" disabled selected>Choose Program</option>
                <option value="100">Bachelors of Science in Computer Science</option>
                <option value="200">Bachelors of Science in Information Technology</option>
            </select>
            <input type="hidden" name="role" value="Student">
            <input type="hidden" id="generated-userid" name="userID">
            <input type="hidden" id="generated-studentid" name="studentID">
            <div class="password-container">
                <input type="password" id="reg-password" name="password" placeholder="Password" required>
                <button type="button" id="toggle-password" class="toggle-password">Show</button>
            </div>

            <input type="submit" class="reg-submit" value="Register">
            <p id="close-modal">Already have an account? <a href="#" id="signin-link">Sign In</a></p>
        </form>

        <div class="message">
            <?php
                if (isset($_SESSION['errorMessage']) && count($_SESSION['errorMessage']) > 0) {
                    foreach ($_SESSION['errorMessage'] as $message) {
                        echo '<p style="color:red;">' . $message . '</p>';
                    }
                    unset($_SESSION['errorMessage']);
                }
            ?>
        </div>
    </div>
</div>

    </div>

    <div class="login-image">
        <img src="student/assets/imgs/login_image.png" alt="Login Image">
    </div>
</main>
<footer></footer>

<script>
document.getElementById('register-link').addEventListener('click', function () {
    document.getElementById('register-modal').style.display = 'flex';
    const userID = 220000 + Math.floor(Math.random() * 1000);
    const studentID = '223' + Math.floor(1000 + Math.random() * 9000);
    document.getElementById('generated-userid').value = userID;
    document.getElementById('generated-studentid').value = studentID;
});

document.querySelector('.close-btn').addEventListener('click', function () {
    document.getElementById('register-modal').style.display = 'none';
});

document.getElementById('toggle-password').addEventListener('click', function () {
    const passwordField = document.getElementById('reg-password');
    const toggleButton = document.getElementById('toggle-password');

    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleButton.textContent = "Hide";
    } else {
        passwordField.type = "password";
        toggleButton.textContent = "Show";
    }
});


if (window.location.search.includes('showModal=true')) {
    document.getElementById('register-modal').style.display = 'flex';
    const userID = 220000 + Math.floor(Math.random() * 1000);
    const studentID = '223' + Math.floor(1000 + Math.random() * 9000);

    document.getElementById('generated-userid').value = userID;
    document.getElementById('generated-studentid').value = studentID;
}

document.querySelector('.close-btn').addEventListener('click', function () {
    document.getElementById('register-modal').style.display = 'none';
});

document.getElementById('close-modal').addEventListener('click', function () {
    document.getElementById('register-modal').style.display = 'none';
});
if (window.location.search.includes('showModal=true')) {
    document.getElementById('register-modal').style.display = 'flex';
}
</script>

</body>
</html>
