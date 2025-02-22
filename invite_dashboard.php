<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

session_start();

if (!isset($_SESSION['Username']) || !isset($_SESSION['role'])) {
    header('Location: login.php');
    exit();
}

require_once 'includes/db_connect.php';

// Initialize variables
$staffDetails = [];
$errorMessage = '';
$successMessage = '';

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

try {
    // Fetch all staff
    $stmt = $conn->prepare("SELECT id, username, email FROM staff");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $staffDetails[] = $row;
    }
    $stmt->close();

    // Send invites
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['invite'])) {
        if (isset($_POST['id'])) {
            $selectedStaffIds = $_POST['id'];
            $emailMessage = "You have been invited to a meeting. Please check the details in the meeting schedule.";
            $emailSentCount = 0; // Counter for sent emails

            foreach ($selectedStaffIds as $staffId) {
                $stmt = $conn->prepare("SELECT email FROM staff WHERE id = ?");
                $stmt->bind_param("i", $staffId);
                $stmt->execute();
                $stmt->bind_result($email);
                $stmt->fetch();
                $stmt->close();

                $mail = new PHPMailer(true);

                try {
                    // Server settings
                    $mail->SMTPDebug = 0;                    // Enable verbose debug output
                    $mail->isSMTP();                          // Set mailer to use SMTP
                    $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                    $mail->SMTPAuth   = true;                 // Enable SMTP authentication
                    $mail->Username   = 'youremail@gmail.com';  // SMTP username
                    $mail->Password   = '';    // SMTP password
                    $mail->SMTPSecure = 'ssl';                // Enable SSL encryption
                    $mail->Port       = 465;                  // TCP port to connect to

                    // Recipients
                    $mail->setFrom('youremail@gmail.com', 'name');
                    $mail->addAddress($email);                // Add a recipient

                    // Content
                    $mail->isHTML(true);                      // Set email format to HTML
                    $mail->Subject = "Meeting Invitation";
                    $mail->Body    = $emailMessage;

                    $mail->send();
                    $emailSentCount++;
                } catch (Exception $e) {
                    $errorMessage .= "Failed to send invite to $email. Mailer Error: {$mail->ErrorInfo}<br>";
                }
            }

            // Set success message if at least one email was sent
            if ($emailSentCount > 0) {
                $successMessage = "$emailSentCount invites sent successfully.";
            }
        } else {
            $errorMessage = "No staff selected to send invites.";
        }
    }
} catch (Exception $e) {
    $errorMessage = "An error occurred: " . $e->getMessage();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invite Dashboard</title>
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

        .success {
            color: green;
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

        button {
            padding: 10px;
            margin-top: 20px;
            background-color: #65463E;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4C342F;
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
                    <a class="nav-link" href="logout.php">log out</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h2>Invite Dashboard</h2>
        <?php if ($errorMessage): ?>
            <div class="error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <?php if ($successMessage): ?>
            <div class="success"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($staffDetails)): ?>
                        <tr>
                            <td colspan="3">No staff available.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($staffDetails as $staff): ?>
                            <tr>
                                <td><?php echo $staff['username']; ?></td>
                                <td><?php echo $staff['email']; ?></td>
                                <td><input type="checkbox" name="id[]" value="<?php echo $staff['id']; ?>"></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <button type="submit" name="invite" class="btn btn-success" style="margin-top: 20px;">Send Invites</button>
        </form>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
