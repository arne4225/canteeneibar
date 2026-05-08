<?php
require_once 'DB.php';
session_start();


$user_id = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['user_name'] ?? null; // adjust key to match what you store on login
date_default_timezone_set('Europe/Madrid');

/* =========================
   MENÚS SEMANALES
========================= */
$menus = [
    'Monday' => [
        'breakfast' => [
            ['nombre' => 'Toast with tomato and olive oil', 'alergenos' => 'gluten'],
            ['nombre' => 'Fresh orange juice', 'alergenos' => ''],
            ['nombre' => 'Coffee with milk or seasonal tea', 'alergenos' => 'dairy'],
            ['nombre' => 'Piece of fresh fruit', 'alergenos' => '']
        ],
        'lunch' => [
            ['nombre' => 'Seasonal vegetable soup', 'alergenos' => ''],
            ['nombre' => 'Mixed salad with vinaigrette', 'alergenos' => ''],
            ['nombre' => 'Baked hake with potatoes', 'alergenos' => 'fish'],
            ['nombre' => 'Roast chicken with basmati rice', 'alergenos' => ''],
            ['nombre' => 'Homemade yogurt or seasonal fruit', 'alergenos' => 'dairy']
        ],
        'dinner' => [
            ['nombre' => 'Pumpkin cream soup with seeds', 'alergenos' => ''],
            ['nombre' => 'Spanish omelette with rustic bread', 'alergenos' => 'egg, gluten'],
            ['nombre' => 'Lentil salad with vinaigrette', 'alergenos' => ''],
            ['nombre' => 'Apple compote or homemade flan', 'alergenos' => 'dairy, egg']
        ]
    ],

    'Tuesday' => [
        'breakfast' => [
            ['nombre' => 'Croissant with butter', 'alergenos' => 'gluten, dairy'],
            ['nombre' => 'Fresh juice', 'alergenos' => ''],
            ['nombre' => 'Coffee or tea', 'alergenos' => ''],
            ['nombre' => 'Fruit', 'alergenos' => '']
        ],
        'lunch' => [
            ['nombre' => 'Macaroni with tomato', 'alergenos' => 'gluten'],
            ['nombre' => 'Baked chicken', 'alergenos' => ''],
            ['nombre' => 'Salad', 'alergenos' => ''],
            ['nombre' => 'Yogurt', 'alergenos' => 'dairy']
        ],
        'dinner' => [
            ['nombre' => 'Vegetable cream soup', 'alergenos' => ''],
            ['nombre' => 'Scrambled eggs', 'alergenos' => 'egg'],
            ['nombre' => 'Whole wheat bread', 'alergenos' => 'gluten'],
            ['nombre' => 'Fruit', 'alergenos' => '']
        ]
    ],

    'Wednesday' => [
        'breakfast' => [
            ['nombre' => 'Whole grain toast with avocado', 'alergenos' => 'gluten'],
            ['nombre' => 'Natural yogurt', 'alergenos' => 'dairy'],
            ['nombre' => 'Coffee or tea', 'alergenos' => ''],
            ['nombre' => 'Banana', 'alergenos' => '']
        ],
        'lunch' => [
            ['nombre' => 'Rice with vegetables', 'alergenos' => ''],
            ['nombre' => 'Grilled turkey breast', 'alergenos' => ''],
            ['nombre' => 'Green salad', 'alergenos' => ''],
            ['nombre' => 'Fruit dessert', 'alergenos' => '']
        ],
        'dinner' => [
            ['nombre' => 'Zucchini cream soup', 'alergenos' => ''],
            ['nombre' => 'Grilled fish', 'alergenos' => 'fish'],
            ['nombre' => 'Bread', 'alergenos' => 'gluten'],
            ['nombre' => 'Yogurt', 'alergenos' => 'dairy']
        ]
    ],

    'Thursday' => [
        'breakfast' => [
            ['nombre' => 'Toast with jam', 'alergenos' => 'gluten'],
            ['nombre' => 'Milk or coffee', 'alergenos' => 'dairy'],
            ['nombre' => 'Orange juice', 'alergenos' => ''],
            ['nombre' => 'Fruit', 'alergenos' => '']
        ],
        'lunch' => [
            ['nombre' => 'Lentil stew', 'alergenos' => ''],
            ['nombre' => 'Grilled chicken', 'alergenos' => ''],
            ['nombre' => 'Salad', 'alergenos' => ''],
            ['nombre' => 'Yogurt', 'alergenos' => 'dairy']
        ],
        'dinner' => [
            ['nombre' => 'Vegetable soup', 'alergenos' => ''],
            ['nombre' => 'Tuna omelette', 'alergenos' => 'egg, fish'],
            ['nombre' => 'Bread', 'alergenos' => 'gluten'],
            ['nombre' => 'Fruit', 'alergenos' => '']
        ]
    ],

    'Friday' => [
        'breakfast' => [
            ['nombre' => 'Cereal with milk', 'alergenos' => 'gluten, dairy'],
            ['nombre' => 'Coffee or tea', 'alergenos' => ''],
            ['nombre' => 'Fruit juice', 'alergenos' => ''],
            ['nombre' => 'Apple', 'alergenos' => '']
        ],
        'lunch' => [
            ['nombre' => 'Pasta with vegetables', 'alergenos' => 'gluten'],
            ['nombre' => 'Grilled fish', 'alergenos' => 'fish'],
            ['nombre' => 'Salad', 'alergenos' => ''],
            ['nombre' => 'Yogurt', 'alergenos' => 'dairy']
        ],
        'dinner' => [
            ['nombre' => 'Cream of carrot soup', 'alergenos' => ''],
            ['nombre' => 'Chicken sandwich', 'alergenos' => 'gluten'],
            ['nombre' => 'Fruit', 'alergenos' => ''],
            ['nombre' => 'Milk', 'alergenos' => 'dairy']
        ]
    ],

    'Saturday' => [
        'breakfast' => [
            ['nombre' => 'Pancakes with honey', 'alergenos' => 'gluten, egg'],
            ['nombre' => 'Coffee', 'alergenos' => ''],
            ['nombre' => 'Juice', 'alergenos' => ''],
            ['nombre' => 'Fruit', 'alergenos' => '']
        ],
        'lunch' => [
            ['nombre' => 'Paella', 'alergenos' => 'fish'],
            ['nombre' => 'Salad', 'alergenos' => ''],
            ['nombre' => 'Bread', 'alergenos' => 'gluten'],
            ['nombre' => 'Fruit dessert', 'alergenos' => '']
        ],
        'dinner' => [
            ['nombre' => 'Vegetable soup', 'alergenos' => ''],
            ['nombre' => 'Pizza', 'alergenos' => 'gluten, dairy'],
            ['nombre' => 'Yogurt', 'alergenos' => 'dairy'],
            ['nombre' => 'Fruit', 'alergenos' => '']
        ]
    ],

    'Sunday' => [
        'breakfast' => [
            ['nombre' => 'Toast with butter and jam', 'alergenos' => 'gluten, dairy'],
            ['nombre' => 'Coffee or tea', 'alergenos' => ''],
            ['nombre' => 'Juice', 'alergenos' => ''],
            ['nombre' => 'Fruit', 'alergenos' => '']
        ],
        'lunch' => [
            ['nombre' => 'Roast lamb', 'alergenos' => ''],
            ['nombre' => 'Roasted potatoes', 'alergenos' => ''],
            ['nombre' => 'Salad', 'alergenos' => ''],
            ['nombre' => 'Dessert', 'alergenos' => 'dairy']
        ],
        'dinner' => [
            ['nombre' => 'Vegetable cream soup', 'alergenos' => ''],
            ['nombre' => 'Ham sandwich', 'alergenos' => 'gluten'],
            ['nombre' => 'Fruit', 'alergenos' => ''],
            ['nombre' => 'Yogurt', 'alergenos' => 'dairy']
        ]
    ]
];

$diaActualRaw = date('l');

$mapDias = [
    'Monday' => 'Monday',
    'Tuesday' => 'Tuesday',
    'Wednesday' => 'Wednesday',
    'Thursday' => 'Thursday',
    'Friday' => 'Friday',
    'Saturday' => 'Saturday',
    'Sunday' => 'Sunday'
];

$diaActual = $mapDias[$diaActualRaw] ?? 'Monday';

$menuHoy = $menus[$diaActual] ?? [
    'breakfast' => [],
    'lunch' => [],
    'dinner' => []
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uni Canteen - Menú Universitario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

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

        /* ─── CAROUSEL ─── */
        #heroCarousel {
            position: relative;
            overflow: hidden;
            height: 88vh;
            min-height: 480px;
        }

        .carousel-inner, .carousel-item { height: 100%; }

        .slide-bg {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        /* Slide 1 – warm food photo feel */
        .slide-1 {
            background-image:
                linear-gradient(to bottom right, rgba(57,92,107,0.55) 0%, rgba(57,92,107,0.15) 60%, transparent 100%),
                url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1600&q=80');
        }

        /* Slide 2 – canteen / communal dining feel */
        .slide-2 {
            background-image:
                linear-gradient(to bottom right, rgba(36,60,71,0.6) 0%, rgba(36,60,71,0.1) 70%, transparent 100%),
                url('https://images.unsplash.com/photo-1567521464027-f127ff144326?w=1600&q=80');
        }

        .slide-caption {
            position: absolute;
            top: 50%;
            left: 8%;
            transform: translateY(-50%);
            max-width: 560px;
            color: var(--cream);
        }

        .slide-caption .eyebrow {
            font-size: 0.75rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            opacity: 0.8;
            margin-bottom: 1rem;
        }

        .slide-caption h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.4rem, 5vw, 3.8rem);
            line-height: 1.12;
            margin-bottom: 1.2rem;
        }

        .slide-caption p {
            font-size: 1rem;
            font-weight: 300;
            opacity: 0.88;
            max-width: 400px;
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        .btn-hero {
            display: inline-block;
            padding: 0.75rem 2.2rem;
            border: 1.5px solid var(--cream);
            color: var(--cream);
            text-decoration: none;
            font-size: 0.82rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            transition: background 0.25s, color 0.25s;
        }

        .btn-hero:hover {
            background: var(--cream);
            color: var(--ink);
        }

        /* Carousel controls */
        .carousel-control-prev,
        .carousel-control-next {
            width: 52px;
            height: 52px;
            top: 50%;
            transform: translateY(-50%);
            bottom: auto;
            opacity: 1;
        }

        .carousel-control-prev { left: 24px; }
        .carousel-control-next { right: 24px; }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 20px;
            height: 20px;
        }

        /* thin indicator lines */
        .carousel-indicators {
            bottom: 28px;
        }
        .carousel-indicators [data-bs-target] {
            width: 36px;
            height: 2px;
            border: none;
            border-radius: 0;
            background: rgba(248,249,240,0.5);
            transition: background 0.3s;
        }
        .carousel-indicators .active { background: var(--cream); }

        /* ─── MENU DEL DÍA ─── */
        .menu-section {
            padding: 6rem 0 5rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3.5rem;
        }

        .section-eyebrow {
            font-size: 0.72rem;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--ink);
            opacity: 0.55;
            margin-bottom: 0.6rem;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            color: var(--deep);
            line-height: 1.15;
        }

        .section-title em {
            font-style: italic;
            color: var(--ink);
        }

        .date-badge {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.35rem 1.2rem;
            background: var(--sand);
            font-size: 0.8rem;
            letter-spacing: 0.05em;
            color: var(--ink);
        }

        /* Meal cards */
        .meal-card {
            background: var(--cream);
            border: 1px solid rgba(57,92,107,0.18);
            padding: 2.2rem 2rem 2rem;
            position: relative;
            transition: box-shadow 0.25s, transform 0.25s;
            height: 100%;
        }

        .meal-card:hover {
            box-shadow: 0 12px 40px rgba(57,92,107,0.12);
            transform: translateY(-4px);
        }

        .meal-time-tag {
            position: absolute;
            top: -1px;
            left: -1px;
            background: var(--ink);
            color: var(--cream);
            font-size: 0.68rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            padding: 0.3rem 0.9rem;
        }

        .meal-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            margin-top: 0.6rem;
        }

        .meal-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--deep);
            margin-bottom: 0.5rem;
        }

        .meal-sub {
            font-size: 0.8rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ink);
            opacity: 0.45;
            margin-bottom: 1.4rem;
        }

        .dish-list {
            list-style: none;
            padding: 0;
        }

        .dish-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            padding: 0.6rem 0;
            border-bottom: 1px solid rgba(57,92,107,0.1);
            font-size: 0.92rem;
            line-height: 1.45;
            color: var(--ink);
        }

        .dish-list li:last-child { border-bottom: none; }

        .dish-list li::before {
            content: '';
            flex-shrink: 0;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--ink);
            opacity: 0.4;
            margin-top: 0.45rem;
        }

        .dish-allergens {
            font-size: 0.72rem;
            letter-spacing: 0.04em;
            color: var(--ink);
            opacity: 0.45;
            margin-left: auto;
            white-space: nowrap;
        }

        .meal-footer {
            margin-top: 1.6rem;
            padding-top: 1.2rem;
            border-top: 1px solid rgba(57,92,107,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.82rem;
        }

        .meal-price {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            color: var(--deep);
        }

        .btn-reserve {
            padding: 0.45rem 1.3rem;
            border: 1.5px solid var(--ink);
            background: transparent;
            color: var(--ink);
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            text-decoration: none;
            transition: background 0.22s, color 0.22s;
            cursor: pointer;
        }

        .btn-reserve:hover {
            background: var(--ink);
            color: var(--cream);
        }

        /* Divider ornament */
        .ornament {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            justify-content: center;
            margin: 4rem 0 2rem;
        }

        .ornament-line {
            flex: 1;
            max-width: 140px;
            height: 1px;
            background: rgba(57,92,107,0.25);
        }

        .ornament-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--ink);
            opacity: 0.35;
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

<!-- ═══════════════════════════════════════
     HEADER
═══════════════════════════════════════ -->
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
                        <?php if ($user_name): ?>
                            <li class="nav-item">
                                <li class="nav-item"> <a class="nav-btn rounded px-4 py-2 d-block text-center" href="acount.php">
                                    <?= htmlspecialchars($user_name) ?>
                                </a>
                            </li>
                            <li class="nav-item"><a class="nav-btn rounded px-4 py-2 d-block text-center" href="logout.php">Logout</a></li>
                        <?php else: ?>
                            <li class="nav-item dropdown">
                                <a class="nav-btn rounded px-4 py-2 d-block text-center dropdown-toggle" href="#" id="loginDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Login
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="loginDropdown"> <li class="nav-item"><a class="nav-btn rounded px-4 py-2 d-block text-center" href="login.php">Login</a></li>
                                    <li><a class="dropdown-item" href="login.php">User Login</a></li>
                                    <li><a class="dropdown-item" href="../worker-side/worker_login.php">Worker Login</a></li>
                                </ul>
                            </li>
                            <li class="nav-item"><a class="nav-btn rounded px-4 py-2 d-block text-center" href="register.php">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
        </div>
    </header>


<!-- ═══════════════════════════════════════
     HERO CAROUSEL
═══════════════════════════════════════ -->
<section id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5500">

    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
    </div>

    <div class="carousel-inner">

        <!-- SLIDE 1 -->
        <div class="carousel-item active">
            <div class="slide-bg slide-1">
                <div class="slide-caption">
                    <p class="eyebrow">Uni Canteen · Eibar</p>
                    <h1>Real food,<br><em>made with love.</em></h1>
                    <p>Fresh menu every day for the university community. Locally sourced ingredients, traditional recipes.</p>
                    <a href="#menu" class="btn-hero">See today's menu</a>
                </div>
            </div>
        </div>

        <!-- SLIDE 2 -->
        <div class="carousel-item">
            <div class="slide-bg slide-2">
                <div class="slide-caption">
                    <p class="eyebrow">Reservations open</p>
                    <h1>Your table<br><em>is waiting for you.</em></h1>
                    <p>Book in seconds and enjoy a comfortable space to eat well between classes.</p>
                    <a href="reservation.php" class="btn-hero">Book now</a>
                </div>
            </div>
        </div>

    </div>

    <button class="carousel-control-prev" type="button"
            data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button"
            data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</section>


<!-- ═══════════════════════════════════════
     MENÚ DEL DÍA
═══════════════════════════════════════ -->
<section class="menu-section" id="menu">
    <div class="container">

        <div class="section-header">
            <p class="section-eyebrow">Gastronomic offering</p>
            <h2 class="section-title">Today’s <em>menu</em></h2>
            <span class="date-badge">
                <?php echo date('l, d \d\e F \d\e Y'); ?>
            </span>
        </div>

        <div class="row g-4">

            <!-- BREAKFAST -->
            <div class="col-lg-4 fade-up delay-1">
                <div class="meal-card">
                    <span class="meal-time-tag">Breakfast · 8:00 – 10:30</span>
                    <h3 class="meal-name">Good morning</h3>
                    <p class="meal-sub">Start your day right</p>

                    <ul class="dish-list">
                    <?php foreach ($menuHoy['breakfast'] as $plato): ?>
                        <li>
                            <?php echo $plato['nombre']; ?>
                            <?php if (!empty($plato['alergenos'])): ?>
                                <span class="dish-allergens"><?php echo $plato['alergenos']; ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                    </ul>

                    <div class="meal-footer">
                        <div>
                            <div class="footer-label">Intern</div>
                            <span class="meal-price">5</span>
                            <span class="meal-price">€</span>
                        </div>
                        <div class="vertical-line"></div>
                        <div>
                            <div class="footer-label">Ocasional</div>
                            <span class="meal-price">7,35</span>
                            <span class="meal-price">€</span>
                        </div>
                        <a href="reservation.php" class="btn-reserve">Book</a>
                    </div>
                </div>
            </div>

            <!-- LUNCH -->
            <div class="col-lg-4 fade-up delay-2">
                <div class="meal-card">
                    <span class="meal-time-tag">Lunch · 13:00 – 15:30</span>
                    <h3 class="meal-name">Full menu</h3>
                    <p class="meal-sub">Starter + main course + dessert</p>

                    <ul class="dish-list">
                    <?php foreach ($menuHoy['lunch'] as $plato): ?>
                        <li>
                            <?php echo $plato['nombre']; ?>
                            <?php if (!empty($plato['alergenos'])): ?>
                                <span class="dish-allergens"><?php echo $plato['alergenos']; ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                    </ul>

                    <div class="meal-footer">
                        <div>
                            <div class="footer-label">Intern</div>
                            <span class="meal-price">11,5</span>
                            <span class="meal-price">€</span>
                        </div>
                        <div class="vertical-line"></div>
                        <div>
                            <div class="footer-label">Ocasional</div>
                            <span class="meal-price">16</span>
                            <span class="meal-price">€</span>
                        </div>
                        <a href="reservation.php" class="btn-reserve">Book</a>
                    </div>
                </div>
            </div>

            <!-- DINNER -->
            <div class="col-lg-4 fade-up delay-3">
                <div class="meal-card">
                    <span class="meal-time-tag">Dinner · 19:30 – 21:30</span>
                    <h3 class="meal-name">Light dinner</h3>
                    <p class="meal-sub">A good way to end the day</p>

                    <ul class="dish-list">
                    <?php foreach ($menuHoy['dinner'] as $plato): ?>
                        <li>
                            <?php echo $plato['nombre']; ?>
                            <?php if (!empty($plato['alergenos'])): ?>
                                <span class="dish-allergens"><?php echo $plato['alergenos']; ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                    </ul>

                    <div class="meal-footer">
                        <div>
                            <div class="footer-label">Intern</div>
                            <span class="meal-price">9,55</span>
                            <span class="meal-price">€</span>
                        </div>
                        <div class="vertical-line"></div>
                        <div>
                            <div class="footer-label">Ocasional</div>
                            <span class="meal-price">14,30</span>
                            <span class="meal-price">€</span>
                        </div>
                        <a href="reservation.php" class="btn-reserve">Book</a>
                    </div>
                </div>
            </div>

        </div><!-- /row -->

        <div class="ornament">
            <div class="ornament-line"></div>
            <div class="ornament-dot"></div>
            <div class="ornament-dot" style="opacity:.2;"></div>
            <div class="ornament-dot"></div>
            <div class="ornament-line"></div>
        </div>


    </div>
</section>


<!-- ═══════════════════════════════════════
     FOOTER
═══════════════════════════════════════ -->
<footer class="custom-footer">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-4 d-flex flex-column align-items-center">
                <p class="footer-label">Contacto</p>
                <p class="footer-value">943 89 92 11</p>
            </div>
            <div class="col-md-4 d-flex justify-content-center align-items-center">
                <img src="img/logo_uni_canteen_bw.png" alt="Logo"
                     style="width:38%;object-fit:contain;opacity:0.85;">
            </div>
            <div class="col-md-4 d-flex flex-column align-items-center">
                <p class="footer-label">Dirección</p>
                <p class="footer-value">Otaola Hiribidea, 29<br>20600 Eibar, Gipuzkoa</p>
            </div>
        </div>
        <p class="footer-bottom">© <?php echo date('Y'); ?> Uni Canteen · Todos los derechos reservados</p>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Intersection Observer → trigger fade-up only when cards enter viewport
const observer = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.style.opacity = '1';
            e.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.15 });

document.querySelectorAll('.fade-up').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(28px)';
    el.style.transition = 'opacity 0.65s ease, transform 0.65s ease';
    observer.observe(el);
});

// Smooth scroll for hero CTA
document.querySelector('.btn-hero[href="#menu-diario"]')?.addEventListener('click', e => {
    e.preventDefault();
    document.getElementById('menu-diario').scrollIntoView({ behavior: 'smooth' });
});
</script>

</body>
</html>
