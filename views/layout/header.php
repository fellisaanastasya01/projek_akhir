<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPLJ - Sistem Pemesanan Lapangan Jakabaring</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-premium shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <i class="bi bi-trophy-fill text-warning me-2 fs-4"></i>
            <span class="fw-bold tracking-wide">SPLJ <span class="text-warning text-accent">JAKABARING</span></span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role_type'] === 'Admin' || $_SESSION['role_type'] === 'Koordinator'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= (($_GET['route'] ?? '') === 'admin/dashboard') ? 'active' : '' ?>" href="index.php?route=admin/dashboard">
                                <i class="bi bi-speedometer2 me-1"></i> Dashboard Admin
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (($_GET['route'] ?? '') === 'admin/verifikasi_pembayaran') ? 'active' : '' ?>" href="index.php?route=admin/verifikasi_pembayaran">
                                <i class="bi bi-shield-check me-1"></i> Verifikasi
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?= (($_GET['route'] ?? '') === 'user/dashboard') ? 'active' : '' ?>" href="index.php?route=user/dashboard">
                                <i class="bi bi-grid-fill me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (($_GET['route'] ?? '') === 'user/booking_form') ? 'active' : '' ?>" href="index.php?route=user/booking_form">
                                <i class="bi bi-calendar-plus-fill me-1"></i> Booking Lapangan
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <div class="dropdown">
                            <button class="btn btn-outline-warning btn-sm dropdown-toggle px-3 rounded-pill" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($_SESSION['nama']) ?> 
                                <span class="badge bg-dark text-warning ms-1"><?= $_SESSION['role_type'] ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item text-danger" href="index.php?route=logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (($_GET['route'] ?? 'login') === 'login') ? 'active' : '' ?>" href="index.php?route=login">Login</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-warning rounded-pill px-4 btn-sm fw-semibold" href="index.php?route=register">Daftar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="content-wrapper py-4">
    <div class="container">
