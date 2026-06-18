<?php
// Student Name: Karabo Tshivhase
// Student Number: [Your Student Number]
// adminSellerRequests.php - Admin reviews seller item submissions

session_start();
include('DBConn.php');

if (!isset($_SESSION['adminID'])) {
    header("Location: adminLogin.php");
    exit();
}

$message = "";

// ===== APPROVE REQUEST =====
if (isset($_GET['approve'])) {
    $requestID = intval($_GET['approve']);

    // Fetch the request details
    $result  = $conn->query("SELECT * FROM tblSellerRequest WHERE requestID=$requestID");
    $request = $result->fetch_assoc();

    if ($request) {
        // Insert into tblClothes so it goes live on listings
        $title     = $conn->real_escape_string($request['title']);
        $brand     = $conn->real_escape_string($request['brand']);
        $size      = $conn->real_escape_string($request['size']);
        $condition = $conn->real_escape_string($request['condition']);
        $price     = $request['price'];
        $image     = $conn->real_escape_string($request['image']);

        $conn->query("INSERT INTO tblClothes 
                      (title, brand, size, `condition`, price, image, status)
                      VALUES 
                      ('$title','$brand','$size','$condition',$price,'$image','available')");

        // Mark the request as approved
        $conn->query("UPDATE tblSellerRequest SET status='approved' 
                      WHERE requestID=$requestID");

        $message = "Item approved and added to listings!";
    }
}

// ===== REJECT REQUEST =====
if (isset($_GET['reject'])) {
    $requestID = intval($_GET['reject']);
    $conn->query("UPDATE tblSellerRequest SET status='rejected' 
                  WHERE requestID=$requestID");
    $message = "Item rejected.";
}

// Fetch all seller requests with user info
$requests = $conn->query("SELECT tblSellerRequest.*, tblUser.firstName, tblUser.lastName, 
                          tblUser.email
                          FROM tblSellerRequest
                          JOIN tblUser ON tblSellerRequest.userID = tblUser.userID
                          ORDER BY 
                          CASE WHEN tblSellerRequest.status='pending' THEN 0 ELSE 1 END,
                          tblSellerRequest.submittedAt DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Requests — Pastimes Admin</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav>
    <a href="index.php" class="logo">PASTIMES</a>
    <ul>
        <li><a href="adminDashboard.php">Users</a></li>
        <li><a href="adminClothes.php">Clothing</a></li>
        <li><a href="adminSellerRequests.php">Seller Requests</a></li>
        <li><a href="adminMessages.php">Messages</a></li>
        <li><a href="adminLogout.php">Logout</a></li>
    </ul>
</nav>

<div class="user-bar">
    Admin: <?php echo htmlspecialchars($_SESSION['adminEmail']); ?> is logged in
</div>

<div class="container">

    <?php if (!empty($message)): ?>
        <div class="success-msg"><?php echo $message; ?></div>
    <?php endif; ?>

    <h2>Seller Item Submissions</h2>

    <?php if ($requests->num_rows > 0): ?>
        <table class="data-table">
            <tr>
                <th>Image</th>
                <th>Item</th>
                <th>Seller</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($req = $requests->fetch_assoc()): ?>
                <tr>
                    <td>
                        <img src="images/<?php echo htmlspecialchars($req['image']); ?>"
                             style="width:50px; height:50px; object-fit:cover; border-radius:4px;"
                             onerror="this.src='images/placeholder.jpg'">
                    </td>
                    <td><?php echo htmlspecialchars($req['title']); ?></td>
                    <td><?php echo htmlspecialchars($req['firstName'] . ' ' . $req['lastName']); ?>
                        <br><small style="color:#888;"><?php echo htmlspecialchars($req['email']); ?></small>
                    </td>
                    <td><?php echo htmlspecialchars($req['brand']); ?></td>
                    <td>R <?php echo number_format($req['price'], 2); ?></td>
                    <td style="max-width:200px; font-size:12px; color:#666;">
                        <?php echo htmlspecialchars(substr($req['description'], 0, 80)); ?>...
                    </td>
                    <td>
                        <?php if ($req['status'] === 'pending'): ?>
                            <span style="color:#cc8800; font-weight:600;">Pending</span>
                        <?php elseif ($req['status'] === 'approved'): ?>
                            <span style="color:#006600; font-weight:600;">Approved</span>
                        <?php else: ?>
                            <span style="color:#cc0000; font-weight:600;">Rejected</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($req['status'] === 'pending'): ?>
                            <a href="adminSellerRequests.php?approve=<?php echo $req['requestID']; ?>"
                               style="color:green; margin-right:10px; font-weight:600;"
                               onclick="return confirm('Approve this item? It will go live on listings.')">
                                ✓ Approve</a>
                            <a href="adminSellerRequests.php?reject=<?php echo $req['requestID']; ?>"
                               style="color:red; font-weight:600;"
                               onclick="return confirm('Reject this item?')">
                                ✕ Reject</a>
                        <?php else: ?>
                            <span style="color:#999;">No action</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <div class="info-msg">No seller submissions yet.</div>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; 2026 PASTIMES — Second-Hand Fashion</p>
</footer>

</body>
</html>