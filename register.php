<?php 
    



?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account - Cozastore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* Using Inter font as a good default */
        }

        .radial-gradient-custom {
            background: radial-gradient(circle, rgba(240, 240, 255, 1) 0%, rgba(220, 220, 245, 1) 100%);
        }

        .card {
            border-radius: 1rem;
            /* More rounded corners for the card */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            /* Softer shadow */
        }

        .form-control {
            border-radius: 0.5rem;
            /* Rounded form inputs */
            padding: 0.75rem 1rem;
            /* Comfortable padding */
        }

        .btn-primary {
            border-radius: 0.5rem;
            /* Rounded button */
            padding: 0.75rem 1.5rem;
            /* Comfortable padding */
            font-weight: 600;
            transition: background-color 0.3s ease;
            /* Smooth hover effect */
        }

        .btn-primary:hover {
            background-color: #0056b3;
            /* Darker shade on hover */
            border-color: #0056b3;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical">
        <div class="position-relative overflow-hidden radial-gradient-custom min-vh-100 d-flex align-items-center justify-content-center p-4" >
            <div class="d-flex align-items-center justify-content-center" style="width: 100%; max-width: 1000px; height: 600px;">
                <div class="row justify-content-center w-100">
                    <div class="col-12" style="max-width: 650px;">
                        <div class="card mb-0">
                            <div class="card-body p-sm-5">
                                <a class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <h1 class="fw-bold mb-0" style="font-size: 2rem; color: orangered;">Cozastore</h1>
                                </a>
                                <p class="text-center text-secondary mb-4">Create your Account</p>
                                <form action="functions/register_handler.php" method="POST">
                                    <div class="mb-3">
                                        <label for="fullName" class="form-label fw-semibold">Username</label>
                                        <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Enter full name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your Email " required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label fw-semibold">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                                    </div>
                                    
                                    <button name="register_btn" type="submit" class="btn btn-primary w-100 py-2 fs-5 mb-4 rounded-2">Register</button>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <p class="fs-5 mb-0 fw-semibold">Already have an Account?</p>
                                        <a href="login.php" class="text-primary fw-semibold ms-2">Login</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>