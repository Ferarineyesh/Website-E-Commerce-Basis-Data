<?php
session_start();
$user_id = (int)$_SESSION['user_id'];

// Pastikan request menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json'); // Tetapkan jenis respons sebagai JSON

    // Ambil data yang dikirimkan
    $cartData = isset($_POST['cartData']) ? json_decode($_POST['cartData'], true) : null;

    if ($cartData) {
        // Proses data (contoh: menyimpan ke database)
        include 'conn.php'; // Ganti dengan file koneksi database Anda

        foreach ($cartData as $item) {
            // Pastikan $item['name'] adalah string
            if (is_string($item['name'])) {
                // Menghilangkan tanda kutip tunggal dan ganda dari string
                $name = str_replace(["'", '"'], '', $item['name']);  // Menghapus kutip tunggal dan ganda
                
                // Menyaring input untuk mencegah injeksi SQL
                $name = mysqli_real_escape_string($conn, $name);
            } else {
                echo json_encode(["success" => false, "message" => "Data 'name' bukan string!"]);
                exit;
            }

            // Pastikan data lainnya valid
            $price = isset($item['price']) ? (int)$item['price'] : 0;
            $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;

            // Query untuk mengambil product_id berdasarkan nama produk
            $query = "SELECT product_id FROM product WHERE nama='$name'";
            $res = mysqli_query($conn, $query);

            if ($res && mysqli_num_rows($res) > 0) {
                $row = mysqli_fetch_assoc($res);
                $product_id = (int)$row['product_id'];  // Mendapatkan product_id

                // Query untuk memasukkan item ke cart_items
                $insertQuery = "INSERT INTO `cart_items`(`product_id`, `user_id`, `quantity`, `price`) VALUES ($product_id, $user_id, $quantity, $price)";
                $ques = mysqli_query($conn, $insertQuery);

                // Cek apakah query INSERT berhasil
                if (!$ques) {
                    echo json_encode(["success" => false, "message" => "Gagal menyimpan item ke keranjang: " . mysqli_error($conn)]);
                    exit;  // Keluar jika terjadi kesalahan pada query
                }
            } else {
                echo json_encode(["success" => false, "message" => "Produk $name tidak ditemukan."]);
                exit;  // Keluar jika produk tidak ditemukan
            }
        }

        // Kirim respons sukses jika semua item berhasil disimpan
        echo json_encode([
            "success" => true,
            "message" => "Data berhasil disimpan"
        ]);
    } else {
        // Kirim respons gagal jika data tidak valid
        echo json_encode([
            "success" => false,
            "message" => "Data tidak valid"
        ]);
    }
    exit;
} else {
    // Jika bukan POST, kirimkan metode tidak diizinkan
    http_response_code(405); // 405 Method Not Allowed
    echo json_encode([
        "success" => false,
        "message" => "Hanya menerima metode POST"
    ]);
    exit;
}
?>
