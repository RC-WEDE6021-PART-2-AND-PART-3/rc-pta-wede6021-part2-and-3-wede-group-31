<?php

// adminLogout.php - Destroys admin session and redirects to admin login

session_start();
session_destroy();
header("Location: adminLogin.php");
exit();
?>