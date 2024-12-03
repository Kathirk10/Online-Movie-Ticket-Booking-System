<?php
session_start(); // Start the session to access session variables
$conn = new mysqli("localhost", "root", "", "movie_booking");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch seat availability from the database
$seats = [];
$result = $conn->query("SELECT seat_number, status FROM seats");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $seats[$row['seat_number']] = $row['status'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seat Reservation</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .seat {
            display: inline-block;
            margin: 5px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }
        .available {
            background-color: #28a745; /* Green for available */
            color: white;
        }
        .booked {
            background-color: #dc3545; /* Red for booked */
            color: white;
            cursor: not-allowed; /* Disable pointer events */
        }
    </style>
</head>
<body>

<h1>Select Your Seats</h1>
<form action="payment.php" method="POST">
    <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($movie_id); ?>"> <!-- Ensure $movie_id is defined -->

    <div class="seat-selection">
        <?php
        // Generate seat labels with color coding based on availability
        $seat_rows = ['A', 'B', 'C']; // Rows of seats
        foreach ($seat_rows as $row) {
            for ($i = 1; $i <= 10; $i++) { // Assuming 10 seats per row
                $seat_number = $row . $i;
                $status = isset($seats[$seat_number]) ? $seats[$seat_number] : 'available'; // Default to available if not found
                $class = $status === 'booked' ? 'booked' : 'available'; // Determine class
                ?>
                <label class="seat <?php echo $class; ?>">
                    <input type="checkbox" name="seats[]" value="<?php echo $seat_number; ?>" <?php echo $status === 'booked' ? 'disabled' : ''; ?>>
                    <?php echo $seat_number; ?>
                </label>
                <?php
            }
        }
        ?>
    </div>

    <div style="text-align: center;">
        <button type="submit">Proceed to Payment</button>
    </div>

</form>

<script>
    document.querySelectorAll('.seat input[type="checkbox"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const seatLabel = this.parentElement;
            if (this.checked) {
                seatLabel.classList.add('selected');
            } else {
                seatLabel.classList.remove('selected');
            }
        });
    });
</script>

</body>
</html>
