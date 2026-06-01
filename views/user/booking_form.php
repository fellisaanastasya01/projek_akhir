<?php
// views/user/booking_form.php
include 'views/layout/header.php';
?>

<div class="row animated-fade-in">
    <div class="col-md-5 mb-4 mb-md-0">
        <div class="card card-premium h-100">
            <div class="card-header-premium">
                <h4 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i>Daftar Lapangan & Harga</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">Berikut adalah daftar lapangan olahraga yang tersedia beserta harga sewa per jam:</p>
                <div class="list-group list-group-flush mt-3">
                    <?php foreach ($lapangans as $lap): ?>
                        <div class="list-group-item bg-transparent px-0 py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-bold text-dark"><?= htmlspecialchars($lap['nama_lapangan']) ?></h6>
                                <small class="badge bg-secondary mt-1"><?= htmlspecialchars($lap['jenis_lapangan']) ?></small>
                                <?php if ($lap['status_lapangan'] === 'Maintenance'): ?>
                                    <small class="badge bg-danger mt-1">Perbaikan</small>
                                <?php else: ?>
                                    <small class="badge bg-success mt-1">Tersedia</small>
                                <?php endif; ?>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-success fs-5">Rp <?= number_format($lap['harga_per_jam'], 0, ',', '.') ?></span>
                                <small class="d-block text-muted">/ jam</small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card card-premium">
            <div class="card-header-premium">
                <h4 class="mb-0 fw-bold"><i class="bi bi-calendar-plus-fill me-2"></i>Form Pemesanan Lapangan</h4>
            </div>
            <div class="card-body p-4">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>
                            <?= htmlspecialchars($_SESSION['error']) ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?route=user/create_booking" id="bookingForm">
                    <div class="mb-3">
                        <label for="lapangan_id" class="form-label fw-semibold">Pilih Lapangan</label>
                        <select name="lapangan_id" id="lapangan_id" class="form-select form-control-premium" required>
                            <option value="" disabled selected>-- Pilih Lapangan --</option>
                            <?php foreach ($lapangans as $lap): ?>
                                <?php if ($lap['status_lapangan'] === 'Tersedia'): ?>
                                    <option value="<?= $lap['lapangan_id'] ?>" data-price="<?= $lap['harga_per_jam'] ?>">
                                        <?= htmlspecialchars($lap['nama_lapangan']) ?> - (Rp <?= number_format($lap['harga_per_jam'], 0, ',', '.') ?>/jam)
                                    </option>
                                <?php else: ?>
                                    <option value="<?= $lap['lapangan_id'] ?>" disabled>
                                        <?= htmlspecialchars($lap['nama_lapangan']) ?> - (Sedang Perbaikan)
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal" class="form-label fw-semibold">Tanggal Main</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control form-control-premium" min="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jam_mulai" class="form-label fw-semibold">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control form-control-premium" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="durasi" class="form-label fw-semibold">Durasi Sewa (Jam)</label>
                        <input type="number" name="durasi" id="durasi" class="form-control form-control-premium" min="1" max="12" placeholder="Masukkan durasi sewa" required>
                    </div>

                    <!-- Dynamic Cost Preview Box -->
                    <div class="card bg-light border-0 p-3 mb-4 rounded-3 d-none" id="costCalculatorBox">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted">Harga per jam:</span>
                            <span id="ratePerHour" class="fw-semibold">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Durasi sewa:</span>
                            <span id="selectDuration" class="fw-semibold">0 Jam</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Estimasi Total Biaya:</span>
                            <span id="estimatedCost" class="fw-bold text-success fs-5">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <span class="text-muted text-start" style="font-size: 0.75rem;"><i class="bi bi-info-circle-fill text-warning me-1"></i>Min. DP pembayaran 50%:</span>
                            <span id="estimatedDp" class="fw-bold text-primary" style="font-size: 0.85rem;">Rp 0</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?route=user/dashboard" class="btn btn-secondary-premium">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-premium">
                            Konfirmasi Booking <i class="bi bi-chevron-right ms-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const lapanganSelect = document.getElementById('lapangan_id');
    const durasiInput = document.getElementById('durasi');
    const costBox = document.getElementById('costCalculatorBox');
    
    const ratePerHourSpan = document.getElementById('ratePerHour');
    const selectDurationSpan = document.getElementById('selectDuration');
    const estimatedCostSpan = document.getElementById('estimatedCost');
    const estimatedDpSpan = document.getElementById('estimatedDp');

    function calculateEstimate() {
        const selectedOption = lapanganSelect.options[lapanganSelect.selectedIndex];
        const duration = parseInt(durasiInput.value) || 0;

        if (selectedOption && selectedOption.value && duration > 0) {
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const total = price * duration;
            const dp = total / 2;

            ratePerHourSpan.innerText = 'Rp ' + price.toLocaleString('id-ID');
            selectDurationSpan.innerText = duration + ' Jam';
            estimatedCostSpan.innerText = 'Rp ' + total.toLocaleString('id-ID');
            estimatedDpSpan.innerText = 'Rp ' + dp.toLocaleString('id-ID');

            costBox.classList.remove('d-none');
        } else {
            costBox.classList.add('d-none');
        }
    }

    lapanganSelect.addEventListener('change', calculateEstimate);
    durasiInput.addEventListener('input', calculateEstimate);
});
</script>

<?php
include 'views/layout/footer.php';
?>
