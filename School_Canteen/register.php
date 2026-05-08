<?php
require_once 'DB.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Kantine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4 fw-bold text-primary">Create Account</h2>

                        <?php
                        // Display success message
                        if (isset($_GET['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> Your account has been created. <a href="login.php">Login here</a>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php
                        // Display error message
                        if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> <?php echo htmlspecialchars($_GET['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="register_process.php">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fname" class="form-label fw-semibold">First Name</label>
                                    <input type="text" class="form-control form-control-lg" id="fname" name="fname"
                                        placeholder="First name" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="lname" class="form-label fw-semibold">Last Name</label>
                                    <input type="text" class="form-control form-control-lg" id="lname" name="lname"
                                        placeholder="Last name" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email"
                                    placeholder="Enter your email" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold">Phone Number</label>
                                <input type="tel" class="form-control form-control-lg" id="phone" name="phone"
                                    placeholder="Enter your phone number" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input type="password" class="form-control form-control-lg" id="password" name="password"
                                    placeholder="Create a password (min. 6 characters)" minlength="6" required>
                                <small class="form-text text-muted">Use at least 6 characters.</small>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirm" class="form-label fw-semibold">Confirm Password</label>
                                <input type="password" class="form-control form-control-lg" id="password_confirm" name="password_confirm"
                                    placeholder="Confirm your password" minlength="6" required>
                            </div>

                            <!-- User Type -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold d-block mb-2">
                                    Account Type <span class="text-muted fw-normal">(optional)</span>
                                </label>
                                <div class="p-3 rounded-3 border bg-light">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="occasional_user" name="occasional_user" value="1">
                                        <label class="form-check-label" for="occasional_user">
                                            I'm an occasional user
                                        </label>
                                        <small class="text-muted d-block" style="margin-left:1.5rem;">I visit the canteen from time to time</small>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_intern" name="is_intern" value="1">
                                        <label class="form-check-label" for="is_intern">
                                            I'm an intern
                                        </label>
                                        <small class="text-muted d-block" style="margin-left:1.5rem;">I'm currently doing an internship</small>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3 fw-semibold">Create Account</button>
                        </form>

                        <hr class="my-4">

                        <p class="text-center text-muted mb-0">
                            Already have an account?
                            <a href="login.php" class="text-primary fw-semibold text-decoration-none">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Client-side password confirmation validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;

            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Passwords do not match!');
                document.getElementById('password_confirm').focus();
            }
        });
    </script>
</body>

</html>