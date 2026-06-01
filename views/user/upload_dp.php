<?php
// views/user/upload_dp.php
include 'views/layout/header.php';
$dpMin = $booking['total_biaya'] / 2;
?>

<div class="row justify-content-center animated-fade-in">
    <div class="col-md-8">
        <div class="card card-premium">
            <div class="card-header-premium text-center">
                <i class="bi bi-wallet2 fs-1 text-warning mb-2"></i>
                <h3 class="fw-bold mb-0">Pembayaran DP Pemesanan</h3>
                <p class="text-muted-light mb-0 fs-7">Selesaikan pembayaran DP untuk mengamankan slot Anda</p>
            </div>
            
            <div class="card-body p-4">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>
                            <?= htmlspecialchars($_SESSION['error']) ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div>
                            <?= htmlspecialchars($_SESSION['success']) ?>
                            <?php unset($_SESSION['success']); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Booking Summary -->
                <div class="row bg-light rounded-3 p-3 mb-4 g-2">
                    <h5 class="fw-bold mb-2 text-dark border-bottom pb-2">Rincian Booking #<?= $booking['booking_id'] ?></h5>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Nama Lapangan</small>
                        <span class="fw-semibold text-dark"><?= htmlspecialchars($booking['nama_lapangan']) ?> (<?= htmlspecialchars($booking['jenis_lapangan']) ?>)</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Waktu Main</small>
                        <span class="fw-semibold text-dark"><?= date('d F Y', strtotime($booking['tanggal'])) ?> &bull; <?= substr($booking['jam_mulai'], 0, 5) ?> WIB</span>
                    </div>
                    <div class="col-sm-6 mt-2">
                        <small class="text-muted d-block">Durasi Sewa</small>
                        <span class="fw-semibold text-dark"><?= $booking['durasi'] ?> Jam</span>
                    </div>
                    <div class="col-sm-6 mt-2">
                        <small class="text-muted d-block">Total Biaya</small>
                        <span class="fw-semibold text-dark">Rp <?= number_format($booking['total_biaya'], 0, ',', '.') ?></span>
                    </div>
                    <div class="col-12 mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary">Minimal Pembayaran DP (50%)</span>
                        <span class="fw-bold text-success fs-5">Rp <?= number_format($dpMin, 0, ',', '.') ?></span>
                    </div>
                </div>

                <!-- Bank Instructions -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-2"><i class="bi bi-bank me-2"></i>Instruksi Pembayaran Transfer Bank:</h6>
                    <p class="text-muted fs-7 mb-3">Silakan lakukan transfer sejumlah minimal DP di atas ke salah satu rekening resmi SPLJ berikut:</p>
                    
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 border rounded-3 bg-white">
                                <span class="d-block fw-bold text-primary">BANK MANDIRI</span>
                                <span class="d-block fs-5 font-monospace fw-semibold my-1">113-00-1234567-8</span>
                                <small class="text-muted">a.n. Jakabaring Sport Center</small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 border rounded-3 bg-white">
                                <span class="d-block fw-bold text-info">BANK BCA</span>
                                <span class="d-block fs-5 font-monospace fw-semibold my-1">872-09876-54</span>
                                <small class="text-muted">a.n. Jakabaring Sport Center</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Form -->
                <form method="POST" action="index.php?route=user/upload_dp&booking_id=<?= $booking['booking_id'] ?>" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="bukti_transfer" class="form-label fw-bold"><i class="bi bi-file-earmark-arrow-up me-2"></i>Upload Bukti Transfer</label>
                        <div class="upload-container" id="uploadBox">
                            <i class="bi bi-cloud-arrow-up-fill text-muted fs-1 mb-2"></i>
                            <p class="mb-1 fw-semibold text-dark" id="fileNamePlaceholder">Pilih file bukti transfer (JPG, PNG, JPEG, PDF)</p>
                            <small class="text-muted d-block">Maksimal ukuran file 2 MB</small>
                            <input type="file" name="bukti_transfer" id="bukti_transfer" class="d-none" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?route=user/dashboard" class="btn btn-secondary-premium">
                            <i class="bi bi-arrow-left me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-premium">
                            Kirim Bukti Pembayaran <i class="bi bi-send ms-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadBox = document.getElementById('uploadBox');
    const fileInput = document.getElementById('bukti_transfer');
    const placeholderText = document.getElementById('fileNamePlaceholder');

    uploadBox.addEventListener('click', function() {
        fileInput.click();
    });

    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            placeholderText.innerText = "File Terpilih: " + file.name + " (" + (file.size/1024/1024).toFixed(2) + " MB)";
            uploadBox.style.borderColor = "var(--accent-color)";
            uploadBox.style.backgroundColor = "rgba(245, 158, 11, 0.05)";
        } else {
            placeholderText.innerText = "Pilih file bukti transfer (JPG, PNG, JPEG, PDF)";
            uploadBox.style.borderColor = "#cbd5e1";
            uploadBox.style.backgroundColor = "#f8fafc";
        }
    });
});
</script>

<?php
include 'views/layout/footer.php';
?>
