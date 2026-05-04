<?php

// cart.php - Shopping cart page for Pastimes

session_start();
include('DBConn.php');

// Redirect to login if not logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID  = $_SESSION['userID'];
$message = "";

//  ADD TO CART 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addToCart'])) {
    $clothesID = intval($_POST['clothesID']);

    // Check if item already in cart
    $check = $conn->query("SELECT cartID FROM tblCart 
                           WHERE userID=$userID AND clothesID=$clothesID");
    if ($check->num_rows > 0) {
        $message = "This item is already in your cart!";
    } else {
        $conn->query("INSERT INTO tblCart (userID, clothesID) 
                      VALUES ($userID, $clothesID)");
        $message = "Item added to cart successfully!";
    }
}

//  REMOVE FROM CART 
if (isset($_GET['remove'])) {
    $cartID = intval($_GET['remove']);
    $conn->query("DELETE FROM tblCart WHERE cartID=$cartID AND userID=$userID");
    header("Location: cart.php");
    exit();
}

//  FETCH CART ITEMS 
$sql = "SELECT tblCart.cartID, tblClothes.clothesID, tblClothes.title, 
               tblClothes.brand, tblClothes.size, tblClothes.price, 
               tblClothes.image, tblClothes.`condition`
        FROM tblCart 
        JOIN tblClothes ON tblCart.clothesID = tblClothes.clothesID
        WHERE tblCart.userID = $userID";
$result = $conn->query($sql);

// Calculate total
$total     = 0;
$cartItems = [];
while ($item = $result->fetch_assoc()) {
    $cartItems[] = $item;
    $total += $item['price'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart — Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav>
    <a href="index.php" class="logo">PASTIMES</a>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="listings.php">Listings</a></li>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="dashboard.php">My Account</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div class="user-bar">
    User <?php echo htmlspecialchars($_SESSION['firstName'] . ' ' . $_SESSION['lastName']); ?> is logged in
</div>

<div class="container">
    <h2>Your Cart</h2>

    <?php if (!empty($message)): ?>
        <div class="success-msg"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (count($cartItems) > 0): ?>

        <table class="data-table">
            <tr>
                <th>Image</th>
                <th>Item</th>
                <th>Brand</th>
                <th>Size</th>
                <th>Condition</th>
                <th>Price</th>
                <th>Remove</th>
            </tr>
            <?php foreach ($cartItems as $item): ?>
            <tr>
                <td>
                    <img src="images/<?php echo htmlspecialchars($item['image']); ?>"
                         alt="<?php echo htmlspecialchars($item['title']); ?>"
                         style="width:60px; height:60px; object-fit:cover; border-radius:4px;">
                </td>
                <td><?php echo htmlspecialchars($item['title']); ?></td>
                <td><?php echo htmlspecialchars($item['brand']); ?></td>
                <td><?php echo htmlspecialchars($item['size']); ?></td>
                <td><?php echo htmlspecialchars($item['condition']); ?></td>
                <td>R <?php echo number_format($item['price'], 2); ?></td>
                <td>
                    <a href="cart.php?remove=<?php echo $item['cartID']; ?>"
                       style="color:red; font-weight:600;"
                       onclick="return confirm('Remove this item from cart?')">
                       ✕ Remove
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>

            <!-- Total row -->
            <tr>
                <td colspan="5" style="text-align:right; font-weight:600; 
                    font-size:16px; padding:15px;">
                    TOTAL:
                </td>
                <td colspan="2" style="font-weight:bold; font-size:18px; padding:15px;">
                    R <?php echo number_format($total, 2); ?>
                </td>
            </tr>
        </table>

        <br>
        <div style="display:flex; gap:15px; flex-wrap:wrap;">
            <a href="listings.php">
                <button class="btn-secondary" style="max-width:220px;">
                    Continue Shopping
                </button>
            </a>
            <a href="checkout.php">
                <button class="btn-primary" style="max-width:220px;">
                    Proceed to Checkout
                </button>
            </a>
        </div>

    <?php else: ?>
        <div class="info-msg">
            Your cart is empty. 
            <a href="listings.php" style="color:#000; font-weight:600;">
                Browse listings
            </a> to add items!
        </div>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; 2026 PASTIMES — Second-Hand Fashion</p>
</footer>

</body>
</html>