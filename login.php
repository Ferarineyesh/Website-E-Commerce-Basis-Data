<?php
session_start(); // Mulai sesi untuk menyimpan informasi pengguna
include("conn.php"); // Pastikan `conn.php` menghubungkan ke database

// Periksa apakah form sudah dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil input dari form
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validasi input tidak kosong
    if (empty($username) || empty($password)) {
        echo "<script>alert('Username dan Password wajib diisi!'); window.location.href = 'login.html';</script>";
        exit;
    }

    // Query untuk memeriksa username dan password
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username); // Hindari SQL Injection
    $stmt->execute();
    $result = $stmt->get_result();

    // Periksa apakah pengguna ditemukan
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Login berhasil, simpan informasi pengguna ke sesi
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;

            // Redirect ke halaman utama
            echo "<script>alert('Login berhasil!'); window.location.href = 'index.php';</script>";
            exit;
        } else {
            echo "<script>alert('Password salah!'); window.location.href = 'login.html';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!'); window.location.href = 'login.html';</script>";
        exit;
    }
} else {
    // Jika pengguna mengakses file tanpa mengirimkan form
    header("Location: index.php");
    exit;
}
?>
