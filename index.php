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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    
    <header id="logos">
        <div class="logo" id="logos">
            <img src="./assets/logo.png" alt="">
            <h1 class="name">SIMPLE MART</h1>
        </div>
        <section class="header-center">
            <nav>
                <a href="#" class="active">Home</a>
                <a href="#categories">Categories</a>
                <a href="#products">Products</a>
                <a href="cart.php">Cart</a>
                <a href="#contact">Contact Us</a>
                <a href="/profile.html" class="profile"><img src="./assets/profil.png"></a>
            </nav>
            </section>
    </header>

    <main>
        <div class="hero-section">
            <img src="./assets/hero.png" alt="">
            <div class="text">
                <h1 class="title">FIND YOUR EVERYDAY PRODUCTS</h1>
                <button type="button" class="shop-now"><a href="#products">SHOP NOW</a></button> 
            </div>
        </div>

        <div class="categories" id="categories">
            <h1 id="categories">Categories</h1>
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
                echo "<div class='card'>";
                echo "<div class='item'>";
                echo "<img style='' src='" . $querys["image_url"] . "' alt='Product Image'>";
                echo "</div>";
                echo "<div class='item-text'>";
                echo "<p style='width:100%;'>" . $querys["nama"] . "</p>";
                echo "</div>";
                echo "<div class='item-price'>";
                echo "<p>Rp. " . $querys["harga"] . "</p>";
                echo "</div>";      
                echo "<button class='add-cart'>Buy Now</button>";
                echo "</div>";
                echo "</div>";
            }
            ?>
            </div>
        </div>

    </main>

    <div class="card fixed-bottom d-flex" id="keranjang" style="opacity:0.96; width:100%;">
        <div class="card-header">
            <h1>Keranjang</h1>
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
                    <h1 id="contact">SIMPLE MART</h1>
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
