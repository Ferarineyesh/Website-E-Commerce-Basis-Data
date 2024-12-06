<?php
session_start();

// Initialize cart items or set default
if (!isset($_SESSION['cartItems'])) {
    $_SESSION['cartItems'] = [
        [
            'id' => 1,
            'name' => 'Chitato Snack rasa keju 68 g',
            'price' => 11000,
            'image' => 'item1.png',
            'quantity' => 0
        ],
        [
            'id' => 2,
            'name' => 'Bango Kecap Manis 135mL',
            'price' => 11500,
            'image' => 'item2.png',
            'quantity' => 0
        ]
    ];
}

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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1B3A57;
            margin: 0;
            padding: 0;
            color: white;
        }
        .header {
            background-color: #FFFBF2;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            height: 40px;
        }
        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
            color: #000;
        }
        .nav-links a {
            color: #000;
            text-decoration: none;
        }
        .profile-icon {
            width: 30px;
            height: 30px;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .cart-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }
        .cart-item {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .cart-item-content {
            display: flex;
            gap: 20px;
        }
        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            background-color: white;
            border-radius: 8px;
            padding: 10px;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        .quantity-control button {
            background-color: transparent;
            color: white;
            border: 1px solid white;
            width: 24px;
            height: 24px;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .order-summary {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 20px;
        }
        .proceed-btn {
            background-color: #2E7D32;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 4px;
            margin-top: 10px;
            cursor: pointer;
        }
        .continue-btn {
            background-color: #424242;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 4px;
            margin-top: 10px;
            cursor: pointer;
        }
        .terms {
            font-size: 12px;
            margin: 10px 0;
        }
        .logo-container {
            display: flex;
            align-items: center;
        }
        .logo-text {
            color: #2E7D32;
            font-size: 24px;
            font-weight: bold;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="logo.png" alt="Simple Mart" class="logo">
            <span class="logo-text">SIMPLE MART</span>
        </div>
        <nav class="nav-links">
            <a href="#">Home</a>
            <a href="#">Categories</a>
            <a href="#">Products</a>
            <a href="#">Contact Us</a>
            <img src="profil.png" alt="Profile" class="profile-icon">
        </nav>
    </header>

    <div class="container">
        <h1>MY CARTS</h1>
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
                <button class="proceed-btn">Proceed to Payment</button>
                <button class="continue-btn">Continue Shopping</button>
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
</body>
</html>
