<?php
session_start();

// Redirect to login page if username or role are not set in session or if role is not Principal
if (!isset($_SESSION['Username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit();
}

require_once('includes/db_connect.php'); // Adjust this according to your database connection file path

$username = $email = $department = $department_id = $role = '';
$usernameError = $emailError = $departmentError = $departmentIdError = $roleError = '';
$successMessage = $errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $department = htmlspecialchars(trim($_POST['department']));
    $department_id = htmlspecialchars(trim($_POST['department_id']));
    $role = htmlspecialchars(trim($_POST['role']));

    // Validate inputs
    if (empty($username)) {
        $usernameError = "Username is required.";
    }
    if (empty($email)) {
        $emailError = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format.";
    }
    if (empty($department)) {
        $departmentError = "Department is required.";
    }
    if (empty($department_id)) {
        $departmentIdError = "Department ID is required.";
    } elseif (!is_numeric($department_id)) {
        $departmentIdError = "Department ID must be numeric.";
    }
    if (empty($role)) {
        $roleError = "Role is required.";
    }

    // If no errors, proceed to insert into database
    if (empty($usernameError) && empty($emailError) && empty($departmentError) && empty($departmentIdError) && empty($roleError)) {
        // Insert into staff table
        $stmt = $conn->prepare("INSERT INTO staff (username, email, department, department_id, role) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssis", $username, $email, $department, $department_id, $role);
            if ($stmt->execute()) {
                $successMessage = "Staff member added successfully.";
            } else {
                $errorMessage = "Failed to add staff member.";
            }
            $stmt->close();
        } else {
            $errorMessage = "Failed to prepare statement.";
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Staff - Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #D3CAC3;
            margin: 0;
            padding: 0;
            color: #4C342F;
        }
        .navbar {
            background-color: #B9968D;
        }
        .navbar-brand {
            font-weight: bold;
            color: #65463E !important;
        }
        .navbar-nav .nav-link {
            color: #4C342F !important;
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
        form {
            display: flex;
            flex-direction: column;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #B9968D;
            border-radius: 5px;
            background-color: #F5F5F5;
        }
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
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="#">AITM Meet</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admincreatemeeting.php">Create Meeting</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <h2>Add Staff Member</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
        <span class="error"><?php echo $usernameError; ?></span>
        <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>
        <span class="error"><?php echo $emailError; ?></span>
        <input type="text" name="department" placeholder="Department" value="<?php echo htmlspecialchars($department); ?>" required>
        <span class="error"><?php echo $departmentError; ?></span>
        <input type="number" name="department_id" placeholder="Department ID" value="<?php echo htmlspecialchars($department_id); ?>" required>
        <span class="error"><?php echo $departmentIdError; ?></span>
        <input type="text" name="role" placeholder="Role" value="<?php echo htmlspecialchars($role); ?>" required>
        <span class="error"><?php echo $roleError; ?></span>
        <button type="submit">Add </button>
    </form>
    <?php if (!empty($successMessage)): ?>
        <div class="success"><?php echo $successMessage; ?></div>
    <?php endif; ?>
    <?php if (!empty($errorMessage)): ?>
        <div class="error"><?php echo $errorMessage; ?></div>
    <?php endif; ?>
    <a href="logout.php">Logout</a>
</div>
<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
