<?php
session_start();

// Redirect to login page if username or role are not set in session or if role is not Principal or Admin
if (!isset($_SESSION['Username']) || !isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Principal', 'Admin'])) {
    header('Location: login.php');
    exit();
}

require_once('includes/db_connect.php');

$task_name = $task_description = $assigned_to = $due_date = '';
$task_nameError = $due_dateError = '';
$successMessage = $errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $task_name = htmlspecialchars(trim($_POST['task_name']));
    $task_description = htmlspecialchars(trim($_POST['task_description']));
    $assigned_to = htmlspecialchars(trim($_POST['assigned_to']));
    $due_date = htmlspecialchars(trim($_POST['due_date']));

    // Validate inputs
    if (empty($task_name)) {
        $task_nameError = "Task Name is required.";
    }
    if (empty($due_date)) {
        $due_dateError = "Due Date is required.";
    }

    // If no errors, proceed to insert into database
    if (empty($task_nameError) && empty($due_dateError)) {
        // Insert into tasks table
        $stmt = $conn->prepare("INSERT INTO tasks (task_name, task_description, assigned_to, due_date) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssss", $task_name, $task_description, $assigned_to, $due_date);
            if ($stmt->execute()) {
                $successMessage = "Task added successfully.";
            } else {
                $errorMessage = "Failed to add task.";
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
    <title>Task Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        input[type="date"],
        textarea {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #B9968D;
            border-radius: 5px;
            background-color: #F5F5F5;
        }

        button,
        .btn-primary {
            padding: 10px;
            margin: 20px 0;
            background-color: #65463E; /* Color adjusted to match "Add Task" button */
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
        <a class="navbar-brand" href="home.php">AITM Meet</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="addstaff.php">Add staff</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admincreatemeeting.php">Create meeting</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h2>Add Task</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="text" name="task_name" placeholder="Task Name" value="<?php echo htmlspecialchars($task_name); ?>" required>
            <span class="error"><?php echo $task_nameError; ?></span>
            <textarea name="task_description" placeholder="Task Description"><?php echo htmlspecialchars($task_description); ?></textarea>
            <input type="text" name="assigned_to" placeholder="Assigned To" value="<?php echo htmlspecialchars($assigned_to); ?>">
            <input type="date" name="due_date" value="<?php echo htmlspecialchars($due_date); ?>" required>
            <span class="error"><?php echo $due_dateError; ?></span>
            <button type="submit">Add Task</button>
        </form>
        <?php if (!empty($successMessage)): ?>
            <div class="success"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        <?php if (!empty($errorMessage)): ?>
            <div class="error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
