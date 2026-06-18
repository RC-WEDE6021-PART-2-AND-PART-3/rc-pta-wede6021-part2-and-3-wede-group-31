<?php
// Student Name: Karabo Tshivhase
// Student Number: [Your Student Number]
// sellItem.php - Seller submission form for users wanting to sell clothing

session_start();
include('DBConn.php');

// Must be logged in to sell
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID  = $_SESSION['userID'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title       = $conn->real_escape_string(trim($_POST['title']));
    $description = $conn->real_escape_string(trim($_POST['description']));
    $brand       = $conn->real_escape_string(trim($_POST['brand']));
    $size        = $conn->real_escape_string(trim($_POST['size']));
    $condition   = $conn->real_escape_string(trim($_POST['condition']));
    $price       = floatval($_POST['price']);
    $image       = $conn->real_escape_string(trim($_POST['image']));

    if (empty($title) || empty($description) || empty($brand) || empty($image)) {
        $message = "Please fill in all required fields.";
    } else {
        $sql = "INSERT INTO tblSellerRequest 
                (userID, title, description, brand, image, price, size, `condition`, status)
                VALUES 
                ($userID, '$title', '$description', '$brand', '$image', 
                 $price, '$size', '$condition', 'pending')";

        if ($conn->query($sql) === TRUE) {
            $message = "Your item has been submitted for review! 
                        The admin will check it before it goes live.";
        } else {
            $message = "Error submitting item: " . $conn->error;
        }
    }
}

// Fetch this user's submission history
$myRequests = $conn->query("SELECT * FROM tblSellerRequest 
                            WHERE userID=$userID 
                            ORDER BY submittedAt DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell an Item — Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav>
    <a href="index.php" class="logo">PASTIMES</a>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="listings.php">Listings</a></li>
        <li><a href="sellItem.php">Sell an Item</a></li>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="dashboard.php">My Account</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div class="user-bar">
    User <?php echo htmlspecialchars($_SESSION['firstName'] . ' ' . $_SESSION['lastName']); ?> is logged in
</div>

<div class="container">
    <h2>Sell Your Clothing</h2>

    <?php if (!empty($message)): ?>
        <div class="<?php echo strpos($message, 'Error') !== false ||
        strpos($message, 'Please') !== false ? 'error-msg' : 'success-msg'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="card" style="max-width:100%; margin-bottom:30px;">
        <form method="POST" action="sellItem.php">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">

                <div class="form-group">
                    <label>Item Title</label>
                    <input type="text" name="title" required
                           placeholder="e.g. Vintage Denim Jacket">
                </div>

                <div class="form-group">
                    <label>Brand</label>
                    <input type="text" name="brand" required
                           placeholder="e.g. Levi's">
                </div>

                <div class="form-group">
                    <label>Size</label>
                    <input type="text" name="size" required
                           placeholder="e.g. M">
                </div>

                <div class="form-group">
                    <label>Condition</label>
                    <select name="condition" required style="width:100%; padding:12px;
                            border:1px solid #ccc; border-radius:4px; background:#f9f9f9;">
                        <option value="New">New</option>
                        <option value="Good">Good</option>
                        <option value="Fair">Fair</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Asking Price (R)</label>
                    <input type="number" step="0.01" name="price" required
                           placeholder="e.g. 250.00">
                </div>

                <div class="form-group">
                    <label>Image Filename</label>
                    <input type="text" name="image" required
                           placeholder="e.g. my_jacket.jpg">
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required rows="4"
                          placeholder="Describe the item's condition, fit, and any details..."
                          style="width:100%; padding:12px 15px; border:1px solid #ccc;
                                 border-radius:4px; background:#f9f9f9; font-family:inherit;
                                 font-size:14px;"></textarea>
            </div>

            <button type="submit" class="btn-primary" style="max-width:250px;">
                Submit for Review
            </button>
        </form>
    </div>

    <!-- SUBMISSION HISTORY -->
    <h2>My Submissions</h2>
    <?php if ($myRequests->num_rows > 0): ?>
        <table class="data-table">
            <tr>
                <th>Item</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Status</th>
                <th>Submitted</th>
            </tr>
            <?php while ($req = $myRequests->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($req['title']); ?></td>
                    <td><?php echo htmlspecialchars($req['brand']); ?></td>
                    <td>R <?php echo number_format($req['price'], 2); ?></td>
                    <td>
                        <?php if ($req['status'] === 'pending'): ?>
                            <span style="color:#cc8800; font-weight:600;">Pending Review</span>
                        <?php elseif ($req['status'] === 'approved'): ?>
                            <span style="color:#006600; font-weight:600;">Approved</span>
                        <?php else: ?>
                            <span style="color:#cc0000; font-weight:600;">Rejected</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('d M Y', strtotime($req['submittedAt'])); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <div class="info-msg">You haven't submitted any items yet.</div>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; 2026 PASTIMES — Second-Hand Fashion</p>
</footer>

</body>
</html>