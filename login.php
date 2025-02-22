<?php
require_once('includes/db_connect.php');
session_start();


$login_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $login_error = "Both fields are required.";
    } else {
        $stmt = $conn->prepare("SELECT Password, role FROM Users WHERE Username = ?");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($hashed_password, $role);
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    // Start session and store relevant data
                    $_SESSION['Username'] = $username;
                    $_SESSION['role'] = $role;
                    
                    // Redirect to dashboard
                    header('Location: dashboard.php');
                    exit();
                } else {
                    $login_error = "Invalid password.";
                }
            } else {
                $login_error = "No user found with that username.";
            }
            $stmt->close();
        } else {
            $login_error = "Failed to prepare the statement.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        <h2>Login</h2>
        <?php if (!empty($login_error)): ?>
            <div class="errors"><?php echo htmlspecialchars($login_error); ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <a href="register.php">Don't have an account? Register</a>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>