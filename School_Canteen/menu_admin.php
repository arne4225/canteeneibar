<?php
include_once("conection_db.php");
$conn = KonektatuDatuBasera();
session_start();

$alergias = mysqli_query($conn, "SELECT Allergy_Id, Name FROM allergies");
$lista_alergia = [];
while ($alle = mysqli_fetch_assoc($alergias)) {
    $lista_alergia[] = $alle;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estructura Menú Restaurante - Paleta Personalizada</title>
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



        .menu-line {
            background-color: #395C6B;
            opacity: 0.6;
        }

        hr.divider {
            border-color: #395C6B;
            opacity: 0.3;
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
        .form-section { padding: 2rem 0; }

        .form-section h2 {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-size: 0.78rem;
            font-weight: 500;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--ink);
        }

        .form-control, .form-select {
            background-color: var(--cream);
            color: var(--ink);
            border: 1px solid rgba(57, 92, 107, 0.35);
            border-radius: 6px;
            font-size: 0.88rem;
            transition: border-color 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--ink);
            box-shadow: none;
        }

        .btn-success {
            background-color: var(--ink) !important;
            border: none !important;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.06em;
        }

        .btn-success:hover { opacity: 0.8; }

        .btn-outline-dark {
            color: var(--ink);
            border-color: rgba(57, 92, 107, 0.35);
            border-radius: 6px;
            font-size: 0.82rem;
        }

        .btn-outline-dark:hover {
            background-color: var(--ink);
            color: var(--cream);
        }
        .form-check-input {
            background-color: #F8F9F0;
            border-color: #395C6B;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #395C6B;
            border-color: #395C6B;
            --bs-form-check-bg-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%2380A4ED' stroke-width='3' d='M6 10l3 3l5-5'/%3e%3c/svg%3e");
        }

        .custom-footer {
            background-color: #F8F9F0;
            color: #395C6B;
            border-top: 1px solid #395C6B;  
        }

        .footer-element {
            background-color: #E6E1C5;
            color: #395C6B;
            font-weight: bold;
            border: none;
        }
        
        .footer-line {
            background-color: #E6E1C5;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>

    <!-- ─── HEADER ─── -->
<header class="custom-header py-3">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg d-flex justify-content-between align-items-center">
            <a class="logo-box d-flex justify-content-center align-items-center text-decoration-none ms-3"
                style="width:160px;height:60px;font-size:0.9rem;" href="index.php">
                <img src="img/logo_uni_canteen.png" alt="Logo" style="width:100%;height:100%;object-fit:contain;">
            </a>

            <button class="navbar-toggler border-0" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav gap-3 mt-3 mt-lg-0">
                    <li class="nav-item"><a class="nav-btn rounded px-4 py-2 d-block text-center" href="#menu">OUR MENU</a></li>
                    <li class="nav-item"><a class="nav-btn rounded px-4 py-2 d-block text-center" href="reservation.php">RESERVATION</a></li>
                </ul>

                <div class="d-none d-lg-block mx-3"
                    style="width:1px;height:30px;background-color:var(--ink);opacity:0.4;"></div>

                <ul class="navbar-nav gap-1 mt-3 mt-lg-0">
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item">
                            <a class="nav-btn rounded px-4 py-2 d-block text-center" href="account.php">
                                <?= htmlspecialchars($_SESSION['username']) ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-btn rounded px-4 py-2 d-block text-center" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-btn rounded px-4 py-2 d-block text-center" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-btn rounded px-4 py-2 d-block text-center" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </div>
</header>
    <main class="container my-5">
        <section id="menu" class="mb-5">
            <h1 class="text-center fs-2 fw-bold mb-5">MANAGE MENUS</h1>

            <!-- BREAKFAST -->
            <div class="mb-4">
                <h2 class="fs-6 fw-bold mb-4">BREAKFAST</h2>
                <?php
                $sql = "SELECT * FROM menu WHERE type = 'breakfast'";
                $result = mysqli_query($conn, $sql);
                ?>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <table class="tabla-menu">
                        <tr>
                            <th>Menu ID</th>
                            <th>1st Plate</th>
                            <th>2nd Plate</th>
                            <th>3rd Plate</th>
                            <th>Type</th>
                            <th>Price Ocasional</th>
                            <th>Price Intern</th>
                            <th>Date</th>
                        </tr>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row["Menu_id"] ?></td>
                            <td><?= $row["1_Plate"] ?></td>
                            <td><?= $row["2_Plate"] ?></td>
                            <td><?= $row["3_Plate"] ?></td>
                            <td><?= $row["Type"] ?></td>
                            <td><?= $row["Price_Ocasional"] ?></td>
                            <td><?= $row["Price_Intern"] ?></td>
                            <td><?= $row["Date"] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p>0 results</p>
                <?php endif; ?>
            </div>

            <!-- LUNCH -->
            <div class="mb-4">
                <h2 class="fs-6 fw-bold mb-4">LUNCH</h2>
                <?php
                $sql = "SELECT * FROM menu WHERE type = 'lunch'";
                $result = mysqli_query($conn, $sql);
                ?>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <table class="tabla-menu">
                        <tr>
                            <th>Menu ID</th>
                            <th>1st Plate</th>
                            <th>2nd Plate</th>
                            <th>3rd Plate</th>
                            <th>Type</th>
                            <th>Price Ocasional</th>
                            <th>Price Intern</th>
                            <th>Date</th>
                        </tr>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row["Menu_id"] ?></td>
                            <td><?= $row["1_Plate"] ?></td>
                            <td><?= $row["2_Plate"] ?></td>
                            <td><?= $row["3_Plate"] ?></td>
                            <td><?= $row["Type"] ?></td>
                            <td><?= $row["Price_Ocasional"] ?></td>
                            <td><?= $row["Price_Intern"] ?></td>
                            <td><?= $row["Date"] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p>0 results</p>
                <?php endif; ?>
            </div>

            <!-- DINNER -->
            <div class="mb-4">
                <h2 class="fs-6 fw-bold mb-4">DINNER</h2>
                <?php
                $sql = "SELECT * FROM menu WHERE type = 'dinner'";
                $result = mysqli_query($conn, $sql);
                ?>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <table class="tabla-menu">
                        <tr>
                            <th>Menu ID</th>
                            <th>1st Plate</th>
                            <th>2nd Plate</th>
                            <th>3rd Plate</th>
                            <th>Type</th>
                            <th>Price Ocasional</th>
                            <th>Price Intern</th>
                            <th>Date</th>
                        </tr>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row["Menu_id"] ?></td>
                            <td><?= $row["1_Plate"] ?></td>
                            <td><?= $row["2_Plate"] ?></td>
                            <td><?= $row["3_Plate"] ?></td>
                            <td><?= $row["Type"] ?></td>
                            <td><?= $row["Price_Ocasional"] ?></td>
                            <td><?= $row["Price_Intern"] ?></td>
                            <td><?= $row["Date"] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p>0 results</p>
                <?php endif; ?>
            </div>

        </section>

        <hr class="divider my-5 border-2">

        <!--FORMULARIO AÑADIR PLATO-->                
        <div class="form-section">
            <h2 class="fs-6 fw-bold mb-4">ADD PLATE</h2>
            <?php if (!empty($msg_plato)): ?>
                <p class="<?= strpos($msg_plato, 'Error') === false ? 'msg-ok' : 'msg-error' ?>">
                    <?= $msg_plato ?>
                </p>
            <?php endif; ?>
            <form action="add_menus.php" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Allergies</label>
                        <select name="allergies" class="form-select" required>
                            <option value="">-- Select --</option>
                            <?php foreach ($lista_alergia as $alle): ?>
                            <option value="<?= $alle['Allergy_Id'] ?>"><?= $alle['Name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Photo</label>
                        <div>
                            <input type="file" id="Argazk" name="Argazk" accept="image/*" class="d-none">
                            <button type="button"
                                    class="btn btn-light btn-outline-dark"
                                    onclick="document.getElementById('Argazk').click()">
                                Select file
                            </button>
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" name="añadir_plato" class="btn btn-success me-4">ADD</button>
                    </div>
                </div>
            </form>
        </div>

        <hr class="divider my-5 border-2">
        
        <!-- FORMULARIO AÑADIR MENÚ -->
        <div class="form-section">
            <h2 class="fs-6 fw-bold mb-4">ADD MENU</h2>
            <?php if (!empty($msg_menu)): ?>
                <p class="<?= strpos($msg_menu, 'Error') === false ? 'msg-ok' : 'msg-error' ?>">
                    <?= $msg_menu ?>
                </p>
            <?php endif; ?>

            <?php
            // Cargamos los platos para los selects
            $platos = mysqli_query($conn, "SELECT Plate_id, Name FROM plate");
            $lista_platos = [];
            while ($p = mysqli_fetch_assoc($platos)) {
                $lista_platos[] = $p;
            }
            ?>

            <form action="add_menus.php" method="POST">
                <!-- Primera fila: plates, type, date -->
                <div class="row g-3">
                    
                    <div class="col-md-2">
                        <label class="form-label">1st Plate</label>
                        <select name="plate1" class="form-select" required>
                            <option value="">-- Select --</option>
                            <?php foreach ($lista_platos as $p): ?>
                                <option value="<?= $p['Plate_id'] ?>"><?= $p['Name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">2nd Plate</label>
                        <select name="plate2" class="form-select" required>
                            <option value="">-- Select --</option>
                            <?php foreach ($lista_platos as $p): ?>
                                <option value="<?= $p['Plate_id'] ?>"><?= $p['Name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">3rd Plate</label>
                        <select name="plate3" class="form-select" required>
                            <option value="">-- Select --</option>
                            <?php foreach ($lista_platos as $p): ?>
                                <option value="<?= $p['Plate_id'] ?>"><?= $p['Name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            <option value="">-- Select --</option>
                            <option value="breakfast">Breakfast</option>
                            <option value="lunch">Lunch</option>
                            <option value="dinner">Dinner</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                </div>

                <!-- Segunda fila: precios y botón -->
                <div class="row g-3 mt-1">
                    <div class="col-md-3">
                        <label class="form-label">Price Ocasional</label>
                        <input type="number" step="0.01" name="price_ocasional" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Price Intern</label>
                        <input type="number" step="0.01" name="price_intern" class="form-control" required>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" name="añadir_menu" class="btn btn-success me-4">ADD</button>
                    </div>
                </div>
            </form>
        </div>

        <?php mysqli_close($conn); ?>

    </main>

    <footer class="custom-footer py-5 mt-5">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-md-4 mb-4 mb-md-0 d-flex flex-column align-items-center">
                  Contact:<br>
                  943 89 92 11  
                </div>
                <div class="col-md-4 mb-4 mb-md-0 d-flex justify-content-center">
                    <img src="img/logo_uni_canteen_bw.png" alt="Logo" style="width: 40%; height: 40%; object-fit: contain;">
               
                </div>
                <div class="col-md-4 d-flex flex-column align-items-center">
                    Adress:<br>
                    Otaola Hiribidea, 29, 20600 Eibar, Gipuzkoa
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>