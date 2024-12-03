<?php

$conn = new mysqli("localhost", "root", "", "movie_booking");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$search = isset($_GET['search']) ? $_GET['search'] : '';


$query = $conn->prepare("SELECT * FROM movies WHERE movie_title LIKE ?");
$searchParam = '%' . $search . '%';
$query->bind_param("s", $searchParam);
$query->execute();
$result = $query->get_result();

$marquee_query = "SELECT image FROM movies";
$marquee_result = $conn->query($marquee_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Booking</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Pvr Cinemas</h1>

<header class="site-header">
    <div class="container">
        <div class="logo">
            <img src="log.jpg" alt="Movie Booking Logo" />
        </div>
        <nav class="nav-menu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="#movies">Movies</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </nav>
    </div>
</header>



<form method="GET" action="index.php" class="search-form">
    <input type="text" name="search" placeholder="Search for a movie..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>


<div class="marquee">
    <div class="marquee-content">
        <?php
        if ($marquee_result->num_rows > 0) {
            while ($row = $marquee_result->fetch_assoc()) {
                echo '<img src="uploads/' . htmlspecialchars($row['image']) . '" alt="Movie Image">';
            }
        }
        ?>
    </div>
</div>
<section id="movies">
<div class="movie-grid">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="movie-card">';
            echo '<img src="uploads/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['movie_title']) . '">';
            echo '<h3>' . htmlspecialchars($row['movie_title']) . '</h3>';
            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
            echo '<a href="login.php" class="btn">Book Tickets</a>';
            echo '</div>';
            
        }
    } else {
        echo "<p>No movies found.</p>";
    }
    ?>
</div>
</section>
</body>
</html>

<?php

$conn->close();
?>
