<?php
$conn = new mysqli("localhost", "root", "", "movie_booking");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_seats = isset($_POST['seats']) ? $_POST['seats'] : [];

    if (!empty($selected_seats)) {
        
        echo "<p>Seats Selected: " . implode(', ', $selected_seats) . "</p>";
    } else {
        echo "No seats selected.";
    }
} else {
    echo "No seat selection data found.";
}
?>

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

    h1 {
        color: #333;
    }

    label {
        display: block;
        margin: 10px 0 5px;
        color: #555;
        font-size: 14px;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    button {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
        margin-top: 10px;
    }

    button:hover {
        background-color: #218838;
    }

    .message {
        margin-bottom: 20px;
        font-size: 16px;
        color: #333;
    }
</style>

<div class="payment-container">
    <div class="message">
        <?php if (!empty($selected_seats)) { ?>
            <h1>Confirm Your Payment</h1>
            <p>Seats Selected: <?php echo implode(', ', $selected_seats); ?></p>
        <?php } ?>
    </div>

    <form action="process_payment.php" method="POST">
        <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>"> <!-- Ensure movie_id is set -->
    <input type="hidden" name="seats" value="<?php echo htmlspecialchars(json_encode($selected_seats)); ?>">

        <label for="card">Card Number:</label>
        <input type="text" id="card" name="card" placeholder="Enter your card number" required>

        <label for="expiry">Expiry Date:</label>
        <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>

        <label for="cvv">CVV:</label>
        <input type="text" id="cvv" name="cvv" placeholder="123" required>

        <button type="submit">Make Payment</button>
    </form>
</div>
