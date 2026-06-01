<?php
// views/admin/verifikasi_pembayaran.php
include 'views/layout/header.php';
?>

<div class="row animated-fade-in">
    <div class="col-12">
        <div class="card card-premium">
            <div class="card-header-premium d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold"><i class="bi bi-shield-check me-2"></i>Verifikasi Pembayaran DP</h4>
                <a href="index.php?route=admin/dashboard" class="btn btn-secondary-premium btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Dashboard Admin
                </a>
            </div>
            
            <div class="card-body p-0">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success border-0 rounded-0 m-0 d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div>
                            <?= htmlspecialchars($_SESSION['success']) ?>
                            <?php unset($_SESSION['success']); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger border-0 rounded-0 m-0 d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>
                            <?= htmlspecialchars($_SESSION['error']) ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (empty($pendingBookings)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-shield-check fs-1 text-success d-block mb-3"></i>
                        <p class="mb-0 fw-semibold text-dark fs-5">Semua Pembayaran Bersih!</p>
                        <p class="text-muted">Tidak ada pembayaran yang memerlukan verifikasi saat ini.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="min-width: 900px;">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-4">Detail Booking</th>
                                    <th>Pelanggan</th>
                                    <th>Rincian Biaya</th>
                                    <th>Bukti Transfer</th>
                                    <th class="pe-4 text-center">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendingBookings as $b): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-primary">#<?= $b['booking_id'] ?></div>
                                            <div class="fw-semibold mt-1"><?= htmlspecialchars($b['nama_lapangan']) ?></div>
                                            <small class="text-muted"><i class="bi bi-calendar-event me-1"></i><?= date('d M Y', strtotime($b['tanggal'])) ?></small><br>
                                            <small class="text-muted"><i class="bi bi-clock me-1"></i><?= substr($b['jam_mulai'], 0, 5) ?> WIB (<?= $b['durasi'] ?> Jam)</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($b['nama_user']) ?></div>
                                            <small class="text-muted"><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($b['email']) ?></small><br>
                                            <small class="text-muted"><i class="bi bi-whatsapp text-success me-1"></i><?= htmlspecialchars($b['nomor_whatsapp']) ?></small>
                                        </td>
                                        <td>
                                            <small class="text-muted">Total: Rp <?= number_format($b['total_biaya'], 0, ',', '.') ?></small><br>
                                            <span class="fw-bold text-success">DP (50%): Rp <?= number_format($b['payment']['jumlah_bayar'] ?? ($b['total_biaya']/2), 0, ',', '.') ?></span>
                                        </td>
                                        <td>
                                            <?php if (!empty($b['payment']['bukti_transfer'])): ?>
                                                <a href="<?= htmlspecialchars($b['payment']['bukti_transfer']) ?>" target="_blank" class="d-inline-block text-decoration-none text-center">
                                                    <div class="border rounded p-1 bg-light position-relative hover-zoom" style="width: 100px; height: 70px; overflow: hidden;">
                                                        <?php 
                                                        $ext = strtolower(pathinfo($b['payment']['bukti_transfer'], PATHINFO_EXTENSION));
                                                        if ($ext === 'pdf'): 
                                                        ?>
                                                            <div class="d-flex align-items-center justify-content-center h-100 bg-white">
                                                                <i class="bi bi-file-pdf-fill text-danger fs-2"></i>
                                                            </div>
                                                        <?php else: ?>
                                                            <img src="<?= htmlspecialchars($b['payment']['bukti_transfer']) ?>" alt="Bukti Transfer" class="img-fluid w-100 h-100 object-fit-cover">
                                                        <?php endif; ?>
                                                    </div>
                                                    <small class="d-block text-primary mt-1" style="font-size: 0.75rem;"><i class="bi bi-zoom-in me-1"></i>Perbesar</small>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-danger"><i class="bi bi-x-circle me-1"></i>Belum Upload</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-4 text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Approve Form -->
                                                <form method="POST" action="index.php?route=admin/verifikasi_pembayaran">
                                                    <input type="hidden" name="booking_id" value="<?= $b['booking_id'] ?>">
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                                        <i class="bi bi-check-lg me-1"></i> Approve
                                                    </button>
                                                </form>

                                                <!-- Reject Form -->
                                                <form method="POST" action="index.php?route=admin/verifikasi_pembayaran">
                                                    <input type="hidden" name="booking_id" value="<?= $b['booking_id'] ?>">
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                                        <i class="bi bi-x-lg me-1"></i> Reject
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.hover-zoom {
    transition: transform 0.2s ease-in-out;
}
.hover-zoom:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
.object-fit-cover {
    object-fit: cover;
}
</style>

<?php
include 'views/layout/footer.php';
?>
