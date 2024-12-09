<?php
include("conn.php"); // File koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($email) || empty($password)) {
        echo "<script>alert('Semua field harus diisi!'); window.location.href = 'signup.html';</script>";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Periksa apakah username atau email sudah ada
    $query_check = "SELECT * FROM users WHERE user_id = ? OR email = ?";
    $stmt_check = $conn->prepare($query_check);

    if (!$stmt_check) {
        die("Query gagal: " . $conn->error);
    }

    $stmt_check->bind_param("ss", $username, $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "<script>alert('Username atau Email sudah terdaftar!'); window.location.href = 'signup.html';</script>";
        exit;
    }

    // Masukkan data ke database
    $query = "INSERT INTO users (first_name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Query gagal: " . $conn->error);
    }

    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href = 'login.html';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat registrasi.'); window.location.href = 'signup.html';</script>";
    }

    $stmt->close();
    $stmt_check->close();
    $conn->close();
} else {
    header("Location: signup.html");
    exit;
}
?>
