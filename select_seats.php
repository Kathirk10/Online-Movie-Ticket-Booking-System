<?php
session_start();
if (!isset($_SESSION['username'])) {
    die("User not logged in.");
}

$user_name = $_SESSION['username'];
$movie_id = isset($_GET['movie_id']) ? intval($_GET['movie_id']) : null;

// Database connection
$conn = new mysqli("localhost", "root", "", "movie_booking");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available seats from the database for the given movie ID
$available_seats = [];
$result = $conn->query("SELECT seat_number FROM seats WHERE movie_id = $movie_id AND status = 'available'");
while ($row = $result->fetch_assoc()) {
    $available_seats[] = $row['seat_number'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Seats</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .seat {
            display: inline-block;
            width: 40px;
            height: 40px;
            margin: 5px;
            background-color: #28a745;
            color: white;
            text-align: center;
            line-height: 40px;
            cursor: pointer;
            border-radius: 5px;
        }
        .seat.selected {
            background-color: #dc3545;
        }
        .payment-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            margin: 20px;
            text-align: center;
        }
    </style>
    <script>
        let selectedSeats = [];

        function selectSeat(seatNumber) {
            const seatElement = document.getElementById(`seat-${seatNumber}`);
            if (selectedSeats.includes(seatNumber)) {
                selectedSeats = selectedSeats.filter(seat => seat !== seatNumber);
                seatElement.classList.remove('selected');
            } else {
                selectedSeats.push(seatNumber);
                seatElement.classList.add('selected');
            }
            document.getElementById('selected_seats').value = JSON.stringify(selectedSeats);
        }
    </script>
</head>
<body>

<div class="payment-container">
    <h1>Select Your Seats</h1>
    <div id="seatSelection">
        <?php foreach ($available_seats as $seat): ?>
            <div class="seat" id="seat-<?php echo $seat; ?>" onclick="selectSeat('<?php echo $seat; ?>')"><?php echo $seat; ?></div>
        <?php endforeach; ?>
    </div>
    <form action="process_payment.php" method="POST">
        <input type="hidden" id="selected_seats" name="seats" value='[]'>
        <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
        <button type="submit">Proceed to Payment</button>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
