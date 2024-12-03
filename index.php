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

        <div class="categories-list">
        </div>

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
                echo "<a href='' style='position:absolute;bottom:20px; left:50%; transform:translateX(-50%);'><button>Buy Now</button></a>";
                echo "</div>";
                echo "</div>";
            }
            ?>
            </div>
        </div>
    </main>

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
            <div class="footer-center">
                <h2>Contact Us</h2>
                <p>Shopping Made Simple, Prices Made Right.</p>
                <div class="footer-links">
                    <a href="#">Home</a>
                    <a href="#">Categories</a>
                    <a href="#">Products</a>
                    <a href="#">Contact Us</a>
                </div>
                <div class="social-icons">
                    <a href="#"><img src="./assets/x icon.png" alt=""></a>
                    <a href="#"><img src="./assets/ig icon.png" alt=""></a>
                    <a href="#"><img src="./assets/fb icon.png" alt=""></a>
                </div>
                <p>Copyright © 2024 Simple Mart</p>
            </div>
        </div>
    </footer>

</body>
</html>
