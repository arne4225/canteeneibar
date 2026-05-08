<?php
session_start();
require_once 'conection_db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$conn = KonektatuDatuBasera();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
         :root {
            --ink: #395C6B;
            --cream: #F8F9F0;
            --sand: #E6E1C5;
            --mist: #c8d6d0;
            --deep: #243c47;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--cream);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            overflow-x: hidden;
        }
        .tabla-menu {
            width: 100%;
            border-collapse: collapse;
            background-color: #F8F9F0;
        }
        .tabla-menu th, .tabla-menu td {
            border-bottom: 1px solid #395C6B;
            padding: 10px 15px;
            text-align: left;
        }
        .tabla-menu th {
            background-color: #395C6B;
            color: #F8F9F0;
            font-weight: bold;
        }
        .tabla-menu tr:hover {
            background-color: #e8ede0;
        }

        .modal-content {
            border: 1px solid #395C6B;
            border-radius: 8px;
        }
        .modal-header {
            background-color: #395C6B;
            color: #F8F9F0;
        }
        .modal-header .btn-close {
            filter: invert(1);
        }
        .form-control:focus {
            border-color: #395C6B;
            box-shadow: 0 0 0 0.2rem rgba(57, 92, 107, 0.25);
        }
        .btn-edit {
            background-color: #395C6B;
            color: #F8F9F0;
            border: none;
        }
        .btn-edit:hover {
            background-color: #2d4a57;
            color: #F8F9F0;
        }
        .btn-cancel {
            background-color: transparent;
            color: #395C6B;
            border: 1px solid #395C6B;
        }
        .btn-cancel:hover {
            background-color: #395C6B;
            color: #F8F9F0;
        }
        /* ─── COLORS ─── */
        :root {
            --ink: #395C6B;
            --cream: #F8F9F0;
            --sand: #E6E1C5;
            --mist: #c8d6d0;
            --deep: #243c47;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        /* ─── HEADER ─── */
        .custom-header {
            border-bottom: 1px solid var(--ink);
            position: sticky;
            top: 0;
            z-index: 100;
            background-color: var(--cream);
        }

        .nav-btn {
            color: var(--ink);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.82rem;
            letter-spacing: 0.08em;
            transition: background 0.2s, color 0.2s;
        }

        .nav-btn:hover {
            background-color: var(--ink);
            color: var(--cream);
        }

        /* ─── FOOTER ─── */
        .custom-footer {
            background-color: var(--deep);
            color: var(--cream);
            border-top: none;
            padding: 3rem 0 2rem;
        }

        .custom-footer a { color: var(--mist); text-decoration: none; }
        .custom-footer a:hover { color: var(--cream); }

        .footer-label {
            font-size: 0.68rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            margin-bottom: 0.4rem;
        }

        .footer-value {
            font-size: 0.95rem;
            font-weight: 300;
        }

        .footer-bottom {
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(248,249,240,0.12);
            font-size: 0.75rem;
            opacity: 0.38;
            text-align: center;
        }

        .vertical-line {
            width: 1px;
            height: 50px;
            background-color: gray;
            margin: 0 1rem;
        }

        /* Fade-in animation */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-up {
            animation: fadeUp 0.7s ease both;
        }

        .delay-1 { animation-delay: 0.15s; }
        .delay-2 { animation-delay: 0.3s; }
        .delay-3 { animation-delay: 0.45s; }
    </style>
</head>

<body>
    <header class="custom-header py-3">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg d-flex justify-content-between align-items-center">
                <a class="logo-box d-flex justify-content-center align-items-center text-decoration-none ms-3" style="width: 160px; height: 60px;" href="index.php">
                    <img src="img/logo_uni_canteen.png" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav gap-3 mt-3 mt-lg-0">
                        <li class="nav-item"><a class="nav-btn rounded px-4 py-2 d-block text-center" href="index.php#menu">OUR MENU</a></li>
                        <li class="nav-item"><a class="nav-btn rounded px-4 py-2 d-block text-center" href="reservation.php">RESERVATION</a></li>
                    </ul>
                    <div class="d-none d-lg-block mx-3" style="width: 1px; height: 30px; background-color: #395C6B; opacity: 0.4;"></div>
                    <ul class="navbar-nav gap-1 mt-3 mt-lg-0">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item">
                                <a class="nav-btn rounded px-4 py-2 d-block text-center" href="acount.php">
                                    <?= htmlspecialchars($_SESSION['user_name']) ?>
                                </a>
                            </li>
                            <li class="nav-item"><a class="nav-btn rounded px-4 py-2 d-block text-center" href="logout.php">Logout</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-btn rounded px-4 py-2 d-block text-center" href="login.php">Login</a></li>
                            <li class="nav-item"><a class="nav-btn rounded px-4 py-2 d-block text-center" href="register.php">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <main class="container my-5">
        <div class="mb-3">
            <h2 class="fs-6 fw-bold mb-4">My Account</h2>

            <!-- Mensajes de éxito o error -->
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php
            // Consultar por Id ya que $_SESSION['user_id'] guarda el Id
            $sql = "SELECT * FROM user WHERE Id = ?";
            $stmt = mysqli_prepare($conn, $sql);

            if (!$stmt) {
                die("Prepare failed: " . mysqli_error($conn));
            }

            mysqli_stmt_bind_param($stmt, "s", $_SESSION['user_id']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            ?>

            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php $row = mysqli_fetch_assoc($result); ?>
                <table class="tabla-menu">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Password</th>
                    </tr>
                    <tr>
                        <td><?php echo ($row['Id']); ?></td>
                        <td><?php echo ($row['Name']); ?></td>
                        <td><?php echo ($row['Surname']); ?></td>
                        <td><?php echo ($row['Email']); ?></td>
                        <td><?php echo ($row['Phone_num']); ?></td>
                        <td><?php echo ($row['Password']); ?></td>
                    </tr>
                </table>

                <!-- Botón para abrir el modal -->
                <button class="btn btn-edit mt-3 px-4" data-bs-toggle="modal" data-bs-target="#editModal">
                    Edit Profile
                </button>

                <!-- Modal con el formulario de edición -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Profile</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="account_process.php">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Id <span class="text-muted fw-normal">(cannot be changed)</span></label>
                                        <input type="text" class="form-control" value="<?php echo ($row['Id']); ?>" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Name</label>
                                        <input type="text" class="form-control" name="name"
                                            value="<?php echo ($row['Name']); ?>" >
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Surname</label>
                                        <input type="text" class="form-control" name="surname"
                                            value="<?php echo htmlspecialchars($row['Surname']); ?>">
                                    </div>

                                    <!-- Email solo de lectura, no se puede cambiar desde aquí -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Email <span class="text-muted fw-normal">(cannot be changed)</span></label>
                                        <input type="email" class="form-control" value="<?php echo ($row['Email']); ?>" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Phone</label>
                                        <input type="text" class="form-control" name="phone"
                                            value="<?php echo ($row['Phone_num']); ?>" >
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Password</label>
                                        <input type="text" class="form-control" name="password"
                                            value="<?php echo ($row['Password']); ?>" >
                                    </div>

                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button type="button" class="btn btn-cancel px-4" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" name="update_profile" class="btn btn-edit px-4">Save Changes</button>
                                    </div>
                                    </div>
                                    <?php if (isset($_GET['success'])): ?>
                                    <div class="alert alert-success">Profile updated successfully.</div>
                                    <?php elseif (isset($_GET['error'])): ?>
                                        <div class="alert alert-danger">Error updating profile.</div>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <p>No user information found.</p>
            <?php endif; ?>
        </div>
        
        <!-- Formulary to see the payment information of the user -->
         <div class="mb-3 mt-5">
            <h2 class="fs-6 fw-bold mb-4">Payment Information</h2>
            <p>Here you can see your payment information and pay your bills.</p>
            <div class="d-flex justify-content-start gap-3 mt-4">
                <?php
            // Consultar por Id ya que $_SESSION['user_id'] guarda el Id
            $sql = "SELECT Payment_Id, Month, Total, User_id, Paid FROM payment WHERE User_id = ?";
            $stmt = mysqli_prepare($conn, $sql);

            if (!$stmt) {
                die("Prepare failed: " . mysqli_error($conn));
            }

            mysqli_stmt_bind_param($stmt, "s", $_SESSION['user_id']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            ?>

            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php $row = mysqli_fetch_assoc($result); ?>
                <table class="tabla-menu">
                    <tr>
                        <th>ID</th>
                        <th>User Id</th>
                        <th>Month</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                    <tr>
                        <td><?php echo ($row['Payment_Id']); ?></td>
                        <td><?php echo ($row['User_id']); ?></td>
                        <td><?php echo ($row['Month']); ?></td>
                        <td><?php echo ($row['Total'] . " €"); ?></td>
                        <td><?php 
                            if ($row['Paid'] == 1) {
                                echo "Paid";
                            } else {
                                echo "Unpaid";  
                            } ?></td>
                    </tr>
                </table>
                <button class="btn btn-edit mt-3 px-4" data-bs-toggle="modal" data-bs-target="#paymentModal">
                    Pay Now
                </button>
                <!-- Modal para el pago -->
                <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="paymentModalLabel">Payment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Total to pay: <strong><?php echo ($row['Total']); ?>€</strong></p>
                                <form method="POST" action="payment_process.php">
                                    <input type="hidden" name="user_id" value="<?php echo ($row['User_id']); ?>">
                                    <input type="hidden" name="month" value="<?php echo ($row['Month']); ?>">
                                    <input type="hidden" name="total" value="<?php echo ($row['Total']); ?>">
                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button type="button" class="btn btn-cancel px-4" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" name="pay_now" class="btn btn-edit px-4">Pay Now</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <p>No payment information found.</p>
            <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="custom-footer py-5 mt-5">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-md-4 mb-4 mb-md-0 d-flex flex-column align-items-center">
                    Contact:<br>943 89 92 11  
                </div>
                <div class="col-md-4 mb-4 mb-md-0 d-flex justify-content-center">
                    <img src="img/logo_uni_canteen_bw.png" alt="Logo" style="width: 40%; height: 40%; object-fit: contain;">
                </div>
                <div class="col-md-4 d-flex flex-column align-items-center">
                    Adress:<br>Otaola Hiribidea, 29, 20600 Eibar, Gipuzkoa
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>