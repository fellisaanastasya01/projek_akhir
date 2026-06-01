<?php
// models/User.php

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create($nama, $email, $password, $whatsapp, $role = 'User') {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (nama, email, password, nomor_whatsapp, role_type, membership_status, total_point) VALUES (?, ?, ?, ?, ?, 'Regular', 0)");
        return $stmt->execute([$nama, $email, $hashedPassword, $whatsapp, $role]);
    }

    public function updatePoints($id, $points) {
        $stmt = $this->db->prepare("UPDATE users SET total_point = total_point + ? WHERE user_id = ?");
        return $stmt->execute([$points, $id]);
    }

    public function updateMembership($id, $status) {
        $stmt = $this->db->prepare("UPDATE users SET membership_status = ? WHERE user_id = ?");
        return $stmt->execute([$status, $id]);
    }
}
