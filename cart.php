<?php
session_start();
$_SESSION['cartItems'] = null;
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
    
    // Find the item in the cart
    foreach ($_SESSION['cartItems'] as &$item) {
        if ($item['id'] == $id) {
            // Update quantity based on action
            if ($action == 'increase') {
                $item['quantity']++;
            } elseif ($action == 'decrease' && $item['quantity'] > 0) {
                $item['quantity']--;
            }
            break;
        }
    }

    // Calculate totals
    $totals = calculateTotals($_SESSION['cartItems']);
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
        <a href="#categories" class="nav-link">Categories</a>
        <a href="#products" class="nav-link">Products</a>
        <a href="cart.php" class="nav-link">Carts</a>
        <a href="#contact" class="nav-link">Contact Us</a>
        <?php if($logged): ?>
        <a href="/profile.html" class="profile-link"><img src="./assets/profil.png" alt="Profile" class="profile-icon"></a>
        <?php else: ?> <a href="login.html" class="nav-link">Login</a>
        <?php endif;?>
    </nav>
</header>

    <div class="container">
        <div class="cart-header">
            <h1>MY CARTS</h1>
            <button class="empty-cart">Empty Cart</button>
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
                <a href="pembayaran.php"><button class="proceed-btn">PROCEED TO PAYMENT</button></a>
                <a href="index.php#products"><button class="continue-btn">CONTINUE SHOPPING</button></a>
            </div>
        </div>
    </div>

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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-oBWosVvOtc/bDTsKSC4+dKF6MBj8ODIQegT8vZPb7hZ1Cfln6Ak4KPbbIhA6g11E" crossorigin="anonymous"></script>
</body>
</html>