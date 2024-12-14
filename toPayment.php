<?php
include("conn.php");
session_start();

// Ambil data dari POST request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['cartItems'], $data['user_id'])) {
    $cartItems = $data['cartItems'];
    $user_id = (int)$data['user_id'];

    // Proses pembayaran (contoh: simpan ke tabel orders)
    foreach ($cartItems as $item) {
        $product_id = $item['id'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $total = $price * $quantity;
        $names = $item["name"];

        // Simpan ke tabel orders
        $query = mysqli_query($conn, "UPDATE cart_items 
SET   
    quantity = ?, 
    price = ?
WHERE 
    user_id = ?
    AND product_id = (
        SELECT product_id FROM products WHERE name = ? LIMIT 1
    )");

        if (!$query) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to process payment.']);
            exit;
        }
    }

    // Hapus cart items setelah pembayaran berhasil
    $_SESSION['cartItems'] = [];
    mysqli_query($conn, "DELETE FROM cart_items WHERE user_id=$user_id");

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request data.']);
}
?>
