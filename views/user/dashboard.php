<?php
// views/user/dashboard.php
include 'views/layout/header.php';
?>

<div class="row mb-4 animated-fade-in">
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="user-profile-card d-flex flex-column justify-content-between h-100">
            <div>
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-circle bg-warning text-dark fw-bold me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 50%; font-size: 1.5rem;">
                        <?= strtoupper(substr($user['nama'], 0, 1)) ?>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-white"><?= htmlspecialchars($user['nama']) ?></h4>
                        <span class="badge bg-warning text-dark fw-semibold mt-1">Status: <?= htmlspecialchars($user['membership_status']) ?></span>
                    </div>
                </div>
                <hr class="border-secondary">
                <p class="mb-2 text-light"><i class="bi bi-envelope me-2"></i><?= htmlspecialchars($user['email']) ?></p>
                <p class="mb-3 text-light"><i class="bi bi-whatsapp me-2"></i><?= htmlspecialchars($user['nomor_whatsapp']) ?></p>
            </div>
            <div class="mt-3">
                <div class="point-badge d-flex align-items-center">
                    <i class="bi bi-gem me-2 fs-5"></i>
                    <div>
                        <small class="d-block text-white-50" style="font-size: 0.75rem;">Loyalitas Poin</small>
                        <span><?= $user['total_point'] ?> Poin</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card card-premium h-100 p-4 d-flex flex-column justify-content-center">
            <h2 class="fw-bold text-premium mb-2">Selamat Datang di SPLJ!</h2>
            <p class="text-muted mb-4">Nikmati kemudahan memesan lapangan olahraga standar internasional di Kompleks Olahraga Jakabaring secara online. Kumpulkan poin loyalitas dari setiap pemesanan untuk menikmati peningkatan membership status ke Premium.</p>
            <div>
                <a href="index.php?route=user/booking_form" class="btn btn-premium btn-lg">
                    <i class="bi bi-calendar-plus me-2"></i>Pesan Lapangan Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row animated-fade-in">
    <div class="col-12">
        <div class="card card-premium">
            <div class="card-header-premium d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Riwayat Pemesanan Anda</h4>
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

                <?php if (empty($bookings)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                        <p class="mb-0">Anda belum memiliki riwayat pemesanan lapangan.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-4">Kode Booking</th>
                                    <th>Lapangan</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Durasi</th>
                                    <th>Total Biaya</th>
                                    <th>Status</th>
                                    <th class="pe-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $b): ?>
                                    <tr>
                                        <td class="ps-4 fw-semibold text-primary">#<?= $b['booking_id'] ?></td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($b['nama_lapangan']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($b['jenis_lapangan']) ?></small>
                                        </td>
                                        <td>
                                            <div><?= date('d M Y', strtotime($b['tanggal'])) ?></div>
                                            <small class="text-muted"><i class="bi bi-clock me-1"></i><?= substr($b['jam_mulai'], 0, 5) ?> WIB</small>
                                        </td>
                                        <td><?= $b['durasi'] ?> Jam</td>
                                        <td class="fw-semibold">Rp <?= number_format($b['total_biaya'], 0, ',', '.') ?></td>
                                        <td>
                                            <?php 
                                            $badgeClass = '';
                                            switch($b['status_booking']) {
                                                case 'Pending DP': $badgeClass = 'badge-pending'; break;
                                                case 'Menunggu Verifikasi': $badgeClass = 'badge-waiting'; break;
                                                case 'Confirmed': $badgeClass = 'badge-confirmed'; break;
                                                case 'Rejected': $badgeClass = 'badge-rejected'; break;
                                                case 'Canceled': $badgeClass = 'badge-canceled'; break;
                                            }
                                            ?>
                                            <span class="badge-custom <?= $badgeClass ?>"><?= $b['status_booking'] ?></span>
                                        </td>
                                        <td class="pe-4 text-center">
                                            <?php if ($b['status_booking'] === 'Pending DP'): ?>
                                                <a href="index.php?route=user/upload_dp&booking_id=<?= $b['booking_id'] ?>" class="btn btn-warning btn-sm rounded-pill px-3 fw-semibold shadow-sm">
                                                    <i class="bi bi-cloud-upload me-1"></i> Upload DP
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted fs-7">-</span>
                                            <?php endif; ?>
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

<?php
include 'views/layout/footer.php';
?>
