<?php
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
include("conn.php");
$query = mysqli_query($conn, "SELECT * FROM product");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/home.css">
    <title>Simple Mart</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        .card {
            margin: 10px;
            transition: 0.5s;
        }
        .card:hover {
            border: 1.50px solid black;
            transform: scale(1.02);
        }
        .card-img-top {
            width: 150px;
            height: 100px;
            object-fit: cover;
        }
        #keranjang {
            position: fixed;
            bottom: 0;
            visibility: hidden; /* Awalnya disembunyikan */
        }
        .products .primary {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: center;
        }
        .products .column {
            flex: 0 1 calc(25% - 16px);
            box-sizing: border-box;
            margin-bottom: 16px;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
            background-color: #fff;
            padding: 16px;
        }
        .card .item img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 8px;
        }
        .card .item-text p {
            margin: 0 0 8px;
            font-size: 16px;
            font-weight: bold;
        }
        .card .item-price p {
            margin: 0 0 16px;
            color: #28a745;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    
    <header>
        <div class="logo">
            <img src="./assets/logo.png" alt="">
            <h1>SIMPLE MART</h1>
        </div>
        <nav>
            <ul>
                <li><a href="./index.html">Home</a></li>
                <li><a href="#categories">Categories</a></li>
                <li><a href="#products">Products</a></li>
                <li><a href="#contact">Contact Us</a></li>
            </ul>
            <div class="profile">
                <a href=""><img src="./assets/profil.png" alt=""></a>
            </div>
        </nav>
    </header>

    <main>
        <div class="hero-section">
            <img src="./assets/hero.png" alt="">
            <div class="text">
                <h1>FIND YOUR EVERYDAY PRODUCTS</h1>
                <button><a href="">SHOP NOW</a></button>
            </div>
        </div>

        <div class="categories" id="categories">
            <h1>Categories</h1>
        </div>

        <div class="categories-list"></div>

        <div class="products" id="products">
            <div class="top">
                <h1>Our Products</h1>
                <input type="text" placeholder="Search Products" class="search-bar">
            </div>
            <div class="primary">
            <?php
            foreach($query as $querys) {
                echo "<div class='column'>";
                echo "<div class='card' style='width:250px;height:420px;position:relative; align-items:center;'>";
                echo "<div class='item'>";
                echo "<img style='' src='" . $querys["image_url"] . "' alt='Product Image'>";
                echo "</div>";
                echo "<div class='item-text'>";
                echo "<p style='width:100%;'>" . $querys["nama"] . "</p>";
                echo "</div>";
                echo "<div class='item-price'>";
                echo "<p>Rp. " . $querys["harga"] . "</p>";
                echo "</div>";      
                echo "<button class='add-cart' style='position:absolute;bottom:20px; left:50%; transform:translateX(-50%);'>Buy Now</button>";
                echo "</div>";
                echo "</div>";
            }
            ?>
            </div>
        </div>

    </main>

    <div class="card fixed-bottom d-flex" id="keranjang" style="opacity:0.96;">
        <div class="card-header">
            Keranjang
        </div>
        <div class="card-body">
            <h5 class="card-title">Barang Dalam Keranjang</h5>
            <p class="card-text">Jumlah Total: <span id="cart-count">0</span></p>
            <div id="cart-content"></div>
        </div>
    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-left">
                <div class="logo-footer">
                    <img src="./assets/logo.png" alt="">
                    <h1>SIMPLE MART</h1>
                </div>
                <p>Simple Mart is your go-to online store for a wide range of products at unbeatable prices.</p>
                <div class="telp" id="contact">
                    <img src="./assets/wa icon.png" alt="">
                    <a href="tel:+6281234567890">+62-812-3456-7890</a>
                </div>
                <div class="mail">
                    <img src="./assets/mail icon.png" alt="">
                    <a href="mailto:simplemart@gmail.com">simplemart@gmail.com</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        let cartCount = 0;
        const cartElement = document.getElementById('keranjang');
        const cartCountElement = document.getElementById('cart-count');
        const cartContentElement = document.getElementById('cart-content');
        const cartItems = {};

        document.querySelectorAll('.add-cart').forEach((button) => {
            button.addEventListener('click', (e) => {
                const card = e.target.closest('.card');
                const name = card.querySelector('.item-text p').textContent;
                const price = card.querySelector('.item-price p').textContent;

                if (!cartItems[name]) {
                    cartItems[name] = { quantity: 1, price: price };
                } else {
                    cartItems[name].quantity++;
                }

                cartCount++;
                cartCountElement.textContent = cartCount;
                renderCartItems();
                cartElement.style.visibility = 'visible';
            });
        });

        function renderCartItems() {
            cartContentElement.innerHTML = '';
            for (const [name, details] of Object.entries(cartItems)) {
                const div = document.createElement('div');
                div.innerHTML = `
                    <span>${name}</span> - 
                    <span>${details.price}</span> - 
                    <span>Qty: ${details.quantity} 
                    <button class="btn-increase" data-name="${name}">+</button>
                    <button class="btn-decrease" data-name="${name}">-</button></span>
                `;
                cartContentElement.appendChild(div);
            }

            document.querySelectorAll('.btn-increase').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const name = btn.getAttribute('data-name');
                    cartItems[name].quantity++;
                    cartCount++;
                    cartCountElement.textContent = cartCount;
                    renderCartItems();
                });
            });

            document.querySelectorAll('.btn-decrease').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const name = btn.getAttribute('data-name');
                    if (cartItems[name].quantity > 1) {
                        cartItems[name].quantity--;
                    } else {
                        delete cartItems[name];
                    }
                    cartCount--;
                    cartCountElement.textContent = cartCount;
                    renderCartItems();

                    if (cartCount === 0) {
                        cartElement.style.visibility = 'hidden';
                    }
                });
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
