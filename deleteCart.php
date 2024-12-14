<?php
include("conn.php");  // Pastikan koneksi ke database sudah benar

// Ambil data dari permintaan POST (yang dikirim oleh AJAX)
$data = json_decode(file_get_contents("php://input"), true);

// Cek apakah 'user_id' ada dalam data yang diterima
if (isset($data['user_id'])) {
    $user_id = (int)$data['user_id'];  // Pastikan user_id adalah integer

    // Menulis query untuk menghapus item dari cart_items berdasarkan user_id
    $query = mysqli_query($conn, "DELETE FROM cart_items WHERE user_id=$user_id");

    // Cek apakah query berhasil
    if ($query) {
        // Mengembalikan status sukses jika penghapusan berhasil
        echo json_encode(['status' => 'success']);
    } else {
        // Jika ada error dalam query, mengembalikan status error
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete items.']);
    }
} else {
    // Jika user_id tidak ada dalam data yang dikirim, kembalikan status error
    echo json_encode(['status' => 'error', 'message' => 'Invalid user ID.']);
}
?>
