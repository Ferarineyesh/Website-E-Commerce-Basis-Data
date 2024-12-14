<?php
include("conn.php");
session_start();

if (empty($_SESSION["username"])) {
    echo "<script>
        alert('Unexpected server response.');
        window.location.href = 'login.php';
    </script>";
    exit;
}

if (empty($_SESSION["cartItems"])) {
    header("Location: index.php");
    exit;
}

$success = true;

foreach ($_SESSION["cartItems"] as $items) {
    $prod_id = $items["id"];
    $prices = $items["price"];
    $names = mysqli_real_escape_string($conn, $items["name"]);
    $quantity = $items["quantity"];
    $user_id = (int)$_SESSION["user_id"];

    $query = mysqli_query($conn, "UPDATE cart_items SET quantity = $quantity, price = $prices WHERE user_id = $user_id AND product_id = (SELECT product_id FROM product WHERE name = '$names' LIMIT 1)");

    if (!$query) {
        $success = false;
        break;
    }
}

if ($success) {
    // Jika semua update berhasil, redirect ke halaman pembayaran
    header("Location: pembayaran.php");
} else {
    // Jika ada kesalahan, kembali ke halaman utama
    echo "<script>
        alert('Error updating cart items.');
        window.location.href = 'index.php';
    </script>";
}
?>
