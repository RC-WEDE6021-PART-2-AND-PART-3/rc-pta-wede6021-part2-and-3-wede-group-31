<?php

// index.php - Home page for Pastimes

session_start();
include('DBConn.php');

// Fetch 4 most recent available items for featured section
$featured = $conn->query("SELECT * FROM tblClothes 
                          WHERE status='available' 
                          ORDER BY createdAt DESC LIMIT 4");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pastimes — Second-Hand Fashion</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .hero {
            background-color: #000000;
            color: #ffffff;
            text-align: center;
            padding: 100px 20px;
        }
        .hero h1 {
            font-size: 52px;
            letter-spacing: 8px;
            text-transform: uppercase;
            margin-bottom: 15px;
        }
        .hero p {
            font-size: 16px;
            color: #cccccc;
            margin-bottom: 35px;
            letter-spacing: 2px;
        }
        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        .hero-btn-primary {
            padding: 14px 35px;
            background-color: #ffffff;
            color: #000000;
            border: none;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            text-decoration: none;
            border-radius: 4px;
        }
        .hero-btn-secondary {
            padding: 14px 35px;
            background-color: transparent;
            color: #ffffff;
            border: 1px solid #ffffff;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            text-decoration: none;
            border-radius: 4px;
        }
        .features-section {
            background-color: #f5f5f5;
            padding: 60px 20px;
            text-align: center;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            max-width: 900px;
            margin: 30px auto 0;
        }
        .feature-item {
            background: #ffffff;
            padding: 30px 20px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
        }
        .feature-icon {
    font-size: 32px;
    margin-bottom: 12px;
    color: #000000;
}
        .feature-item h4 {
            font-size: 14px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .feature-item p {
            font-size: 13px;
            color: #666;
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
    User <?php echo htmlspecialchars($_SESSION['firstName'] . ' ' 
    . $_SESSION['lastName']); ?> is logged in
</div>
<?php endif; ?>

<!-- HERO SECTION -->
<div class="hero">
    <h1>Pastimes</h1>
    <p>Buy & Sell Second-Hand Branded Fashion</p>
    <div class="hero-buttons">
        <a href="listings.php" class="hero-btn-primary">Browse Listings</a>
        <?php if (!isset($_SESSION['userID'])): ?>
            <a href="register.php" class="hero-btn-secondary">Join Now</a>
        <?php else: ?>
            <a href="dashboard.php" class="hero-btn-secondary">My Account</a>
        <?php endif; ?>
    </div>
</div>

<!-- FEATURES SECTION -->
<div class="features-section">
    <h2 style="font-size:20px; letter-spacing:3px; text-transform:uppercase;">
        Why Pastimes?
    </h2>
    <div class="features-grid">
        <div class="feature-item">
            <div class="feature-icon">
                <i class="fas fa-recycle" style="font-size:32px;"></i>
            </div>
            <h4>Sustainable Fashion</h4>
            <p>Give pre-loved clothing a new home and reduce fashion waste.</p>
        </div>
        <div class="feature-item">
            <div class="feature-icon">
                <i class="fas fa-tag" style="font-size:32px;"></i>
            </div>
            <h4>Zero Seller Fees</h4>
            <p>Sell your clothes and keep 100% of your earnings.</p>
        </div>
        <div class="feature-item">
            <div class="feature-icon">
                <i class="fas fa-shield-alt" style="font-size:32px;"></i>
            </div>
            <h4>Buyer Protection</h4>
            <p>Shop with confidence , all buyers are protected.</p>
        </div>
        <div class="feature-item">
            <div class="feature-icon">
                <i class="fas fa-truck" style="font-size:32px;"></i>
            </div>
            <h4>Fast Delivery</h4>
            <p>Reliable courier delivery to your door.</p>
        </div>
    </div>
</div>

<!-- FEATURED LISTINGS -->
<div class="container">
    <h2>Featured Items</h2>
    <div class="product-grid">
        <?php while ($item = $featured->fetch_assoc()): ?>
        <div class="product-card">
            <img src="images/<?php echo htmlspecialchars($item['image']); ?>"
                 alt="<?php echo htmlspecialchars($item['title']); ?>"
                 onerror="this.src='images/placeholder.jpg'">
            <div class="product-info">
                <div class="product-brand">
                    <?php echo htmlspecialchars($item['brand']); ?>
                    &middot; Size <?php echo htmlspecialchars($item['size']); ?>
                </div>
                <div class="product-title">
                    <?php echo htmlspecialchars($item['title']); ?>
                </div>
                <div class="product-price">
                    R <?php echo number_format($item['price'], 2); ?>
                </div>
                <a href="listings.php">
                    <button class="btn-cart">View Item</button>
                </a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- FOOTER -->
<footer>
    <p>&copy; 2026 PASTIMES — Second-Hand Fashion</p>
</footer>

</body>
</html>