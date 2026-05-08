<?php
require_once 'DB.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['user_name'] ?? null;

// ── REDIRECT IF NOT LOGGED IN ──────────────────────────────────────────────
if (!$user_id) {
    header('Location: login.php');
    exit;
}

// ── HANDLE RESERVATION ────────────────────────────────────────────────────
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu_id  = $_POST['menu_id'];
    $allergy  = $_POST['allergy']  ?? '';   // ← now read from the card form
    $vegan    = isset($_POST['vegan']) ? 1 : 0; // ← new vegan checkbox
    $date     = date('Y-m-d');

    $stmt = $pdo->prepare("SELECT * FROM menu WHERE Menu_id = ?");
    $stmt->execute([$menu_id]);
    $menu = $stmt->fetch();

    if ($menu) {
        $stmt = $pdo->prepare("
            INSERT INTO reservation
            (Menu_id, Allergies, Vegan, User_id, Date, Price_Ocasional, Price_Intern)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $menu_id,
            $allergy,           // ← saved correctly
            $vegan,             // ← saved correctly
            $user_id,
            $date,
            $menu['Price_Ocasional'],
            $menu['Price_Intern']
        ]);
        $success = true;
    }
}

// ── FILTERS ───────────────────────────────────────────────────────────────
$date          = $_GET['date']    ?? date('Y-m-d');
$allergyFilter = $_GET['allergy'] ?? '';

// ── GET ALLERGIES ─────────────────────────────────────────────────────────
$stmt     = $pdo->query("SELECT * FROM Allergies");
$allergies = $stmt->fetchAll();

// ── MENU QUERY ────────────────────────────────────────────────────────────
$sql = "
    SELECT m.*,
        p1.Name AS plate1, a1.Name AS allergy1,
        p2.Name AS plate2, a2.Name AS allergy2,
        p3.Name AS plate3, a3.Name AS allergy3
    FROM menu m
    LEFT JOIN plate p1 ON m.1_Plate = p1.Plate_id
    LEFT JOIN Allergies a1 ON p1.Allergie_Id = a1.Allergy_Id
    LEFT JOIN plate p2 ON m.2_Plate = p2.Plate_id
    LEFT JOIN Allergies a2 ON p2.Allergie_Id = a2.Allergy_Id
    LEFT JOIN plate p3 ON m.3_Plate = p3.Plate_id
    LEFT JOIN Allergies a3 ON p3.Allergie_Id = a3.Allergy_Id
    WHERE m.Date = :date
";

$params = ['date' => $date];

if ($allergyFilter !== '') {
    $sql .= " AND
        (a1.Name IS NULL OR a1.Name != :allergy1) AND
        (a2.Name IS NULL OR a2.Name != :allergy2) AND
        (a3.Name IS NULL OR a3.Name != :allergy3)";
    $params['allergy1'] = $allergyFilter;
    $params['allergy2'] = $allergyFilter;
    $params['allergy3'] = $allergyFilter;
}

$stmt  = $pdo->prepare($sql);
$stmt->execute($params);
$menus = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reservation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=DM+Sans&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #395C6B;
            --cream: #F8F9F0;
            --sand: #E6E1C5;
            --mist: #c8d6d0;
            --deep: #243c47;
        }

        body {
            background-color: var(--cream);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
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
            font-size: 0.82rem;
            letter-spacing: 0.08em;
        }

        .nav-btn:hover {
            background-color: var(--ink);
            color: var(--cream);
        }

        .menu-section {
            padding: 5rem 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--deep);
        }

        .filter-box {
            background: white;
            border: 1px solid rgba(57, 92, 107, 0.15);
            padding: 1rem;
        }

        .meal-card {
            background: var(--cream);
            border: 1px solid rgba(57, 92, 107, 0.18);
            padding: 2rem;
            transition: 0.3s;
            height: 100%;
        }

        .meal-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(57, 92, 107, 0.1);
        }

        .meal-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--deep);
        }

        .dish-list {
            list-style: none;
            padding: 0;
        }

        .dish-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(57, 92, 107, 0.1);
            font-size: 0.9rem;
        }

        .dish-list li:last-child {
            border-bottom: none;
        }

        .meal-footer {
            margin-top: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .meal-price {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
        }

        .btn-reserve {
            border: 1px solid var(--ink);
            background: transparent;
            padding: 5px 16px;
            font-size: 0.75rem;
            letter-spacing: 0.08em;
        }

        .btn-reserve:hover {
            background: var(--ink);
            color: var(--cream);
        }

        .custom-footer {
            background-color: var(--deep);
            color: var(--cream);
            padding: 3rem 0 2rem;
            margin-top: 4rem;
        }

        .footer-bottom {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.75rem;
            opacity: 0.4;
        }

        /* vegan badge on card */
        .vegan-check {
            font-size: 0.8rem;
            color: var(--ink);
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin-top: 1rem;
        }

        .vegan-check input {
            accent-color: var(--ink);
            width: 15px;
            height: 15px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
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
                        <li class="nav-item"><a class="nav-btn rounded px-4 py-2 d-block text-center" href="index.php#menu">OUR MENU</a></li>
                        <li class="nav-item"><a class="nav-btn rounded px-4 py-2 d-block text-center" href="reservation.php">RESERVATION</a></li>
                    </ul>
                    <div class="d-none d-lg-block mx-3" style="width:1px;height:30px;background-color:var(--ink);opacity:0.4;"></div>
                    <ul class="navbar-nav gap-1 mt-3 mt-lg-0">
                        <?php if ($user_name): ?>
                            <li class="nav-item"><a class="nav-btn rounded px-4 py-2 d-block text-center" href="acount.php"><?= htmlspecialchars($user_name) ?></a></li>
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

    <!-- SECTION -->
    <section class="menu-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Make a Reservation</h2>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success text-center">Reservation successful!</div>
            <?php endif; ?>

            <!-- FILTER -->
            <form method="GET" class="filter-box d-flex justify-content-center gap-3 mb-5 flex-wrap">
                <input type="date" name="date" value="<?= htmlspecialchars($date) ?>" class="form-control w-auto">
                <select name="allergy" class="form-control w-auto">
                    <option value="">No allergy filter</option>
                    <?php foreach ($allergies as $a): ?>
                        <option value="<?= htmlspecialchars($a['Name']) ?>"
                            <?= $allergyFilter === $a['Name'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['Name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn-reserve">Filter</button>
            </form>

            <!-- MENU CARDS -->
            <div class="row g-4">
                <?php foreach ($menus as $menu): ?>
                    <div class="col-lg-4">
                        <div class="meal-card">
                            <h4 class="meal-name"><?= htmlspecialchars($menu['Type']) ?></h4>

                            <ul class="dish-list">
                                <li><?= htmlspecialchars($menu['plate1']) ?> (<?= htmlspecialchars($menu['allergy1'] ?? '–') ?>)</li>
                                <li><?= htmlspecialchars($menu['plate2']) ?> (<?= htmlspecialchars($menu['allergy2'] ?? '–') ?>)</li>
                                <li><?= htmlspecialchars($menu['plate3']) ?> (<?= htmlspecialchars($menu['allergy3'] ?? '–') ?>)</li>
                            </ul>

                            <form method="POST">
                                <input type="hidden" name="menu_id" value="<?= $menu['Menu_id'] ?>">

                                <!--
                                Pass the currently selected allergy filter into
                                each card's POST so it gets saved to the DB.
                            -->
                                <input type="hidden" name="allergy" value="<?= htmlspecialchars($allergyFilter) ?>">

                                <!-- ── VEGAN CHECKBOX ── -->
                                <label class="vegan-check">
                                    <input type="checkbox" name="vegan" value="1">
                                    I am vegan
                                </label>

                                <div class="meal-footer">
                                    <span class="meal-price">€<?= htmlspecialchars($menu['Price_Ocasional']) ?></span>
                                    <button type="submit" class="btn-reserve">Reserve</button>
                                </div>
                            </form>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

    <!-- FOOTER -->
    <footer class="custom-footer">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-md-4 d-flex flex-column align-items-center">
                    <p class="footer-label">Contacto</p>
                    <p class="footer-value">943 89 92 11</p>
                </div>
                <div class="col-md-4 d-flex justify-content-center align-items-center">
                    <img src="img/logo_uni_canteen_bw.png" alt="Logo" style="width:38%;object-fit:contain;opacity:0.85;">
                </div>
                <div class="col-md-4 d-flex flex-column align-items-center">
                    <p class="footer-label">Dirección</p>
                    <p class="footer-value">Otaola Hiribidea, 29<br>20600 Eibar, Gipuzkoa</p>
                </div>
            </div>
            <p class="footer-bottom">© <?= date('Y') ?> Uni Canteen · Todos los derechos reservados</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
