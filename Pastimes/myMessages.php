<?php
// Student Name: Karabo Tshivhase
// Student Number: [Your Student Number]
// myMessages.php - User views messages from admin

session_start();
include('DBConn.php');

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];

// Mark all as read when viewed

$conn->query("UPDATE tblMessages SET isRead=1 
              WHERE receiverID=$userID AND senderType='admin'");

// Fetch messages for this user
$messages = $conn->query("SELECT * FROM tblMessages 
                          WHERE receiverID=$userID AND senderType='admin'
                          ORDER BY sentAt DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Messages — Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
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
    <h2>My Messages</h2>

    <?php if ($messages->num_rows > 0): ?>
        <?php while ($msg = $messages->fetch_assoc()): ?>
            <div class="card" style="max-width:100%; margin-bottom:15px;">
                <h3 style="font-size:16px; margin-bottom:5px;">
                    <?php echo htmlspecialchars($msg['subject']); ?>
                </h3>
                <p style="color:#888; font-size:12px; margin-bottom:10px;">
                    From Pastimes Admin &middot;
                    <?php echo date('d M Y H:i', strtotime($msg['sentAt'])); ?>
                </p>
                <p style="color:#333; font-size:14px;">
                    <?php echo htmlspecialchars($msg['message']); ?>
                </p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="info-msg">You have no messages yet.</div>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; 2026 PASTIMES — Second-Hand Fashion</p>
</footer>

</body>
</html>