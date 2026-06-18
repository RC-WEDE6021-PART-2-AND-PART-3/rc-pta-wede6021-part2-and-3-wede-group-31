<?php
// Student Name: Karabo Tshivhase
// Student Number: [Your Student Number]
// adminMessages.php - Admin sends messages/notifications to users

session_start();
include('DBConn.php');

if (!isset($_SESSION['adminID'])) {
    header("Location: adminLogin.php");
    exit();
}

$message = "";

// ===== SEND MESSAGE =====
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sendMessage'])) {
    $receiverID = intval($_POST['receiverID']);
    $subject    = $conn->real_escape_string(trim($_POST['subject']));
    $body       = $conn->real_escape_string(trim($_POST['messageBody']));
    $adminID    = $_SESSION['adminID'];

    if (empty($subject) || empty($body)) {
        $message = "Please fill in subject and message.";
    } else {
        $sql = "INSERT INTO tblMessages 
                (senderID, senderType, receiverID, subject, message)
                VALUES 
                ($adminID, 'admin', $receiverID, '$subject', '$body')";

        $message = $conn->query($sql) ? "Message sent successfully!" : "Error: " . $conn->error;
    }
}

// Fetch all users for the dropdown
$users = $conn->query("SELECT userID, firstName, lastName, email FROM tblUser 
                       WHERE status='verified' ORDER BY firstName");

// Fetch all sent messages (admin's outbox)
$sentMessages = $conn->query("SELECT tblMessages.*, tblUser.firstName, tblUser.lastName
                              FROM tblMessages
                              JOIN tblUser ON tblMessages.receiverID = tblUser.userID
                              WHERE tblMessages.senderType='admin'
                              ORDER BY tblMessages.sentAt DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages — Pastimes Admin</title>
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

    <h2>Send a Message</h2>
    <div class="card" style="max-width:100%; margin-bottom:30px;">
        <form method="POST" action="adminMessages.php">

            <div class="form-group">
                <label>Send To</label>
                <select name="receiverID" required style="width:100%; padding:12px;
                        border:1px solid #ccc; border-radius:4px;">
                    <option value="">-- Select a user --</option>
                    <?php while ($u = $users->fetch_assoc()): ?>
                        <option value="<?php echo $u['userID']; ?>">
                            <?php echo htmlspecialchars($u['firstName'] . ' ' .
                                $u['lastName'] . ' (' . $u['email'] . ')'); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="subject" required
                       placeholder="e.g. Your item has been delivered">
            </div>

            <div class="form-group">
                <label>Message</label>
                <textarea name="messageBody" required rows="4"
                          placeholder="Type your message here..."
                          style="width:100%; padding:12px 15px; border:1px solid #ccc;
                                 border-radius:4px; background:#f9f9f9; font-family:inherit;
                                 font-size:14px;"></textarea>
            </div>

            <button type="submit" name="sendMessage" class="btn-primary"
                    style="max-width:200px;">Send Message</button>
        </form>
    </div>

    <h2>Sent Messages</h2>
    <?php if ($sentMessages->num_rows > 0): ?>
        <table class="data-table">
            <tr>
                <th>To</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Sent</th>
            </tr>
            <?php while ($msg = $sentMessages->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($msg['firstName'] . ' ' . $msg['lastName']); ?></td>
                    <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                    <td style="max-width:250px; font-size:13px;">
                        <?php echo htmlspecialchars(substr($msg['message'], 0, 60)); ?>...
                    </td>
                    <td><?php echo date('d M Y H:i', strtotime($msg['sentAt'])); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <div class="info-msg">No messages sent yet.</div>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; 2026 PASTIMES — Second-Hand Fashion</p>
</footer>

</body>
</html>