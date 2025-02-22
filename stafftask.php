<?php
session_start();
require_once('includes/db_connect.php');

// Redirect to login page if username or role are not set in session
if (!isset($_SESSION['Username']) || !isset($_SESSION['role'])) {
    header('Location: login.php');
    exit();
}

// Update task status if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task_id']) && isset($_POST['status'])) {
    $task_id = intval($_POST['task_id']);
    $status = htmlspecialchars(trim($_POST['status']));

    $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("si", $status, $task_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch tasks from the database
$tasks = [];
$sql = "SELECT * FROM tasks";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #D3CAC3;
            color: #4C342F;
        }
        .container {
            margin-top: 50px;
        }
        .navbar {
            background-color: #B9968D;
        }
        .navbar-brand, .nav-link {
            color: #65463E !important;
        }
        .navbar-brand:hover, .nav-link:hover {
            color: #4C342F !important;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #F5F5F5;
        }
        .table thead {
            background-color: #65463E;
            color: white;
        }
        h2 {
            color: #4C342F;
        }
        .form-inline {
            display: flex;
            flex-direction: row;
            align-items: center;
        }
        .form-inline select, .form-inline button {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="home.php">AITM</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="staffprofile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h2 class="text-center">Task List</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Task Name</th>
                    <th>Description</th>
                    <th>Assigned To</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Change Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($tasks) > 0): ?>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($task['id']); ?></td>
                            <td><?php echo htmlspecialchars($task['task_name']); ?></td>
                            <td><?php echo htmlspecialchars($task['task_description']); ?></td>
                            <td><?php echo htmlspecialchars($task['assigned_to']); ?></td>
                            <td><?php echo htmlspecialchars($task['due_date']); ?></td>
                            <td><?php echo htmlspecialchars($task['status']); ?></td>
                            <td>
                                <form class="form-inline" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                    <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['id']); ?>">
                                    <select name="status" class="form-control">
                                        <option value="Pending" <?php if ($task['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="In Progress" <?php if ($task['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                                        <option value="Completed" <?php if ($task['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No tasks found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
