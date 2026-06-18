<?php


session_start();
include('DBConn.php');

if (!isset($_SESSION['adminID'])) {
    header("Location: adminLogin.php");
    exit();
}

$message = "";

// ===== ADD CLOTHING ITEM =====
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addItem'])) {
    $title     = $conn->real_escape_string(trim($_POST['title']));
    $brand     = $conn->real_escape_string(trim($_POST['brand']));
    $size      = $conn->real_escape_string(trim($_POST['size']));
    $condition = $conn->real_escape_string(trim($_POST['condition']));
    $price     = floatval($_POST['price']);
    $image     = $conn->real_escape_string(trim($_POST['image']));

    $sql = "INSERT INTO tblClothes (title, brand, size, `condition`, price, image, status)
            VALUES ('$title','$brand','$size','$condition',$price,'$image','available')";

    $message = $conn->query($sql) ? "Item added successfully!" : "Error: " . $conn->error;
}

// ===== UPDATE CLOTHING ITEM =====
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateItem'])) {
    $clothesID = intval($_POST['clothesID']);
    $title     = $conn->real_escape_string(trim($_POST['title']));
    $brand     = $conn->real_escape_string(trim($_POST['brand']));
    $size      = $conn->real_escape_string(trim($_POST['size']));
    $condition = $conn->real_escape_string(trim($_POST['condition']));
    $price     = floatval($_POST['price']);
    $image     = $conn->real_escape_string(trim($_POST['image']));
    $status    = $conn->real_escape_string($_POST['status']);

    $sql = "UPDATE tblClothes SET 
            title='$title', brand='$brand', size='$size', `condition`='$condition',
            price=$price, image='$image', status='$status'
            WHERE clothesID=$clothesID";

    $message = $conn->query($sql) ? "Item updated successfully!" : "Error: " . $conn->error;
}

// ===== DELETE CLOTHING ITEM =====
if (isset($_GET['delete'])) {
    $deleteID = intval($_GET['delete']);
    $conn->query("DELETE FROM tblClothes WHERE clothesID=$deleteID");
    $message = "Item deleted successfully!";
}

// Fetch all clothing items
$clothes = $conn->query("SELECT * FROM tblClothes ORDER BY createdAt DESC");

// Fetch item to edit if edit clicked
$editItem = null;
if (isset($_GET['edit'])) {
    $editID     = intval($_GET['edit']);
    $editResult = $conn->query("SELECT * FROM tblClothes WHERE clothesID=$editID");
    $editItem   = $editResult->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Clothing — Pastimes Admin</title>
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

    <!-- ADD NEW ITEM FORM -->
    <h2>Add New Clothing Item</h2>
    <div class="card" style="max-width:100%; margin-bottom:30px;">
        <form method="POST" action="adminClothes.php">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" required placeholder="e.g. Nike Hoodie">
                </div>
                <div class="form-group">
                    <label>Brand</label>
                    <input type="text" name="brand" required placeholder="e.g. Nike">
                </div>
                <div class="form-group">
                    <label>Size</label>
                    <input type="text" name="size" required placeholder="e.g. M">
                </div>
                <div class="form-group">
                    <label>Condition</label>
                    <select name="condition" required style="width:100%; padding:12px;
                            border:1px solid #ccc; border-radius:4px;">
                        <option value="New">New</option>
                        <option value="Good">Good</option>
                        <option value="Fair">Fair</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Price (R)</label>
                    <input type="number" step="0.01" name="price" required placeholder="e.g. 350.00">
                </div>
                <div class="form-group">
                    <label>Image Filename</label>
                    <input type="text" name="image" required placeholder="e.g. nike_hoodie.jpg">
                </div>
            </div>
            <button type="submit" name="addItem" class="btn-primary"
                    style="max-width:200px;">Add Item</button>
        </form>
    </div>

    <!-- EDIT ITEM FORM -->
    <?php if ($editItem): ?>
        <h2>Edit Clothing Item</h2>
        <div class="card" style="max-width:100%; margin-bottom:30px; border:2px solid #000;">
            <form method="POST" action="adminClothes.php">
                <input type="hidden" name="clothesID" value="<?php echo $editItem['clothesID']; ?>">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title"
                               value="<?php echo htmlspecialchars($editItem['title']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Brand</label>
                        <input type="text" name="brand"
                               value="<?php echo htmlspecialchars($editItem['brand']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Size</label>
                        <input type="text" name="size"
                               value="<?php echo htmlspecialchars($editItem['size']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Condition</label>
                        <select name="condition" style="width:100%; padding:12px;
                            border:1px solid #ccc; border-radius:4px;">
                            <option value="New" <?php echo $editItem['condition']=='New'?'selected':''; ?>>New</option>
                            <option value="Good" <?php echo $editItem['condition']=='Good'?'selected':''; ?>>Good</option>
                            <option value="Fair" <?php echo $editItem['condition']=='Fair'?'selected':''; ?>>Fair</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Price (R)</label>
                        <input type="number" step="0.01" name="price"
                               value="<?php echo $editItem['price']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Image Filename</label>
                        <input type="text" name="image"
                               value="<?php echo htmlspecialchars($editItem['image']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" style="width:100%; padding:12px;
                            border:1px solid #ccc; border-radius:4px;">
                            <option value="available" <?php echo $editItem['status']=='available'?'selected':''; ?>>Available</option>
                            <option value="sold" <?php echo $editItem['status']=='sold'?'selected':''; ?>>Sold</option>
                        </select>
                    </div>
                </div>
                <button type="submit" name="updateItem" class="btn-primary"
                        style="max-width:200px;">Update Item</button>
                <a href="adminClothes.php">
                    <button type="button" class="btn-secondary"
                            style="max-width:200px;">Cancel</button>
                </a>
            </form>
        </div>
    <?php endif; ?>

    <!-- CLOTHING TABLE -->
    <h2>Manage Clothing Items</h2>
    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Title</th>
            <th>Brand</th>
            <th>Size</th>
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($item = $clothes->fetch_assoc()): ?>
            <tr>
                <td><?php echo $item['clothesID']; ?></td>
                <td>
                    <img src="images/<?php echo htmlspecialchars($item['image']); ?>"
                         style="width:50px; height:50px; object-fit:cover; border-radius:4px;"
                         onerror="this.src='images/placeholder.jpg'">
                </td>
                <td><?php echo htmlspecialchars($item['title']); ?></td>
                <td><?php echo htmlspecialchars($item['brand']); ?></td>
                <td><?php echo htmlspecialchars($item['size']); ?></td>
                <td>R <?php echo number_format($item['price'], 2); ?></td>
                <td>
                    <?php if ($item['status'] === 'available'): ?>
                        <span style="color:#006600; font-weight:600;">Available</span>
                    <?php else: ?>
                        <span style="color:#cc0000; font-weight:600;">Sold</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="adminClothes.php?edit=<?php echo $item['clothesID']; ?>"
                       style="color:#333; margin-right:10px;">✎ Edit</a>
                    <a href="adminClothes.php?delete=<?php echo $item['clothesID']; ?>"
                       style="color:red;"
                       onclick="return confirm('Delete this item?')">✕ Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<footer>
    <p>&copy; 2026 PASTIMES — Second-Hand Fashion</p>
</footer>

</body>
</html>