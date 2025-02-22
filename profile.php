<?php
session_start();

require_once('includes/db_connect.php'); // Include database connection file

// Redirect to login page if username or role are not set in session
if (!isset($_SESSION['Username']) || !isset($_SESSION['role'])) {
    header('Location: login.php');
    exit();
}

// Sanitize session variables
$username = htmlspecialchars($_SESSION['Username']);
$role = htmlspecialchars($_SESSION['role']);

// Debugging: Display session variables
// Uncomment the following line to debug
//echo "Username: $username, Role: $role<br>";

// Fetch user profile details using prepared statement
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$userProfile = $result->fetch_assoc();
$stmt->close();

// Debugging: Display fetched user profile
// Uncomment the following line to debug
//echo "<pre>"; print_r($userProfile); echo "</pre>";

if (!$userProfile) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        .profile-info {
            margin-top: 20px;
        }
        .profile-info th {
            text-align: left;
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
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='%23D3CAC3' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }
        .btn-custom {
            background-color: #65463E;
            color: #D3CAC3;
        }
        .btn-custom:hover {
            background-color: #B9968D;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="home.php">AITM </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="createmeeting.php">Create meeting</a>
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

    <div class="container mt-4">
        <h2>Welcome, <?php echo $username; ?>!</h2>
        <div class="profile-info">
            <h3>Your Profile</h3>
            <table class="table">
                <tr>
                    <th>Username:</th>
                    <td><?php echo htmlspecialchars($userProfile['Username']); ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?php echo htmlspecialchars($userProfile['Email']); ?></td>
                </tr>
                <tr>
                    <th>Role:</th>
                    <td><?php echo htmlspecialchars($userProfile['role']); ?></td>
                </tr>
            </table>
        </div>
        <!-- Other sections for meetings, tasks, etc. can be added here -->
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
