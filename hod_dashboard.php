<?php
session_start();

// Redirect to login page if username or role are not set in session or if role is not Principal
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Principal') {
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

    // If no errors, proceed to insert into database and send invitations
    if (empty($agendaError) && empty($dateError) && empty($timeError)) {
        // Insert into meetings table
        $stmt = $conn->prepare("INSERT INTO meetings (agenda, date, time, venue) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssss", $agenda, $date, $time, $venue);
            if ($stmt->execute()) {
                $successMessage = "Meeting added successfully.";

                // Send email invitations to HODs and Staff
                $emailMessage = "You have been invited to a meeting.\nAgenda: $agenda\nDate: $date\nTime: $time\nVenue: $venue";

                // Retrieve HODs emails
                $stmtHODs = $conn->prepare("SELECT email FROM Users WHERE role = 'hod'");
                if ($stmtHODs) {
                    $stmtHODs->execute();
                    $resultHODs = $stmtHODs->get_result();
                    while ($row = $resultHODs->fetch_assoc()) {
                        $to = $row['email'];
                        $subject = "Meeting Invitation";
                        // Send mail function example
                        mail($to, $subject, $emailMessage);
                    }
                    $stmtHODs->close();
                }

                // Retrieve Staff emails
                $stmtStaff = $conn->prepare("SELECT email FROM Users WHERE role = 'staff'");
                if ($stmtStaff) {
                    $stmtStaff->execute();
                    $resultStaff = $stmtStaff->get_result();
                    while ($row = $resultStaff->fetch_assoc()) {
                        $to = $row['email'];
                        $subject = "Meeting Invitation";
                        // Send mail function example
                        mail($to, $subject, $emailMessage);
                    }
                    $stmtStaff->close();
                }
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
    <title>Principal Dashboard</title>
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
    <div class="container">
        <h2>Create Meeting</h2>
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
