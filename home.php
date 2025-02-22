<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Angadi Institute of Technology and Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #4C342F; /* Match the navbar color */
            color: white; /* Ensure text is readable */
        }

        .navbar {
            background-color: #4C342F;
            opacity: 0; /* Initial state for animation */
            animation: fadeIn 2s forwards; /* Navbar fade-in animation */
        }

        .navbar-nav .nav-link {
            color: white !important;
        }

        .header {
            position: relative;
            text-align: center;
            color: white;
        }

        .header img {
            width: 100%;
            height: auto;
            opacity: 0; /* Initial state for animation */
            animation: slideIn 3s forwards; /* Image slide-in animation */
        }

        .header .text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2em;
            font-weight: bold;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 10px 20px;
            border-radius: 10px;
            opacity: 0; /* Initial state for animation */
            animation: fadeInText 4s forwards; /* Text fade-in animation */
        }

        .content {
            padding: 20px;
            background-color: #D3CAC3;
        }

        .btn-custom {
            background-color: #65463E;
            color: white;
            margin: 10px;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease; /* Button hover animation */
        }

        .btn-custom:hover {
            background-color: #4C342F;
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        footer {
            background-color: #65463E;
            color: #ffffff;
            padding: 10px 0;
            opacity: 0; /* Initial state for animation */
            animation: fadeInFooter 3s forwards; /* Footer fade-in animation */
        }

        footer p {
            margin: 0;
        }

        .head {
            position: relative;
            text-align: center;
            color: white;
        }

        /* Keyframes for animations */
        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInText {
            to {
                opacity: 1;
            }
        }

        @keyframes fadeInFooter {
            to {
                opacity: 1;
            }
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

<div class="header">
    <img src="https://images.static-collegedunia.com/public/college_data/images/appImage/12821_AITM_New.jpg" alt="Angadi Institute of Technology and Management">
    
</div>

<footer class="text-center mt-5">
    <p>&copy; 2024 Angadi Institute of Technology and Management, Belagavi. All rights reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
