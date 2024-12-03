<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies</title>
</head>
<body>
    <h1>Available Movies</h1>

    <?php
    
    $movies = [
        ['id' => 1, 'title' => 'Movie 1', 'show_time' => '2024-10-22 18:00'],
        ['id' => 2, 'title' => 'Movie 2', 'show_time' => '2024-10-22 20:00'],
    ];

    foreach ($movies as $movie) {
        echo "<h2>{$movie['title']}</h2>";
        echo "<p>Show Time: {$movie['show_time']}</p>";
        
        // Add the booking form for this movie
        echo '<form action="process_payment.php" method="POST">
                <input type="hidden" name="movie_id" value="' . $movie['id'] . '">
                <input type="hidden" name="seats" value=\'["A1", "A2"]\'>
                <input type="text" name="user_name" placeholder="Enter your name" required>
                <button type="submit">Book Tickets</button>
              </form>';
    }
    ?>
</body>
</html>
