<?php
session_start();
require_once('includes/db_connect.php');

function addMeetingHistory($meeting_id, $staff_id, $attended, $outcome) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=meeting_management', 'root', '');
        $stmt = $pdo->prepare('INSERT INTO meeting_history (meeting_id, staff_id, attended, outcome) VALUES (?, ?, ?, ?)');
        $stmt->execute([$meeting_id, $staff_id, $attended, $outcome]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $meeting_id = $_POST['meeting_id'];
    $staff_id = $_POST['staff_id'];
    $attended = $_POST['attended'];
    $outcome = $_POST['outcome'];

    $result = addMeetingHistory($meeting_id, $staff_id, $attended, $outcome);

    if ($result) {
        $message = "Meeting history added successfully!";
    } else {
        $message = "Failed to add meeting history.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting History Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #D3CAC3;
        }
        .container {
            background-color: #4C342F;
            color: #D3CAC3;
            padding: 20px;
            border-radius: 10px;
            margin-top: 50px;
        }
        .btn-custom {
            background-color: #65463E;
            color: #D3CAC3;
        }
        .btn-custom:hover {
            background-color: #B9968D;
        }
        .navbar {
            background-color: #4C342F !important;
        }
        .navbar-brand, .nav-link {
            color: #D3CAC3 !important;
        }
        .nav-link:hover {
            color: #B9968D !important;
        }
        .navbar-toggler {
            border-color: #D3CAC3 !important;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">AITM Meet</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="staffprofile.php">Profile</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="task.php">Tasks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h2>Add Meeting History</h2>
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="meeting_id">Meeting ID:</label>
                <input type="text" class="form-control" id="meeting_id" name="meeting_id" required>
            </div>
            <div class="form-group">
                <label for="staff_id">Staff ID:</label>
                <input type="text" class="form-control" id="staff_id" name="staff_id" required>
            </div>
            <div class="form-group">
                <label for="attended">Attended:</label>
                <select class="form-control" id="attended" name="attended" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="outcome">Outcome:</label>
                <textarea class="form-control" id="outcome" name="outcome" required></textarea>
            </div>
            <button type="submit" class="btn btn-custom">Submit</button>
        </form>
    </div>
</body>
</html>