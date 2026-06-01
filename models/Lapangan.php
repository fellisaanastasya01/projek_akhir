<?php
// models/Lapangan.php

class Lapangan {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function all() {
        $stmt = $this->db->query("SELECT * FROM lapangan ORDER BY nama_lapangan ASC");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM lapangan WHERE lapangan_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($nama, $jenis, $harga, $status = 'Tersedia') {
        $stmt = $this->db->prepare("INSERT INTO lapangan (nama_lapangan, jenis_lapangan, harga_per_jam, status_lapangan) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nama, $jenis, $harga, $status]);
    }

    public function update($id, $nama, $jenis, $harga, $status) {
        $stmt = $this->db->prepare("UPDATE lapangan SET nama_lapangan = ?, jenis_lapangan = ?, harga_per_jam = ?, status_lapangan = ? WHERE lapangan_id = ?");
        return $stmt->execute([$nama, $jenis, $harga, $status, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM lapangan WHERE lapangan_id = ?");
        return $stmt->execute([$id]);
    }
}
