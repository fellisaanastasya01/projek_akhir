<?php
// views/auth/register.php
include 'views/layout/header.php';
?>

<div class="row justify-content-center align-items-center min-vh-75 animated-fade-in">
    <div class="col-md-6">
        <div class="card card-premium">
            <div class="card-header-premium text-center">
                <i class="bi bi-person-plus fs-1 text-warning mb-2"></i>
                <h3 class="fw-bold mb-0">Daftar Akun Baru</h3>
                <p class="text-muted-light mb-0 fs-7">Bergabunglah untuk memesan lapangan dengan mudah</p>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div><?= htmlspecialchars($error) ?></div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div><?= htmlspecialchars($success) ?></div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?route=register">
                    <div class="mb-3">
                        <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" name="nama" id="nama" class="form-control form-control-premium border-start-0" placeholder="Masukkan nama lengkap Anda" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" id="email" class="form-control form-control-premium border-start-0" placeholder="nama@email.com" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="nomor_whatsapp" class="form-label fw-semibold">Nomor WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-whatsapp text-muted"></i></span>
                            <input type="text" name="nomor_whatsapp" id="nomor_whatsapp" class="form-control form-control-premium border-start-0" placeholder="Contoh: 081234567890" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                            <input type="password" name="password" id="password" class="form-control form-control-premium border-start-0" placeholder="Buat password minimal 6 karakter" required>
                        </div>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-premium btn-lg">
                            Daftar Sekarang <i class="bi bi-check2-circle ms-1"></i>
                        </button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <span class="text-muted">Sudah punya akun?</span>
                    <a href="index.php?route=login" class="text-warning fw-semibold text-decoration-none ms-1">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'views/layout/footer.php';
?>
