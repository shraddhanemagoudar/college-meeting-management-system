<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meeting Management System - Information</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #D3CAC3;
            color: #4C342F;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #4C342F;
        }

        .navbar-nav .nav-link {
            color: white !important;
        }

        .container {
            max-width: 1000px;
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

        .role-section {
            margin-bottom: 30px;
        }

        .role-section h3 {
            color: #65463E;
        }

        .role-section ul {
            list-style-type: none;
            padding: 0;
        }

        .role-section ul li {
            padding: 5px 0;
        }

        footer {
            background-color: #65463E;
            color: #ffffff;
            padding: 10px 0;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">
            AITM Meet
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="information.php">Information</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>Meeting Management System - Roles and Permissions</h2>

        <div class="role-section">
            <h3>Admin</h3>
            <ul>
                <li>Add meetings</li>
                <li>Add staff</li>
                <li>Assign tasks to staff members</li>
                <li>Manage system settings</li>
            </ul>
        </div>

        <div class="role-section">
            <h3>Principal</h3>
            <ul>
                <li>Add meetings</li>
                <li>Invite members to meetings</li>
                <li>Assign tasks</li>
                <li>Review meeting outcomes</li>
            </ul>
        </div>

        <div class="role-section">
            <h3>HOD (Head of Department)</h3>
            <ul>
                <li>Create meetings for staff members</li>
                <li>Handle tasks assigned to staff</li>
                <li>Monitor department performance</li>
                <li>Report to Principal</li>
            </ul>
        </div>

        <div class="role-section">
            <h3>Staff</h3>
            <ul>
                <li>Attend assigned meetings</li>
                <li>Complete assigned tasks</li>
                <li>updates about the tasks</li>
            </ul>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Angadi Institute of Technology and Management, Belagavi. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
