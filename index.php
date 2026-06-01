<?php
// index.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();


// Require config & database
require_once 'config/koneksi.php';

// Require Models
require_once 'models/User.php';
require_once 'models/Booking.php';
require_once 'models/Lapangan.php';

// Require Controllers
require_once 'controllers/AuthController.php';
require_once 'controllers/BookingController.php';
require_once 'controllers/PaymentController.php';

// Initialize Models
$userModel = new User($conn);
$bookingModel = new Booking($conn);
$lapanganModel = new Lapangan($conn);

// Initialize Controllers
$authController = new AuthController($userModel);
$bookingController = new BookingController($bookingModel, $lapanganModel, $userModel);
$paymentController = new PaymentController($bookingModel, $userModel);

// Auth check helper
function checkAuth($allowedRoles = []) {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Silakan login terlebih dahulu.";
        header("Location: index.php?route=login");
        exit;
    }
    if (!empty($allowedRoles) && !in_array($_SESSION['role_type'], $allowedRoles)) {
        http_response_code(403);
        die("Akses ditolak. Peran Anda (" . $_SESSION['role_type'] . ") tidak diperbolehkan mengakses halaman ini.");
    }
}

// Route mapping
$route = $_GET['route'] ?? 'login';

switch ($route) {
    case 'login':
        $authController->login();
        break;
        
    case 'register':
        $authController->register();
        break;
        
    case 'logout':
        $authController->logout();
        break;
        
    case 'user/dashboard':
        checkAuth(['User']);
        $bookingController->userDashboard();
        break;
        
    case 'user/booking_form':
        checkAuth(['User']);
        $bookingController->bookingForm();
        break;
        
    case 'user/create_booking':
        checkAuth(['User']);
        $bookingController->createBooking();
        break;
        
    case 'user/upload_dp':
        checkAuth(['User']);
        $paymentController->uploadDp();
        break;
        
    case 'admin/dashboard':
        checkAuth(['Admin', 'Koordinator']);
        $paymentController->adminDashboard();
        break;
        
    case 'admin/verifikasi_pembayaran':
        checkAuth(['Admin', 'Koordinator']);
        $paymentController->verifyPayment();
        break;
        
    default:
        header("Location: index.php?route=login");
        exit;
}