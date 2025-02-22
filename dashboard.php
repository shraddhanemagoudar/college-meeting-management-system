<?php
session_start();
require_once('includes/db_connect.php');

// Check and debug session variables
if (isset($_SESSION['Username']) && isset($_SESSION['role'])) {
    var_dump($_SESSION['Username']);
    var_dump($_SESSION['role']);
} else {
    echo 'Session variables are not set.';
}

// Redirect to login page if username or role are not set in session
if (!isset($_SESSION['Username']) || !isset($_SESSION['role'])) {
    header('Location: login.php');
    exit();
}

// Sanitize session variables
$username = htmlspecialchars($_SESSION['Username']);
$role = htmlspecialchars($_SESSION['role']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add viewport meta tag for responsiveness -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #D3CAC3;
            margin: 0;
            padding: 0;
            color: #4C342F;
        }

        .container {
            max-width: 800px;
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

        .dashboard-options {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .dashboard-option {
            margin: 10px;
            padding: 20px;
            background-color: #B9968D;
            color: #fff;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .dashboard-option:hover {
            background-color: #65463E;
        }

        .dashboard-option a {
            color: #fff;
            text-decoration: none;
        }

        .dashboard-option a:hover {
            text-decoration: underline;
        }

        .logout-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #65463E;
            text-decoration: none;
        }

        .logout-link:hover {
            color: #4C342F;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $username; ?>!</h2>
        <div class="dashboard-options">
            <?php if ($role === 'Principal'): ?>
                <div class="dashboard-option">
                    <a href="createmeeting.php">Principal Dashboard</a>
                </div>
            <?php elseif ($role === 'HOD'): ?>
                <div class="dashboard-option">
                    <a href="hodcreatemeeting.php">HOD Dashboard</a>
                </div>
            <?php elseif ($role === 'Staff'): ?>
                <div class="dashboard-option">
                    <a href="staffprofile.php">Staff Dashboard</a>
                </div>
            <?php elseif ($role === 'Admin'): ?>
                <div class="dashboard-option">
                    <a href="admincreatemeeting.php">Admin Dashboard</a>
                </div>
            <?php else: ?>
                <div class="dashboard-option">
                    Invalid Role
                </div>
            <?php endif; ?>
        </div>
        <a class="logout-link" href="logout.php">Logout</a>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
