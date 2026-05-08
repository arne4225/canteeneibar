<?php
session_start();
require_once 'DB.php';

// CHECK IF LOGGED IN AS WORKER
if (!isset($_SESSION['worker_id'])) {
    header("Location: worker_login.php");
    exit();
}

// VERIFY WORKER EXISTS IN DB
$stmt = $pdo->prepare("SELECT * FROM worker WHERE Id = ?");
$stmt->execute([$_SESSION['worker_id']]);
$worker = $stmt->fetch();

if (!$worker) {
    session_destroy();
    header("Location: worker_login.php");
    exit();
}

// SELECTED DATE (default today, allow GET param)
$today = date('Y-m-d');
$selectedDate = $today;
if (!empty($_GET['date'])) {
    $parsed = date_create($_GET['date']);
    if ($parsed) $selectedDate = date_format($parsed, 'Y-m-d');
}
$isToday = ($selectedDate === $today);

// GET PREV / NEXT dates for navigation
$prevDate = date('Y-m-d', strtotime($selectedDate . ' -1 day'));
$nextDate = date('Y-m-d', strtotime($selectedDate . ' +1 day'));

// GET ALL MENUS FOR SELECTED DATE
$stmt = $pdo->prepare("
    SELECT m.*, 
           p1.Name AS plate1, 
           p2.Name AS plate2, 
           p3.Name AS plate3
    FROM menu m
    LEFT JOIN plate p1 ON m.1_Plate = p1.Plate_id
    LEFT JOIN plate p2 ON m.2_Plate = p2.Plate_id
    LEFT JOIN plate p3 ON m.3_Plate = p3.Plate_id
    WHERE m.Date = ?
    ORDER BY m.Menu_id ASC
");
$stmt->execute([$selectedDate]);
$menus = $stmt->fetchAll();

// GET RESERVATIONS FOR SELECTED DATE
$stmt = $pdo->prepare("
    SELECT r.*,
           u.Name, u.Surname, u.Intern,
           m.Type AS meal_type
    FROM reservation r
    JOIN user u ON r.User_id = u.Id
    LEFT JOIN menu m ON r.Menu_id = m.Menu_id
    WHERE r.Date = ?
    ORDER BY m.Menu_id ASC
");
$stmt->execute([$selectedDate]);
$reservations = $stmt->fetchAll();

//  QUICK STATS
$totalMeals = count($reservations);

$allergyCount = 0;

foreach ($reservations as $r) {
    if (!empty($r['Allergies']) && $r['Allergies'] !== 'None') $allergyCount++;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Dashboard</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --ink: #395C6B;
            --cream: #F8F9F0;
            --sand: #E6E1C5;
            --mist: #c8d6d0;
            --deep: #243c47;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        .carousel-inner,
        .carousel-item {
            height: 100%;
        }

        .slide-bg {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .slide-1 {
            background-image:
                linear-gradient(to bottom right, rgba(57, 92, 107, 0.55) 0%, rgba(57, 92, 107, 0.15) 60%, transparent 100%),
                url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1600&q=80');
        }

        .slide-2 {
            background-image:
                linear-gradient(to bottom right, rgba(36, 60, 71, 0.6) 0%, rgba(36, 60, 71, 0.1) 70%, transparent 100%),
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

        .carousel-control-prev,
        .carousel-control-next {
            width: 52px;
            height: 52px;
            top: 50%;
            transform: translateY(-50%);
            bottom: auto;
            opacity: 1;
        }

        .carousel-control-prev {
            left: 24px;
        }

        .carousel-control-next {
            right: 24px;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 20px;
            height: 20px;
        }

        .carousel-indicators {
            bottom: 28px;
        }

        .carousel-indicators [data-bs-target] {
            width: 36px;
            height: 2px;
            border: none;
            border-radius: 0;
            background: rgba(248, 249, 240, 0.5);
            transition: background 0.3s;
        }

        .carousel-indicators .active {
            background: var(--cream);
        }

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
            border: 1px solid rgba(57, 92, 107, 0.18);
            padding: 2.2rem 2rem 2rem;
            position: relative;
            transition: box-shadow 0.25s, transform 0.25s;
            height: 100%;
        }

        .meal-card:hover {
            box-shadow: 0 12px 40px rgba(57, 92, 107, 0.12);
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
            border-bottom: 1px solid rgba(57, 92, 107, 0.1);
            font-size: 0.92rem;
            line-height: 1.45;
            color: var(--ink);
        }

        .dish-list li:last-child {
            border-bottom: none;
        }

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
            border-top: 1px solid rgba(57, 92, 107, 0.15);
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
            background: rgba(57, 92, 107, 0.25);
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

        .custom-footer a {
            color: var(--mist);
            text-decoration: none;
        }

        .custom-footer a:hover {
            color: var(--cream);
        }

        .footer-label {
            font-size: 0.68rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            opacity: 0.45;
            margin-bottom: 0.4rem;
        }

        .footer-value {
            font-size: 0.95rem;
            font-weight: 300;
        }

        .footer-bottom {
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(248, 249, 240, 0.12);
            font-size: 0.75rem;
            opacity: 0.38;
            text-align: center;
        }

        /* Fade-in animation */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up {
            animation: fadeUp 0.7s ease both;
        }

        .delay-1 {
            animation-delay: 0.15s;
        }

        .delay-2 {
            animation-delay: 0.3s;
        }

        .delay-3 {
            animation-delay: 0.45s;
        }

        /* ─── DASHBOARD-SPECIFIC ─── */
        .dashboard-wrapper {
            min-height: 100vh;
            padding: 3rem 0 5rem;
        }

        .dashboard-greeting {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 3.5vw, 2.6rem);
            color: var(--deep);
            line-height: 1.2;
        }

        .dashboard-greeting span {
            font-style: italic;
        }

        .worker-role-badge {
            display: inline-block;
            padding: 0.28rem 0.9rem;
            background: var(--sand);
            font-size: 0.7rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--ink);
        }

        /* Stat cards */
        .stat-card {
            border: 1px solid rgba(57, 92, 107, 0.18);
            padding: 1.8rem 1.6rem;
            background: var(--cream);
            position: relative;
            overflow: hidden;
            transition: box-shadow 0.25s, transform 0.25s;
        }

        .stat-card:hover {
            box-shadow: 0 8px 32px rgba(57, 92, 107, 0.1);
            transform: translateY(-3px);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
            background: var(--ink);
        }

        .stat-number {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            color: var(--deep);
            line-height: 1;
            margin-bottom: 0.3rem;
        }

        .stat-label {
            font-size: 0.72rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--ink);
            opacity: 0.5;
        }

        /* Menu panel */
        .menu-panel {
            border: 1px solid rgba(57, 92, 107, 0.18);
            background: var(--cream);
        }

        .menu-panel-header {
            background: var(--deep);
            color: var(--cream);
            padding: 1rem 1.6rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu-panel-header h5 {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            margin: 0;
            font-weight: 400;
        }

        .menu-panel-body {
            padding: 1.6rem;
        }

        .menu-type-tag {
            display: inline-block;
            padding: 0.28rem 0.85rem;
            background: var(--sand);
            font-size: 0.7rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--ink);
            margin-bottom: 1.4rem;
        }

        .plate-row {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(57, 92, 107, 0.1);
        }

        .plate-row:last-child {
            border-bottom: none;
        }

        .plate-number {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            border: 1px solid rgba(57, 92, 107, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.68rem;
            letter-spacing: 0.05em;
            color: var(--ink);
            opacity: 0.7;
        }

        .plate-name {
            font-size: 0.95rem;
            color: var(--ink);
            padding-top: 0.1rem;
        }

        /* Reservations table */
        .reservations-panel {
            border: 1px solid rgba(57, 92, 107, 0.18);
            background: var(--cream);
        }

        .reservations-panel-header {
            background: var(--ink);
            color: var(--cream);
            padding: 1rem 1.6rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .reservations-panel-header h5 {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            margin: 0;
            font-weight: 400;
        }

        .count-pill {
            background: var(--cream);
            color: var(--ink);
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            padding: 0.2rem 0.7rem;
        }

        .res-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }

        .res-table thead tr {
            border-bottom: 1px solid rgba(57, 92, 107, 0.2);
        }

        .res-table thead th {
            font-size: 0.68rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--ink);
            opacity: 0.45;
            padding: 1rem 1.6rem 0.7rem;
            font-weight: 500;
        }

        .res-table tbody tr {
            border-bottom: 1px solid rgba(57, 92, 107, 0.08);
            transition: background 0.15s;
        }

        .res-table tbody tr:hover {
            background: rgba(57, 92, 107, 0.04);
        }

        .res-table tbody tr:last-child {
            border-bottom: none;
        }

        .res-table tbody td {
            padding: 0.85rem 1.6rem;
            color: var(--ink);
        }

        .allergy-tag {
            display: inline-block;
            padding: 0.2rem 0.65rem;
            background: rgba(57, 92, 107, 0.08);
            font-size: 0.72rem;
            letter-spacing: 0.06em;
            color: var(--ink);
        }

        .allergy-tag.none {
            opacity: 0.35;
        }


        /* Meal type tags */
        .meal-type-tag {
            display: inline-block;
            padding: 0.2rem 0.65rem;
            font-size: 0.7rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            background: var(--sand);
            color: var(--ink);
        }

        .meal-breakfast {
            background: #f0ead2;
        }

        .meal-lunch {
            background: #d8eadf;
        }

        .meal-dinner {
            background: #dce3ea;
        }

        .vegan-tag {
            display: inline-block;
            padding: 0.2rem 0.65rem;
            background: rgba(80, 140, 90, 0.12);
            color: #3a7a48;
            font-size: 0.7rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .user-type-tag {
            display: inline-block;
            padding: 0.12rem 0.5rem;
            background: rgba(57, 92, 107, 0.08);
            font-size: 0.65rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ink);
            opacity: 0.6;
            margin-left: 0.4rem;
            vertical-align: middle;
        }

        /* ─── DATE NAVIGATOR ─── */
        .date-nav {
            display: flex;
            align-items: center;
            gap: 0;
            border: 1px solid rgba(57, 92, 107, 0.25);
            width: fit-content;
        }

        .date-nav-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            background: transparent;
            border: none;
            color: var(--ink);
            text-decoration: none;
            font-size: 1rem;
            transition: background 0.2s, color 0.2s;
            flex-shrink: 0;
        }

        .date-nav-btn:hover {
            background: var(--ink);
            color: var(--cream);
        }

        .date-nav-center {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0 1.2rem;
            border-left: 1px solid rgba(57, 92, 107, 0.2);
            border-right: 1px solid rgba(57, 92, 107, 0.2);
            height: 42px;
        }

        .date-nav-input {
            border: none;
            background: transparent;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.82rem;
            letter-spacing: 0.05em;
            color: var(--ink);
            outline: none;
            cursor: pointer;
            width: 130px;
        }

        .date-nav-input::-webkit-calendar-picker-indicator {
            opacity: 0.4;
            cursor: pointer;
        }

        .today-btn {
            padding: 0 1rem;
            height: 42px;
            background: transparent;
            border: none;
            border-left: 1px solid rgba(57, 92, 107, 0.2);
            font-size: 0.7rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--ink);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: background 0.2s, color 0.2s;
        }

        .today-btn:hover,
        .today-btn.active {
            background: var(--ink);
            color: var(--cream);
        }

        .no-menu-msg {
            padding: 2rem 1.6rem;
            text-align: center;
        }

        .no-menu-msg p {
            font-size: 0.88rem;
            opacity: 0.5;
            letter-spacing: 0.06em;
        }
    </style>
</head>

<body>

    <!-- ─── HEADER ─── -->
    <header class="custom-header">
        <div class="container-fluid px-4 py-3 d-flex align-items-center justify-content-between">
            <div>
                <a class="logo-box d-flex justify-content-center align-items-center text-decoration-none ms-3"
                    style="width:160px;height:60px;font-size:0.9rem;" href="index.php">
                    <img src="img/logo_uni_canteen.png" alt="Logo" style="width:100%;height:100%;object-fit:contain;">
                </a>
                <span style="font-size:0.68rem; letter-spacing:0.18em; text-transform:uppercase; opacity:0.4; margin-left:0.8rem;">
                    Staff Portal
                </span>
            </div>
            <nav class="d-flex gap-1 align-items-center">
                <a href="#" class="nav-btn px-3 py-2">Dashboard</a>
                <a href="../School_Canteen/menu_admin.php" class="nav-btn px-3 py-2">Menu</a>
                <a href="#" class="nav-btn px-3 py-2">Reports</a>
                <a href="worker_login.php" class="nav-btn px-3 py-2" style="opacity:0.5;">Sign out</a>
            </nav>
        </div>
    </header>

    <!-- ─── DASHBOARD ─── -->
    <div class="dashboard-wrapper">
        <div class="container-lg px-4">

            <!-- Greeting + Date Navigator -->
            <div class="mb-5 fade-up d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-4">
                <div>
                    <p class="section-eyebrow mb-2">
                        <?php echo $isToday ? 'Today &mdash; ' : ''; ?><?php echo date('l, d F Y', strtotime($selectedDate)); ?>
                    </p>
                    <h1 class="dashboard-greeting">
                        Good <?php echo (date('H') < 12) ? 'morning' : (date('H') < 18 ? 'afternoon' : 'evening') ?>,
                        <span><?php echo htmlspecialchars($worker['Name']); ?></span>
                    </h1>
                    <div class="mt-2">
                        <span class="worker-role-badge"><?php echo htmlspecialchars($worker['Post'] ?? 'Kitchen Staff'); ?></span>
                    </div>
                </div>

                <!-- Date Navigator -->
                <div class="date-nav">
                    <a href="?date=<?php echo $prevDate; ?>" class="date-nav-btn" title="Previous day">&#8592;</a>
                    <div class="date-nav-center">
                        <form method="GET" style="margin:0;">
                            <input
                                type="date"
                                name="date"
                                class="date-nav-input"
                                value="<?php echo $selectedDate; ?>"
                                onchange="this.form.submit()">
                        </form>
                    </div>
                    <a href="?date=<?php echo $nextDate; ?>" class="date-nav-btn" title="Next day">&#8594;</a>
                    <?php if (!$isToday): ?>
                        <a href="?" class="today-btn">Today</a>
                    <?php else: ?>
                        <span class="today-btn active">Today</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Ornament -->
            <div class="ornament fade-up delay-1" style="margin-top:0; margin-bottom:2.5rem;">
                <div class="ornament-line"></div>
                <div class="ornament-dot"></div>
                <div class="ornament-dot"></div>
                <div class="ornament-dot"></div>
                <div class="ornament-line"></div>
            </div>

            <!-- ─── STATS ROW ─── -->
            <div class="row g-3 mb-5 fade-up delay-1">
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $totalMeals; ?></div>
                        <div class="stat-label">Meals today</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $allergyCount; ?></div>
                        <div class="stat-label">Allergy requests</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo ($totalMeals > 0) ? $totalMeals - $allergyCount : 0; ?></div>
                        <div class="stat-label">Standard meals</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo ($totalMeals > 0) ? round(($allergyCount / $totalMeals) * 100) : 0; ?>%</div>
                        <div class="stat-label">Allergy rate</div>
                    </div>
                </div>
            </div>

            <!-- ─── MENUS ROW ─── -->
            <div class="mb-4 fade-up delay-2">
                <p class="section-eyebrow mb-3">Menus &mdash; <?php echo date('d M Y', strtotime($selectedDate)); ?></p>

                <?php if (!empty($menus)): ?>
                    <div class="row g-3">
                        <?php
                        $count = count($menus);
                        $colClass = $count === 1 ? 'col-md-4' : ($count === 2 ? 'col-md-6' : 'col-md-4');
                        foreach ($menus as $menu):
                        ?>
                            <div class="<?php echo $colClass; ?>">
                                <div class="menu-panel h-100">
                                    <div class="menu-panel-header">
                                        <h5><?php echo htmlspecialchars($menu['Type'] ?? 'Menu'); ?></h5>
                                    </div>
                                    <div class="menu-panel-body">
                                        <div class="plate-row">
                                            <div class="plate-number">1</div>
                                            <div class="plate-name"><?php echo htmlspecialchars($menu['plate1'] ?? '—'); ?></div>
                                        </div>
                                        <div class="plate-row">
                                            <div class="plate-number">2</div>
                                            <div class="plate-name"><?php echo htmlspecialchars($menu['plate2'] ?? '—'); ?></div>
                                        </div>
                                        <div class="plate-row">
                                            <div class="plate-number">3</div>
                                            <div class="plate-name"><?php echo htmlspecialchars($menu['plate3'] ?? '—'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="menu-panel">
                        <div class="no-menu-msg">
                            <p>No menus set for today.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ─── RESERVATIONS ─── -->
            <div class="row g-4 fade-up delay-3">

                <!-- Reservations List -->
                <div class="col-12">
                    <div class="reservations-panel h-100">
                        <div class="reservations-panel-header">
                            <h5>Reservations</h5>
                            <span class="count-pill"><?php echo $totalMeals; ?> today</span>
                        </div>

                        <?php if (!empty($reservations)): ?>
                            <div class="table-responsive">
                                <table class="res-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Guest</th>
                                            <th>Meal</th>
                                            <th>Allergies</th>
                                            <th>Vegan</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($reservations as $i => $r): ?>
                                            <tr>
                                                <td style="opacity:0.3; font-size:0.78rem;"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></td>
                                                <td>
                                                    <?php echo htmlspecialchars($r['Name'] . ' ' . $r['Surname']); ?>
                                                    <span class="user-type-tag"><?php echo $r['Intern'] ? 'Intern' : 'Occasional'; ?></span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $mealType = $r['meal_type'] ?? '';
                                                    $mealClass = strtolower($mealType);
                                                    ?>
                                                    <span class="meal-type-tag meal-<?php echo htmlspecialchars($mealClass); ?>">
                                                        <?php echo htmlspecialchars($mealType ?: '—'); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if (empty($r['Allergies']) || $r['Allergies'] === 'None'): ?>
                                                        <span class="allergy-tag none">None</span>
                                                    <?php else: ?>
                                                        <span class="allergy-tag"><?php echo htmlspecialchars($r['Allergies']); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($r['Vegan']): ?>
                                                        <span class="vegan-tag">Vegan</span>
                                                    <?php else: ?>
                                                        <span style="opacity:0.25; font-size:0.78rem;">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td style="font-family:'Playfair Display',serif; font-size:0.95rem;">
                                                    <?php
                                                    $price = $r['Intern'] ? $r['Price_Intern'] : $r['Price_Ocasional'];
                                                    echo $price ? '€' . number_format($price, 2) : '—';
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="no-menu-msg">
                                <p>No reservations for today.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div><!-- /row -->

        </div><!-- /container -->
    </div><!-- /dashboard-wrapper -->

    <!-- ─── FOOTER ─── -->
    <footer class="custom-footer">
        <div class="container-lg px-4">
            <div class="row g-4 mb-0">
                <div class="col-6 col-md-3">
                    <div class="footer-label">Portal</div>
                    <div class="footer-value">Staff Dashboard</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="footer-label">Date</div>
                    <div class="footer-value"><?php echo date('d F Y'); ?></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="footer-label">Logged in as</div>
                    <div class="footer-value"><?php echo htmlspecialchars($worker['Name']); ?></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="footer-label">Actions</div>
                    <div class="footer-value">
                        <a href="worker_login.php">Sign out</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <img src="img/logo_uni_canteen.png" alt="Logo" style="width:38%;object-fit:contain;opacity:0.85;">
                &mdash; Internal Staff Portal &mdash; <?php echo date('Y'); ?>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
