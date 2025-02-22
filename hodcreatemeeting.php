<?php
session_start();

// Redirect to login page if username or role are not set in session or if role is not HOD
if (!isset($_SESSION['Username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'HOD') {
    header('Location: login.php');
    exit();
}

require_once('includes/db_connect.php'); // Adjust this according to your database connection file path

$agenda = $date = $time = $venue = '';
$agendaError = $dateError = $timeError = '';
$successMessage = $errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $agenda = htmlspecialchars(trim($_POST['agenda']));
    $date = htmlspecialchars(trim($_POST['date']));
    $time = htmlspecialchars(trim($_POST['time']));
    $venue = htmlspecialchars(trim($_POST['venue']));

    // Validate inputs
    if (empty($agenda)) {
        $agendaError = "Agenda is required.";
    }
    if (empty($date)) {
        $dateError = "Date is required.";
    }
    if (empty($time)) {
        $timeError = "Time is required.";
    }

    // If no errors, proceed to insert into database
    if (empty($agendaError) && empty($dateError) && empty($timeError)) {
        // Insert into meetings table
        $stmt = $conn->prepare("INSERT INTO meetings (agenda, date, time, venue) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssss", $agenda, $date, $time, $venue);
            if ($stmt->execute()) {
                $successMessage = "Meeting added successfully.";
            } else {
                $errorMessage = "Failed to add meeting.";
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
    <title>HOD Dashboard</title>
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
        input[type="time"],
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
            background-color: #65463E; /* Color adjusted to match "Add Meeting" button */
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
        <a class="navbar-brand" href="#">AITM Belagavi</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="task.php">Tasks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">log out</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h2>Add meeting details</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="text" name="agenda" placeholder="Agenda" value="<?php echo htmlspecialchars($agenda); ?>" required>
            <span class="error"><?php echo $agendaError; ?></span>
            <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required>
            <span class="error"><?php echo $dateError; ?></span>
            <input type="time" name="time" value="<?php echo htmlspecialchars($time); ?>" required>
            <span class="error"><?php echo $timeError; ?></span>
            <textarea name="venue" placeholder="Venue"><?php echo htmlspecialchars($venue); ?></textarea>
            <button type="submit">Add Meeting</button>
            <a href="invite_dashboard.php" class="btn btn-primary">Invite Members</a>
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
