<?php
session_start();
$_SESSION['cartItems'] = [];
//$ids = $_SESSION["user_id"];
$user = $_SESSION["username"];
include("conn.php");
if(!empty($_SESSION["username"])){
  $logged = true;
}
else{
  $logged=false;
  echo "
  <script>
    alert('Silahkan Login terlebih dahulu!');
    window.location.href = 'login.html';
  </script>
  ";
}
$user_id = (int)$_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM cart_items where user_id=$user_id");
// Initialize cart items or set default
if (!isset($_SESSION['cartItems'])) {
    $_SESSION['cartItems'] = [];
}

function addToCart($id, $name, $price, $image, $quantity) {
  foreach ($_SESSION['cartItems'] as &$item) {
      if ($item['id'] == $id) {
          // Jika item sudah ada, update jumlah
          $item['quantity'] += $quantity;
          return;
      }
  }
  // Jika item belum ada, tambahkan sebagai item baru
  $_SESSION['cartItems'][] = [
      'id' => $id,
      'name' => $name,
      'price' => $price,
      'image' => $image,
      'quantity' => $quantity
  ];
}
$counts=1;
if($query){
  foreach($query as $querys){

    $ids = $querys["product_id"];
    $que = mysqli_query($conn, "SELECT nama,harga,image_url from product where product_id=$ids");
    
    if($que){
      $res = mysqli_fetch_assoc($que);
      if($res){
        addToCart($counts,$res['nama'],$res['harga'],$res['image_url'],$querys['quantity']);
      }
    }
    $counts++;
  }
  
}

$counts--;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'], $_POST['action'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];

    // Update kuantitas item di sesi
    foreach ($_SESSION['cartItems'] as &$item) {
        if ($item['id'] == $id) {
            if ($action == 'increase') {
                $item['quantity']++;
            } elseif ($action == 'decrease' && $item['quantity'] > 0) {
                $item['quantity']--;
            }
            break;
        }
    }

    // Hitung ulang total setelah pembaruan
    $totals = calculateTotals($_SESSION['cartItems']);

    // Respon JSON untuk frontend
    $response = [
        'status' => 'success',
        'itemQuantity' => $_SESSION['cartItems'][$id - 1]['quantity'],
        'itemSubtotal' => $_SESSION['cartItems'][$id - 1]['price'] * $_SESSION['cartItems'][$id - 1]['quantity'],
        'subtotal' => $totals['subtotal'],
        'deliveryFee' => $totals['deliveryFee'],
        'total' => $totals['total']
    ];

    echo json_encode($response);
    exit;
}


// Function to calculate totals
function calculateTotals($cartItems) {
    $subtotal = array_reduce($cartItems, function ($carry, $item) {
        return $carry + ($item['price'] * $item['quantity']);
    }, 0);

    $hasItems = array_reduce($cartItems, function ($carry, $item) {
        return $carry || $item['quantity'] > 0;
    }, false);

    $deliveryFee = $hasItems ? 5500 : 0;
    $total = $subtotal + $deliveryFee;

    return [
        'subtotal' => $subtotal,
        'deliveryFee' => $deliveryFee,
        'total' => $total
    ];
}

$totals = calculateTotals($_SESSION['cartItems']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Carts - Simple Mart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="cart.css">
</head>
<body>
<header class="header">
    <div class="logo-container">
        <img src="./assets/logo.png" alt="Logo" class="logo">
        <h1 class="name">SIMPLE MART</h1>
    </div>
    <nav class="nav-links">
        <a href="index.php" class="nav-link">Home</a>
        <a href="index.php#categories" class="nav-link">Categories</a>
        <a href="index.php#products" class="nav-link">Products</a>
        <a href="cart.php" class="nav-link">Carts</a>
        <a href="index.php#contact" class="nav-link">Contact Us</a>
        <?php if($logged): ?>
        <a href="/profile.php" class="profile-link"><img src="./assets/profil.png" alt="Profile" class="profile-icon"></a>
        <?php else: ?> <a href="login.html" class="nav-link">Login</a>
        <?php endif;?>
    </nav>
</header>

    <div class="container">
        <div class="cart-header">
            <h1>MY CARTS</h1>
            <button class="empty-cart" id="empty">Empty Cart</button>
        </div>
        <div class="cart-grid">
            <div class="cart-items">
                <?php if (is_array($_SESSION['cartItems']) && !empty($_SESSION['cartItems'])): ?>
                    <?php foreach ($_SESSION['cartItems'] as $item): ?>
                        <div class="cart-item" id="item-<?php echo $item['id']; ?>">
                            <div class="cart-item-content">
                                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                <div>
                                    <h3><?php echo $item['name']; ?></h3>
                                    <p>Rp. <?php echo number_format($item['price'], 0, ',', '.'); ?></p>
                                    <div class="quantity-control">
                                        <button onclick="updateQuantity(<?php echo $item['id']; ?>, 'decrease')">-</button>
                                        <span id="quantity-<?php echo $item['id']; ?>"><?php echo $item['quantity']; ?></span>
                                        <button onclick="updateQuantity(<?php echo $item['id']; ?>, 'increase')">+</button>
                                    </div>
                                    <p>Subtotal: Rp. <span id="subtotal-<?php echo $item['id']; ?>">
                                        <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>
                                    </span></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No items in the cart.</p>
                <?php endif; ?>
            </div>
            <div class="order-summary">
                <h2>ORDER SUMMARY | <?php echo count($_SESSION['cartItems']); ?> PRODUCTS</h2>
                <p>Product subtotal: Rp. <span id="subtotal">
                    <?php echo number_format($totals['subtotal'], 0, ',', '.'); ?>
                </span></p>
                <p>Delivery fees: Rp. <span id="deliveryFee">
                    <?php echo number_format($totals['deliveryFee'], 0, ',', '.'); ?>
                </span></p>
                <h3>Total: Rp. <span id="total">
                    <?php echo number_format($totals['total'], 0, ',', '.'); ?>
                </span></h3>
                <p class="terms">By clicking the payment button, you agree to our terms and conditions.</p>
                <button class="proceed-btn" id="payment">PROCEED TO PAYMENT</button>
                <a href="index.php#products"><button class="continue-btn">CONTINUE SHOPPING</button></a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-oBWosVvOtc/bDTsKSC4+dKF6MBj8ODIQegT8vZPb7hZ1Cfln6Ak4KPbbIhA6g11E" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function updateQuantity(id, action) {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}&action=${action}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById(`quantity-${id}`).textContent = data.itemQuantity;
                    document.getElementById(`subtotal-${id}`).textContent = data.itemSubtotal.toLocaleString('id-ID');
                    document.getElementById('subtotal').textContent = data.subtotal.toLocaleString('id-ID');
                    document.getElementById('deliveryFee').textContent = data.deliveryFee.toLocaleString('id-ID');
                    document.getElementById('total').textContent = data.total.toLocaleString('id-ID');
                } else {
                    alert(data.message || 'Error updating cart');
                }
            })
            .catch(error => {
                console.error('Error updating cart:', error);
                alert('An error occurred. Please try again later.');
            });
        }

        //Deleting Items
        function deleting() {
    const confirmation = confirm("Are you sure you want to delete all items?");
    if (confirmation) {
        // Kirim permintaan ke backend untuk menghapus item
        $.ajax({
    url: 'deleteCart.php', // URL file PHP untuk menghapus item
    type: 'POST', // Metode HTTP POST
    contentType: 'application/json', // Header untuk JSON
    data: JSON.stringify({
        user_id: <?php echo json_encode($user_id); ?> // Kirim user_id ke backend
    }),
    success: function (response) {
        try {
            const data = JSON.parse(response); // Parsing response JSON
            if (data.status === 'success') {
                alert("Items deleted!");
                window.location.href = "cart.php"; // Redirect ke halaman cart
            } else {
                alert("Failed to delete items!");
            }
        } catch (e) {
            console.error("Parsing Error:", e);
            alert("Unexpected server response.");
        }
    },
    error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
        alert("An error occurred while processing the request.");
    }
});

    } else {
        alert("Item not deleted!");
    }
}

document.getElementById("empty").addEventListener("click", deleting);

/*function payment() {
    // Kirim data cartItems ke backend untuk proses pembayaran
    $.ajax({
        url: 'toPayment.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            cartItems: <?php echo json_encode($_SESSION['cartItems']); ?>, // Kirim cartItems ke backend
            user_id: <?php echo json_encode($user_id); ?> // Kirim user_id
        }),
        success: function (response) {
            try {
                const data = JSON.parse(response);
                if (data.status === 'success') {
                    alert("Payment successful!");
                    window.location.href = "index.php"; // Redirect ke halaman utama
                } else {
                    alert("Payment failed: " + data.message);
                }
            } catch (e) {
                console.error("Parsing Error:", e);
                alert("Unexpected server response.");
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
            alert("An error occurred while processing the payment.");
        }
    });
}
*/
document.getElementById("payment").addEventListener("click", ()=>{
    window.location.href="./toPayment.php";
});

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-oBWosVvOtc/bDTsKSC4+dKF6MBj8ODIQegT8vZPb7hZ1Cfln6Ak4KPbbIhA6g11E" crossorigin="anonymous"></script>
</body>
</html>