<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Mart - Payment</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="pembayaran.css">
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
            <a href="profile.html" class="profile-link">
                <img src="./assets/profil.png" alt="Profile" class="profile-icon">
            </a>
        </nav>
    </header>

    <div class="container">
        <div class="payment-options">
            <h2 class="pilihan-pembayaran">PILIHAN PEMBAYARAN</h2>
            <p><input id="bank-transfer" name="payment" type="radio" value="val1" onclick="check_radio()"> Pembayaran Transfer Bank</p>
            <p><input id="qris" name="payment" type="radio" value="val2" onclick="check_radio()"> QRIS</p>

            <div id="transfer-content" class="transfer-content" style="display:none;">
                <p>A virtual account number will be generated when placing an order. Please use the virtual account number to complete the payment using internet banking or via ATM. Your order will be cancelled if you do not complete the payment within the payment deadline.</p>
                <p><input id="bca" name="bank-payment" type="radio">BCA VA</p>
                <p><input id="bni" name="bank-payment" type="radio">BNI VA</p>
                <p><input id="bri" name="bank-payment" type="radio">BRI VA</p>
                <p><input id="sumut" name="bank-payment" type="radio">Mandiri VA</p>
                <p><input id="sumut" name="bank-payment" type="radio">Sumut VA</p>
                <p>Nomor VA</p>
                <div class="kotak-rekening">
                    <p class="rekening">122082273039434</p>
                </div>
                <p>Once you receive your unique VA number, proceed to your bank's online banking platform or mobile app to complete the transfer. Ensure that the payment is made using the provided VA number to ensure accurate and prompt processing of your order. Please complete the transfer within the specified time to avoid any delays or cancellation of your transaction.</p>
            </div>
            
            <div id="qris-content" class="qris-content" style="display:none;">
                <p class="deskripsi-qris">QRIS (Quick Response Code Indonesian Standard) is a simple and secure way to make payments using your mobile banking app or digital wallet. To complete your payment, simply scan the QR code provided during checkout using your preferred QRIS-enabled payment app. Once scanned, confirm the payment amount and authorize the transaction. Your payment will be processed instantly, and your order will be confirmed. Please ensure that your payment is completed within the specified time to avoid any delays. If you have any issues, feel free to contact our customer support team.</p>
                <div class="image-container">
                    <img class="QRIS" src="/Foto/QRIS E-COMMERCE.jpeg" alt="QRIS Code">
                </div>
            </div>

            <p><input id="confirm-amount" type="checkbox" class="checkbox">Ensure the payment amount matches the total order amount, including taxes and fees.</p>
        </div>

        <div class="order-summary">
            <div class="judul">
                <h2>ORDER SUMMARY</h2><p> | </p><h2>99 PRODUCTS</h2>
            </div>
            <div class="subjudul">
                <p>Product subtotal</p><p>Rp. 55.000</p>
            </div>
            <div class="subjudul">
                <p>Delivery fees</p><p>Rp. 5.500</p>
            </div>
            <div class="total">
                <h3>Total</h3><p class="harga">Rp. 60.500</p>
            </div>
            <p class="terms" style="font-size: 18px">By clicking the payment button, you agree to our terms and conditions.</p>
            <button class="proceed">PROCEED TO PAYMENT</button>
            <button class="continue">CONTINUE SHOPPING</button>
        </div>
        

    <div class="modal">
        <div class="modal-content">
            <p>Before finalizing your payment, please take a moment to review your order. Ensure that the products, quantities, and subtotal are correct, including any additional charges such as taxes or shipping fees.</p>
            <button class="make-payment">MAKE PAYMENT</button>
            <button class="cancel-payment">CANCEL PAYMENT</button>
        </div>
    </div>

    <script>
        // Check the selected radio button and show the corresponding content
        function check_radio() {
            const transferContent = document.getElementById('transfer-content');
            const qrisContent = document.getElementById('qris-content');
            
            switch (document.querySelector('input[name="payment"]:checked').value) {
                case 'val1':
                    console.log('Pembayaran Transfer Bank selected');
                    transferContent.style.display = 'block';
                    qrisContent.style.display = 'none';
                    break;
                case 'val2':
                    console.log('QRIS selected');
                    transferContent.style.display = 'none';
                    qrisContent.style.display = 'block';
                    break;
                default:
                    transferContent.style.display = 'none';
                    qrisContent.style.display = 'none';
            }
        }

        // Proceed to Payment modal
        const proceedButton = document.querySelector('.proceed');
        const modal = document.querySelector('.modal');
        const modalContent = document.querySelector('.modal-content');
        const makePaymentButton = document.querySelector('.make-payment');
        const cancelPaymentButton = document.querySelector('.cancel-payment');

        proceedButton.addEventListener('click', function() {
            modal.style.display = 'block';
            modalContent.style.display = 'block';
        });

        makePaymentButton.addEventListener('click', function() {
            alert('Payment Successful');
        });

        cancelPaymentButton.addEventListener('click', function() {
            modal.style.display = 'none';
            modalContent.style.display = 'none';
        });
    </script>
</body>
</html>
