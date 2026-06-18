<?php

// login.php - User login page for Pastimes

session_start();
include('DBConn.php');

// Redirect if already logged in
if (isset($_SESSION['userID'])) {
    header("Location: index.php");
    exit();
}

$username = $email = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form inputs — sticky form keeps values on error
    $username = $conn->real_escape_string(trim($_POST['username']));
    $email    = $conn->real_escape_string(trim($_POST['email']));
    $password = trim($_POST['password']);

    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Find user by username and email
        $sql = "SELECT * FROM tblUser 
                WHERE username='$username' AND email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Check password against hashed password
            if (password_verify($password, $user['password'])) {

                // Check if account is verified
                if ($user['status'] === 'verified') {
                    // Set session variables
                    $_SESSION['userID']    = $user['userID'];
                    $_SESSION['firstName'] = $user['firstName'];
                    $_SESSION['lastName']  = $user['lastName'];
                    $_SESSION['username']  = $user['username'];
                    $_SESSION['email']     = $user['email'];

                    // Redirect to user dashboard
                    header("Location: dashboard.php");
                    exit();
                } else {
                    // Account pending approval
                    $error = "Your account is pending admin approval. Please wait.";
                }
            } else {
                // Wrong password — sticky form keeps username and email
                $error = "Incorrect password. Please try again.";
            }
        } else {
            $error = "No account found with those details.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav>
    <a href="index.php" class="logo">PASTIMES</a>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="listings.php">Listings</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
    </ul>
</nav>

<!-- LOGIN FORM -->
<div class="page-wrapper">
    <div class="card">
        <h2>Welcome Back</h2>
        <p class="subtitle">Login to your Pastimes account</p>

        <?php if (!empty($error)): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">

            <div class="form-group">
                <label for="username">Username</label>
                <!-- Sticky form — keeps value on error -->
                <input type="text" id="username" name="username"
                       value="<?php echo htmlspecialchars($username); ?>"
                       required placeholder="Enter your username">
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <!-- Sticky form — keeps value on error -->
                <input type="email" id="email" name="email"
                       value="<?php echo htmlspecialchars($email); ?>"
                       required placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       required placeholder="Enter your password">
            </div>

            <button type="submit" class="btn-primary">Login</button>
            <a href="register.php">
                <button type="button" class="btn-secondary">Create Account</button>
            </a>

        </form>

        <div class="card-link">
            Admin? <a href="adminLogin.php">Login here</a>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer>
    <p>&copy; 2026 PASTIMES — Second-Hand Fashion</p>
</footer>

</body>
</html>