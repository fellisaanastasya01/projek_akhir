<?php
// models/Booking.php

class Booking {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($userId, $lapanganId, $tanggal, $jamMulai, $durasi, $totalBiaya) {
        $stmt = $this->db->prepare("INSERT INTO booking (user_id, lapangan_id, tanggal, jam_mulai, durasi, status_booking, total_biaya) VALUES (?, ?, ?, ?, ?, 'Pending DP', ?)");
        if ($stmt->execute([$userId, $lapanganId, $tanggal, $jamMulai, $durasi, $totalBiaya])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function findById($bookingId) {
        $stmt = $this->db->prepare("
            SELECT b.*, l.nama_lapangan, l.jenis_lapangan, l.harga_per_jam, u.nama as nama_user, u.email, u.nomor_whatsapp 
            FROM booking b
            JOIN lapangan l ON b.lapangan_id = l.lapangan_id
            JOIN users u ON b.user_id = u.user_id
            WHERE b.booking_id = ?
        ");
        $stmt->execute([$bookingId]);
        return $stmt->fetch();
    }

    public function findByUser($userId) {
        $stmt = $this->db->prepare("
            SELECT b.*, l.nama_lapangan, l.jenis_lapangan 
            FROM booking b
            JOIN lapangan l ON b.lapangan_id = l.lapangan_id
            WHERE b.user_id = ?
            ORDER BY b.tanggal DESC, b.jam_mulai DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function allWithDetails() {
        $stmt = $this->db->query("
            SELECT b.*, l.nama_lapangan, u.nama as nama_user, u.nomor_whatsapp 
            FROM booking b
            JOIN lapangan l ON b.lapangan_id = l.lapangan_id
            JOIN users u ON b.user_id = u.user_id
            ORDER BY b.tanggal DESC, b.jam_mulai DESC
        ");
        return $stmt->fetchAll();
    }

    public function checkConflict($lapanganId, $tanggal, $jamMulai, $durasi) {
        // A conflict occurs if there is any booking for the same lapangan and tanggal
        // where the booking's time overlaps with the requested time.
        // Requested interval: [jamMulai, jamMulai + durasi]
        // Existing booking interval: [jam_mulai, jam_mulai + durasi]
        // In SQL, we can convert TIME to seconds or use date_add.
        // Let's do a robust time overlap calculation.
        // An overlap exists if: (startA < endB) AND (endA > startB)
        // For time fields, we can use:
        // jam_mulai < ADDTIME(?, SEC_TO_TIME(? * 3600)) AND ADDTIME(jam_mulai, SEC_TO_TIME(durasi * 3600)) > ?
        // And we only care about bookings that are NOT 'Rejected' and NOT 'Canceled'.
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM booking 
            WHERE lapangan_id = ? 
              AND tanggal = ? 
              AND status_booking NOT IN ('Rejected', 'Canceled')
              AND jam_mulai < ADDTIME(?, SEC_TO_TIME(? * 3600))
              AND ADDTIME(jam_mulai, SEC_TO_TIME(durasi * 3600)) > ?
        ");
        $stmt->execute([
            $lapanganId,
            $tanggal,
            $jamMulai, // endB start boundary
            $durasi,
            $jamMulai  // startB end boundary
        ]);
        $row = $stmt->fetch();
        return $row['count'] > 0;
    }

    public function updateStatus($bookingId, $status) {
        $stmt = $this->db->prepare("UPDATE booking SET status_booking = ? WHERE booking_id = ?");
        return $stmt->execute([$status, $bookingId]);
    }

    // Payment operations inside Booking model or can be used directly
    public function addPayment($bookingId, $paymentType, $jumlahBayar, $buktiTransfer) {
        $stmt = $this->db->prepare("INSERT INTO pembayaran (booking_id, payment_type, jumlah_bayar, bukti_transfer, status_pembayaran) VALUES (?, ?, ?, ?, 'Pending')");
        return $stmt->execute([$bookingId, $paymentType, $jumlahBayar, $buktiTransfer]);
    }

    public function getPaymentByBookingId($bookingId) {
        $stmt = $this->db->prepare("SELECT * FROM pembayaran WHERE booking_id = ? ORDER BY pembayaran_id DESC LIMIT 1");
        $stmt->execute([$bookingId]);
        return $stmt->fetch();
    }

    public function updatePaymentStatus($paymentId, $status) {
        $stmt = $this->db->prepare("UPDATE pembayaran SET status_pembayaran = ? WHERE pembayaran_id = ?");
        return $stmt->execute([$status, $paymentId]);
    }
}
