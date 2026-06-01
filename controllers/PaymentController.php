<?php
// controllers/PaymentController.php

class PaymentController {
    private $bookingModel;
    private $userModel;

    public function __construct($bookingModel, $userModel) {
        $this->bookingModel = $bookingModel;
        $this->userModel = $userModel;
    }

    public function uploadDp() {
        $bookingId = (int)($_GET['booking_id'] ?? 0);
        if (!$bookingId) {
            $_SESSION['error'] = "Pemesanan tidak ditemukan.";
            header("Location: index.php?route=user/dashboard");
            exit;
        }

        $booking = $this->bookingModel->findById($bookingId);
        if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Akses ditolak atau pemesanan tidak ditemukan.";
            header("Location: index.php?route=user/dashboard");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_FILES['bukti_transfer']) || $_FILES['bukti_transfer']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['error'] = "File bukti transfer wajib diupload.";
                include 'views/user/upload_dp.php';
                return;
            }

            $fileTmpPath = $_FILES['bukti_transfer']['tmp_name'];
            $fileName = $_FILES['bukti_transfer']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
            if (!in_array($fileExtension, $allowedExtensions)) {
                $_SESSION['error'] = "Format file tidak didukung. Harap upload JPG, JPEG, PNG, atau PDF.";
                include 'views/user/upload_dp.php';
                return;
            }

            // Create uploads directory if not exists
            $uploadFileDir = 'uploads/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $newFileName = 'bukti_' . $bookingId . '_' . time() . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // DP is 50% of the total biaya
                $dpAmount = $booking['total_biaya'] / 2;

                // Add payment record
                $this->bookingModel->addPayment($bookingId, 'DP', $dpAmount, $dest_path);

                // Update booking status to 'Menunggu Verifikasi'
                $this->bookingModel->updateStatus($bookingId, 'Menunggu Verifikasi');

                $_SESSION['success'] = "Bukti transfer berhasil diupload! Menunggu verifikasi admin.";
                header("Location: index.php?route=user/dashboard");
                exit;
            } else {
                $_SESSION['error'] = "Terjadi kesalahan saat menyimpan file bukti transfer.";
            }
        }

        include 'views/user/upload_dp.php';
    }

    public function adminDashboard() {
        $bookings = $this->bookingModel->allWithDetails();
        include 'views/admin/dashboard.php';
    }

    public function verifyPayment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookingId = (int)($_POST['booking_id'] ?? 0);
            $action = $_POST['action'] ?? '';

            $booking = $this->bookingModel->findById($bookingId);
            if (!$booking) {
                $_SESSION['error'] = "Pemesanan tidak ditemukan.";
                header("Location: index.php?route=admin/verifikasi_pembayaran");
                exit;
            }

            $payment = $this->bookingModel->getPaymentByBookingId($bookingId);

            if ($action === 'approve') {
                $this->bookingModel->updateStatus($bookingId, 'Confirmed');
                if ($payment) {
                    $this->bookingModel->updatePaymentStatus($payment['pembayaran_id'], 'Verified');
                }
                // Reward user with loyalty points (10 points per booking)
                $this->userModel->updatePoints($booking['user_id'], 10);

                // Check if user should get membership status upgrade
                $user = $this->userModel->findById($booking['user_id']);
                if ($user && $user['total_point'] >= 100 && $user['membership_status'] === 'Regular') {
                    $this->userModel->updateMembership($booking['user_id'], 'Premium');
                }

                $_SESSION['success'] = "Pembayaran booking #$bookingId berhasil disetujui.";
            } elseif ($action === 'reject') {
                $this->bookingModel->updateStatus($bookingId, 'Rejected');
                if ($payment) {
                    $this->bookingModel->updatePaymentStatus($payment['pembayaran_id'], 'Rejected');
                }
                $_SESSION['success'] = "Pembayaran booking #$bookingId ditolak.";
            }

            header("Location: index.php?route=admin/verifikasi_pembayaran");
            exit;
        }

        // Fetch all bookings that are waiting for verification
        $allBookings = $this->bookingModel->allWithDetails();
        $pendingBookings = array_filter($allBookings, function($b) {
            return $b['status_booking'] === 'Menunggu Verifikasi';
        });

        // For each pending booking, attach the payment details
        foreach ($pendingBookings as &$b) {
            $b['payment'] = $this->bookingModel->getPaymentByBookingId($b['booking_id']);
        }
        unset($b);

        include 'views/admin/verifikasi_pembayaran.php';
    }
}
