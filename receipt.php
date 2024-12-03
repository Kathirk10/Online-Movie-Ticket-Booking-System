<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Movie Booking</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Booking Confirmation</h1>
    <p>Thank you for your booking, <?php echo $_SESSION['username']; ?>!</p>
    <p>Your seats have been successfully reserved.</p>
    <p><a href="index.php">Go back to home</a></p>
</body>
</html>
