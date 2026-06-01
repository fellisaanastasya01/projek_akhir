<?php
// views/auth/login.php
include 'views/layout/header.php';
?>

<div class="row justify-content-center align-items-center min-vh-75 animated-fade-in">
    <div class="col-md-5">
        <div class="card card-premium">
            <div class="card-header-premium text-center">
                <i class="bi bi-person-lock fs-1 text-warning mb-2"></i>
                <h3 class="fw-bold mb-0">Login User</h3>
                <p class="text-muted-light mb-0 fs-7">Sistem Pemesanan Lapangan Jakabaring</p>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div><?= htmlspecialchars($error) ?></div>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div>
                            <?= htmlspecialchars($_SESSION['success']) ?>
                            <?php unset($_SESSION['success']); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?route=login">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" id="email" class="form-control form-control-premium border-start-0" placeholder="nama@email.com" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                            <input type="password" name="password" id="password" class="form-control form-control-premium border-start-0" placeholder="Masukkan password Anda" required>
                        </div>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-premium btn-lg">
                            Masuk <i class="bi bi-box-arrow-in-right ms-1"></i>
                        </button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <span class="text-muted">Belum punya akun?</span>
                    <a href="index.php?route=register" class="text-warning fw-semibold text-decoration-none ms-1">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'views/layout/footer.php';
?>
