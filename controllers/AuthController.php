<?php
// controllers/AuthController.php

class AuthController {
    private $userModel;

    public function __construct($userModel) {
        $this->userModel = $userModel;
    }

    public function login() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectDashboard($_SESSION['role_type']);
        }

        $error = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = "Email dan password wajib diisi.";
            } else {
                $user = $this->userModel->findByEmail($email);
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['nama'] = $user['nama'];
                    $_SESSION['role_type'] = $user['role_type'];

                    $this->redirectDashboard($user['role_type']);
                } else {
                    $error = "Email atau password salah.";
                }
            }
        }

        // Include login view
        include 'views/auth/login.php';
    }

    public function register() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectDashboard($_SESSION['role_type']);
        }

        $error = "";
        $success = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $whatsapp = trim($_POST['nomor_whatsapp'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($nama) || empty($email) || empty($whatsapp) || empty($password)) {
                $error = "Semua field wajib diisi.";
            } elseif ($this->userModel->findByEmail($email)) {
                $error = "Email sudah terdaftar.";
            } else {
                if ($this->userModel->create($nama, $email, $password, $whatsapp, 'User')) {
                    $success = "Registrasi berhasil! Silakan login.";
                } else {
                    $error = "Registrasi gagal, coba lagi nanti.";
                }
            }
        }

        // Include register view
        include 'views/auth/register.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?route=login");
        exit;
    }

    private function redirectDashboard($role) {
        if ($role === 'Admin' || $role === 'Koordinator') {
            header("Location: index.php?route=admin/dashboard");
        } else {
            header("Location: index.php?route=user/dashboard");
        }
        exit;
    }
}
