<?php
session_start();
require_once('includes/db_connect.php');

$registration_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = trim($_POST['role']);

    // Server-side email pattern validation
    $email_pattern = '/^[a-zA-Z0-9._%+-]+@gmail\.com$/';

    // Password validation criteria
    $password_pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{8,}$/';

    // Validate the input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        $registration_error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match($email_pattern, $email)) {
        $registration_error = "Invalid email format. It should be example@gmail.com.";
    } elseif ($password !== $confirm_password) {
        $registration_error = "Passwords do not match.";
    } elseif (!preg_match($password_pattern, $password)) {
        $registration_error = "Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } else {
        // Check if a principal or admin already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM Users WHERE role = ?");

        // Check for existing principal
        $existing_role = "principal";
        $stmt->bind_param("s", $existing_role);
        $stmt->execute();
        $stmt->bind_result($principal_count);
        $stmt->fetch();
        $stmt->reset();

        if ($role == "principal" && $principal_count > 0) {
            $registration_error = "A principal is already registered.";
        } else {
            // Check for existing admin
            $existing_role = "admin";
            $stmt->bind_param("s", $existing_role);
            $stmt->execute();
            $stmt->bind_result($admin_count);
            $stmt->fetch();
            $stmt->reset();

            if ($role == "admin" && $admin_count > 0) {
                $registration_error = "An admin is already registered.";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Prepare and execute the query
                $stmt = $conn->prepare("INSERT INTO Users (username, email, password, role) VALUES (?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
                    if ($stmt->execute()) {
                        // Redirect to login page on successful registration
                        $_SESSION['registration_success'] = true; // Set a session variable for registration success
                        header('Location: login.php');
                        exit();
                    } else {
                        $registration_error = "Registration failed. Username or email may already be in use.";
                    }
                    $stmt->close();
                } else {
                    $registration_error = "Failed to prepare the statement.";
                }
            }
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        function validateForm() {
            const password = document.forms["registrationForm"]["password"].value;
            const confirmPassword = document.forms["registrationForm"]["confirm_password"].value;
            const email = document.forms["registrationForm"]["email"].value;
            const emailPattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
            const passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{8,}$/;

            if (!emailPattern.test(email)) {
                alert("Email must be in the format example@gmail.com");
                return false;
            }
            if (!passwordPattern.test(password)) {
                alert("Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.");
                return false;
            }
            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            return true;
        }
    </script>
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #D3CAC3;
    margin: 0;
    padding: 0;
    color: #4C342F;
}

.container {
    max-width: 500px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

h2 {
    text-align: center;
    color: #4C342F;
}

form {
    display: flex;
    flex-direction: column;
}

input[type="text"],
input[type="email"],
input[type="password"],
select {
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #B9968D;
    border-radius: 5px;
    background-color: #F5F5F5;
}

button {
    padding: 10px;
    margin: 20px 0;
    background-color: #65463E;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #4C342F;
}

.errors {
    color: red;
    margin-bottom: 10px;
}

a {
    display: block;
    text-align: center;
    color: #65463E;
    text-decoration: none;
}

a:hover {
    color: #4C342F;
    text-decoration: underline;
}

label {
    margin: 10px 0 5px 0;
}

.container {
    background-color: #B9968D;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

</style>
<body>
    <div class="container">
        <h2>Register</h2>
        <?php if (!empty($registration_error)): ?>
            <div class="errors"><?php echo htmlspecialchars($registration_error); ?></div>
        <?php endif; ?>
        <form name="registrationForm" action="register.php" method="POST" onsubmit="return validateForm()">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="email" name="email" placeholder="Email" required pattern="[a-zA-Z0-9._%+-]+@gmail\.com"><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
            <label for="role">Select Role:</label>
            <select name="role" id="role" required>
                <option value="">--Select Role--</option>
                <option value="principal">Principal</option>
                <option value="hod">HOD</option>
                <option value="staff">Staff</option>
                <option value="admin">Admin</option>
            </select><br>
            <button type="submit">Register</button>
        </form>
        <a href="login.php">Already have an account? Login</a>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
