<?php
session_start(); // Start the session to access session variables

// Assuming the username is stored in the session during user login
if (!isset($_SESSION['username'])) {
    die("User not logged in.");
}

// Use the logged-in user's name
$user_name = $_SESSION['username'];

// Simulate POST data (replace these values as needed)
$_POST['movie_id'] = 1; // Example movie ID
$_POST['seats'] = json_encode(['C2', 'B2', 'C4']); // Example seats in JSON format
$_POST['card'] = '4111111111111111'; // Example card number
$_POST['expiry'] = '12/24'; // Example expiry date
$_POST['cvv'] = '123'; // Example CVV

$conn = new mysqli("localhost", "root", "", "movie_booking");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $movie_id = $_POST['movie_id'];
    $seats = isset($_POST['seats']) ? json_decode($_POST['seats'], true) : [];
    $card = $_POST['card'];
    $expiry = $_POST['expiry'];
    $cvv = $_POST['cvv'];

    $test_card = "4111111111111111";
    $test_expiry = "12/24";
    $test_cvv = "123";

    if ($card == $test_card && $expiry == $test_expiry && $cvv == $test_cvv) {
        if (!empty($seats)) {
            // Debugging output
            echo "Searching for movie with ID: $movie_id<br>";

            $movie_query = "SELECT movie_title FROM movies WHERE movie_id = $movie_id";
            $movie_result = $conn->query($movie_query);
            
            if ($movie_result && $movie_result->num_rows > 0) {
                $movie = $movie_result->fetch_assoc();
                $movie_title = $movie['movie_title'];
                
                foreach ($seats as $seat) {
                    $conn->query("UPDATE seats SET status='booked', booked_by='$user_name' WHERE seat_number='$seat'");
                }

                $seat_count = count($seats);
                $price_per_seat = 130;
                $tax_rate = 0.1;
                $total_amount = $seat_count * $price_per_seat;
                $tax_amount = $total_amount * $tax_rate;
                $grand_total = $total_amount + $tax_amount;

                $seats_joined = implode(', ', $seats);
                $insert_query = "INSERT INTO bookings (movie_title, seats, user_name, total_amount) VALUES ('$movie_title', '$seats_joined', '$user_name', '$grand_total')";
                $conn->query($insert_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .receipt-container {
            background-color: white;
            padding: 20px;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .receipt-header {
            text-align: center;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        h1, h2 {
            color: #333;
            margin: 0;
        }
        .receipt-info {
            margin-bottom: 20px;
        }
        .receipt-info p {
            margin: 5px 0;
            font-size: 16px;
        }
        .receipt-details {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
        }
        .receipt-details p {
            font-size: 14px;
            margin: 8px 0;
        }
        .receipt-details p span {
            float: right;
        }
        .total {
            font-weight: bold;
        }
        .print-button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            font-size: 16px;
        }
        .print-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>Payment Successful!</h1>
            <h2>Receipt</h2>
        </div>
        <div class="receipt-info">
            <p><strong>Movie:</strong> <?php echo $movie_title; ?></p>
            <p><strong>User:</strong> <?php echo $user_name; ?></p>
            <p><strong>Seats:</strong> <?php echo $seats_joined; ?></p>
        </div>
        <div class="receipt-details">
            <p>Price per Seat <span>Rs.<?php echo $price_per_seat; ?></span></p>
            <p>Tax (10%) <span>Rs.<?php echo number_format($tax_amount, 2); ?></span></p>
            <p class="total">Total Amount Paid <span>Rs.<?php echo number_format($grand_total, 2); ?></span></p>
        </div>
        <button class="print-button" onclick="window.print()">Print Receipt</button>
    </div>
</body>
</html>
<?php
            } else {
                echo "Movie not found. SQL error: " . $conn->error; // Add this line for error reporting
            }
        } else {
            echo "No seats selected for payment.";
        }
    } else {
        echo "<h1>Payment Failed!</h1>";
        echo "<p>Please use the correct test card details to simulate a successful payment.</p>";
    }
} else {
    echo "No payment data received.";
}

$conn->close();
?>
