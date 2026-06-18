<?php

// listings.php - Displays all available clothing items

session_start();
include('DBConn.php');

// Fetch all available clothes using associative array
$sql    = "SELECT * FROM tblClothes WHERE status='available' ORDER BY createdAt DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listings — Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Popup overlay */
        .popup-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .popup-overlay.active {
            display: flex;
        }
        .popup-box {
            background: #ffffff;
            border-radius: 8px;
            padding: 40px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .popup-box h3 {
            font-size: 20px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .popup-box .popup-price {
            font-size: 36px;
            font-weight: bold;
            margin: 15px 0;
        }
        .popup-box p {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }
        .popup-box .btn-primary {
            max-width: 200px;
            margin: 0 auto 10px;
            display: block;
        }
        .popup-box .btn-close {
            background: none;
            border: none;
            color: #999;
            font-size: 13px;
            cursor: pointer;
            margin-top: 10px;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav>
    <a href="index.php" class="logo">PASTIMES</a>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="listings.php">Listings</a></li>
        <?php if (isset($_SESSION['userID'])): ?>
            <li><a href="sellItem.php">Sell an Item</a></li>
            <li><a href="myMessages.php">Messages</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="dashboard.php">My Account</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>

<?php if (isset($_SESSION['userID'])): ?>
<div class="user-bar">
    User <?php echo htmlspecialchars($_SESSION['firstName'] . ' ' . $_SESSION['lastName']); ?> is logged in
</div>
<?php endif; ?>

<!-- POPUP -->
<div class="popup-overlay" id="cartPopup">
    <div class="popup-box">
        <h3 id="popupTitle"></h3>
        <div class="popup-price" id="popupPrice"></div>
        <p id="popupDetails"></p>
        <form method="POST" action="cart.php">
            <input type="hidden" name="clothesID" id="popupClothesID">
            <button type="submit" name="addToCart" class="btn-primary">
                Confirm Add to Cart
            </button>
        </form>
        <button class="btn-close" onclick="closePopup()">
            Continue Shopping
        </button>
    </div>
</div>

<!-- LISTINGS -->
<div class="container">
    <h2>Available Clothing</h2>

    <?php if ($result->num_rows > 0): ?>
        <div class="product-grid">
            <?php while ($item = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="images/<?php echo htmlspecialchars($item['image']); ?>"
                         alt="<?php echo htmlspecialchars($item['title']); ?>"
                         onerror="this.src='images/placeholder.jpg'">
                    <div class="product-info">
                        <div class="product-brand">
                            <?php echo htmlspecialchars($item['brand']); ?> 
                            &middot; Size <?php echo htmlspecialchars($item['size']); ?>
                            &middot; <?php echo htmlspecialchars($item['condition']); ?>
                        </div>
                        <div class="product-title">
                            <?php echo htmlspecialchars($item['title']); ?>
                        </div>
                        <div class="product-price">
                            R <?php echo number_format($item['price'], 2); ?>
                        </div>
                        <!-- Add to Cart button triggers popup -->
                        <button class="btn-cart" onclick="openPopup(
                            '<?php echo htmlspecialchars($item['title']); ?>',
                            '<?php echo $item['price']; ?>',
                            '<?php echo htmlspecialchars($item['brand']); ?>',
                            '<?php echo htmlspecialchars($item['size']); ?>',
                            '<?php echo $item['clothesID']; ?>'
                        )">Add to Cart</button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="info-msg">No clothing items available at the moment. Check back soon!</div>
    <?php endif; ?>
</div>

<!-- FOOTER -->
<footer>
    <p>&copy; 2026 PASTIMES — Second-Hand Fashion</p>
</footer>

<script>
    // Open popup with item details
    function openPopup(title, price, brand, size, clothesID) {
        document.getElementById('popupTitle').innerText = title;
        document.getElementById('popupPrice').innerText = 'R ' + parseFloat(price).toFixed(2);
        document.getElementById('popupDetails').innerText = brand + ' · Size ' + size;
        document.getElementById('popupClothesID').value = clothesID;
        document.getElementById('cartPopup').classList.add('active');
    }

    // Close popup
    function closePopup() {
        document.getElementById('cartPopup').classList.remove('active');
    }

    // Close popup when clicking outside
    document.getElementById('cartPopup').addEventListener('click', function(e) {
        if (e.target === this) closePopup();
    });
</script>

</body>
</html>