<?php
// views/admin/dashboard.php
include 'views/layout/header.php';

// Calculate some quick stats for the dashboard
$totalBookings = count($bookings);
$pendingVerifikasi = count(array_filter($bookings, function($b) { return $b['status_booking'] === 'Menunggu Verifikasi'; }));
$confirmedBookings = count(array_filter($bookings, function($b) { return $b['status_booking'] === 'Confirmed'; }));
$totalRevenue = array_reduce($bookings, function($carry, $item) {
    // If confirmed, count total revenue
    if ($item['status_booking'] === 'Confirmed') {
        return $carry + $item['total_biaya'];
    }
    return $carry;
}, 0);
?>

<div class="row mb-4 animated-fade-in">
    <!-- Stat 1 -->
    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
        <div class="card card-premium p-3 border-0 bg-primary text-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold mb-1" style="font-size: 0.75rem;">Total Booking</h6>
                    <h3 class="fw-bold mb-0 text-white"><?= $totalBookings ?></h3>
                </div>
                <div class="bg-white-10 rounded-3 p-2" style="background: rgba(255,255,255,0.15)">
                    <i class="bi bi-calendar-event fs-3 text-white"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Stat 2 -->
    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
        <div class="card card-premium p-3 border-0 bg-warning text-dark h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-dark-50 text-uppercase fw-bold mb-1" style="font-size: 0.75rem;">Menunggu Verifikasi</h6>
                    <h3 class="fw-bold mb-0 text-dark"><?= $pendingVerifikasi ?></h3>
                </div>
                <div class="bg-black-10 rounded-3 p-2" style="background: rgba(0,0,0,0.1)">
                    <i class="bi bi-clock-history fs-3 text-dark"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Stat 3 -->
    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
        <div class="card card-premium p-3 border-0 bg-success text-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold mb-1" style="font-size: 0.75rem;">Booking Disetujui</h6>
                    <h3 class="fw-bold mb-0 text-white"><?= $confirmedBookings ?></h3>
                </div>
                <div class="bg-white-10 rounded-3 p-2" style="background: rgba(255,255,255,0.15)">
                    <i class="bi bi-check-circle fs-3 text-white"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Stat 4 -->
    <div class="col-md-3 col-sm-6">
        <div class="card card-premium p-3 border-0 bg-info text-white h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold mb-1" style="font-size: 0.75rem;">Total Pendapatan</h6>
                    <h3 class="fw-bold mb-0 text-white" style="font-size: 1.5rem;">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></h3>
                </div>
                <div class="bg-white-10 rounded-3 p-2" style="background: rgba(255,255,255,0.15)">
                    <i class="bi bi-cash-stack fs-3 text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row animated-fade-in">
    <div class="col-12">
        <div class="card card-premium">
            <div class="card-header-premium d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold"><i class="bi bi-journal-list me-2"></i>Semua Data Booking Lapangan</h4>
                <?php if ($pendingVerifikasi > 0): ?>
                    <a href="index.php?route=admin/verifikasi_pembayaran" class="btn btn-warning btn-sm fw-semibold rounded-pill px-3">
                        <i class="bi bi-shield-exclamation me-1"></i> Ada <?= $pendingVerifikasi ?> Verifikasi
                    </a>
                <?php endif; ?>
            </div>
            <div class="card-body p-0">
                <?php if (empty($bookings)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                        <p class="mb-0">Belum ada pemesanan dalam sistem.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="min-width: 900px;">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Pelanggan</th>
                                    <th>Lapangan</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Durasi</th>
                                    <th>Biaya</th>
                                    <th>Status</th>
                                    <th class="pe-4 text-center">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $b): ?>
                                    <tr>
                                        <td class="ps-4 fw-semibold text-primary">#<?= $b['booking_id'] ?></td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($b['nama_user']) ?></div>
                                            <small class="text-muted"><i class="bi bi-whatsapp text-success me-1"></i><?= htmlspecialchars($b['nomor_whatsapp']) ?></small>
                                        </td>
                                        <td><?= htmlspecialchars($b['nama_lapangan']) ?></td>
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
                                            <?php if ($b['status_booking'] === 'Menunggu Verifikasi'): ?>
                                                <a href="index.php?route=admin/verifikasi_pembayaran" class="btn btn-warning btn-sm rounded-pill px-3 fw-semibold">
                                                    Verifikasi <i class="bi bi-arrow-right-short"></i>
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
