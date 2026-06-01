<?php
// controllers/BookingController.php

class BookingController {
    private $bookingModel;
    private $lapanganModel;
    private $userModel;

    public function __construct($bookingModel, $lapanganModel, $userModel) {
        $this->bookingModel = $bookingModel;
        $this->lapanganModel = $lapanganModel;
        $this->userModel = $userModel;
    }

    public function userDashboard() {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        $bookings = $this->bookingModel->findByUser($userId);

        include 'views/user/dashboard.php';
    }

    public function bookingForm() {
        $lapangans = $this->lapanganModel->all();
        include 'views/user/booking_form.php';
    }

    public function createBooking() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?route=user/booking_form");
            exit;
        }

        $lapanganId = (int)($_POST['lapangan_id'] ?? 0);
        $tanggal = $_POST['tanggal'] ?? '';
        $jamMulai = $_POST['jam_mulai'] ?? '';
        $durasi = (int)($_POST['durasi'] ?? 0);

        if (empty($lapanganId) || empty($tanggal) || empty($jamMulai) || empty($durasi)) {
            $_SESSION['error'] = "Semua input booking wajib diisi.";
            header("Location: index.php?route=user/booking_form");
            exit;
        }

        $lapangan = $this->lapanganModel->findById($lapanganId);
        if (!$lapangan || $lapangan['status_lapangan'] !== 'Tersedia') {
            $_SESSION['error'] = "Lapangan tidak tersedia atau tidak ditemukan.";
            header("Location: index.php?route=user/booking_form");
            exit;
        }

        // Schedule Conflict Validation
        $hasConflict = $this->bookingModel->checkConflict($lapanganId, $tanggal, $jamMulai, $durasi);
        if ($hasConflict) {
            $_SESSION['error'] = "Jadwal bentrok dengan pemesanan lain. Silakan pilih waktu/lapangan lain.";
            header("Location: index.php?route=user/booking_form");
            exit;
        }

        // Calculate total cost
        $totalBiaya = $lapangan['harga_per_jam'] * $durasi;
        $userId = $_SESSION['user_id'];

        $bookingId = $this->bookingModel->create($userId, $lapanganId, $tanggal, $jamMulai, $durasi, $totalBiaya);

        if ($bookingId) {
            $_SESSION['success'] = "Pemesanan berhasil dibuat! Silakan upload bukti pembayaran DP.";
            header("Location: index.php?route=user/upload_dp&booking_id=" . $bookingId);
        } else {
            $_SESSION['error'] = "Gagal membuat pemesanan, silakan coba lagi.";
            header("Location: index.php?route=user/booking_form");
        }
        exit;
    }
}
