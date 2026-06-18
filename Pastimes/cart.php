<?php

session_start();
include('DBConn.php');

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID  = $_SESSION['userID'];
$message = "";

// ===== ADD TO CART =====
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addToCart'])) {
    $clothesID = intval($_POST['clothesID']);

    $check = $conn->query("SELECT cartID, quantity FROM tblCart 
                           WHERE userID=$userID AND clothesID=$clothesID");
    if ($check->num_rows > 0) {
        // Item already in cart, increase quantity instead
        $row = $check->fetch_assoc();
        $newQty = $row['quantity'] + 1;
        $conn->query("UPDATE tblCart SET quantity=$newQty 
                      WHERE cartID={$row['cartID']}");
        $message = "Item already in cart — quantity increased!";
    } else {
        $conn->query("INSERT INTO tblCart (userID, clothesID, quantity) 
                      VALUES ($userID, $clothesID, 1)");
        $message = "Item added to cart successfully!";
    }
}

// ===== UPDATE QUANTITY (increase/decrease) =====
if (isset($_GET['increase'])) {
    $cartID = intval($_GET['increase']);
    $conn->query("UPDATE tblCart SET quantity = quantity + 1 
                  WHERE cartID=$cartID AND userID=$userID");
    header("Location: cart.php");
    exit();
}

if (isset($_GET['decrease'])) {
    $cartID = intval($_GET['decrease']);
    // Check current quantity first
    $check = $conn->query("SELECT quantity FROM tblCart WHERE cartID=$cartID AND userID=$userID");
    $row = $check->fetch_assoc();
    if ($row && $row['quantity'] > 1) {
        $conn->query("UPDATE tblCart SET quantity = quantity - 1 
                      WHERE cartID=$cartID AND userID=$userID");
    }
    header("Location: cart.php");
    exit();
}

// ===== REMOVE FROM CART =====
if (isset($_GET['remove'])) {
    $cartID = intval($_GET['remove']);
    $conn->query("DELETE FROM tblCart WHERE cartID=$cartID AND userID=$userID");
    header("Location: cart.php");
    exit();
}

// ===== FETCH CART ITEMS =====
$sql = "SELECT tblCart.cartID, tblCart.quantity, tblClothes.clothesID, tblClothes.title, 
               tblClothes.brand, tblClothes.size, tblClothes.price, 
               tblClothes.image, tblClothes.`condition`
        FROM tblCart 
        JOIN tblClothes ON tblCart.clothesID = tblClothes.clothesID
        WHERE tblCart.userID = $userID";
$result = $conn->query($sql);

$total     = 0;
$cartItems = [];
while ($item = $result->fetch_assoc()) {
    $cartItems[] = $item;
    $total += ($item['price'] * $item['quantity']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart — Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .qty-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .qty-btn {
            width: 28px;
            height: 28px;
            border: 1px solid #000;
            background: #fff;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #000;
        }
        .qty-btn:hover {
            background: #000;
            color: #fff;
        }
        .qty-value {
            min-width: 30px;
            text-align: center;
            font-weight: 600;
        }
    </style>
</head>
<body>

<nav>
    <a href="index.php" class="logo">PASTIMES</a>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="listings.php">Listings</a></li>
        <li><a href="sellItem.php">Sell an Item</a></li>
        <li><a href="myMessages.php">Messages</a></li>
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
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
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
                    <td>R <?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <!-- Quantity increase/decrease controls -->
                        <div class="qty-control">
                            <a href="cart.php?decrease=<?php echo $item['cartID']; ?>"
                               class="qty-btn">−</a>
                            <span class="qty-value"><?php echo $item['quantity']; ?></span>
                            <a href="cart.php?increase=<?php echo $item['cartID']; ?>"
                               class="qty-btn">+</a>
                        </div>
                    </td>
                    <td>R <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td>
                        <a href="cart.php?remove=<?php echo $item['cartID']; ?>"
                           style="color:red; font-weight:600;"
                           onclick="return confirm('Remove this item from cart?')">
                            ✕ Remove
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <tr>
                <td colspan="6" style="text-align:right; font-weight:600;
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