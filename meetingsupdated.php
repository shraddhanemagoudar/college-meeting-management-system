<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header('Location: login.php');
    exit();
}

require_once('includes/db_connect.php');

$meetings = [];
$errorMessage = '';

try {
    // Fetch meetings added by the principal
    $stmt = $conn->prepare("SELECT * FROM meetings WHERE principal_id IN (SELECT id FROM users WHERE role = 'Principal')");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $meetings[] = $row;
    }
    $stmt->close();
} catch (Exception $e) {
    $errorMessage = "An error occurred: " . $e->getMessage();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard - Meetings</title>
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

        .error {
            color: red;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #B9968D;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #F5F5F5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Meetings Scheduled by Principal</h2>
        <?php if ($errorMessage): ?>
            <div class="error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <?php if (!empty($meetings)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Agenda</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Venue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($meetings as $meeting): ?>
                        <tr>
                            <td><?php echo $meeting['id']; ?></td>
                            <td><?php echo $meeting['agenda']; ?></td>
                            <td><?php echo $meeting['date']; ?></td>
                            <td><?php echo $meeting['time']; ?></td>
                            <td><?php echo $meeting['venue']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No meetings found.</p>
        <?php endif; ?>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
