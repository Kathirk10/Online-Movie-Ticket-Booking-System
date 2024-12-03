<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["image"]["name"]);

if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    $conn = new mysqli("localhost", "root", "", "movie_booking");

    $movie_title = $_POST['movie_title'];
    $description = $_POST['description'];
    $release_date = $_POST['release_date'];
    $genre = $_POST['genre'];
    $image = basename($_FILES["image"]["name"]);

    $insert_query = "INSERT INTO movies (movie_title, image, description, release_date, genre)
                     VALUES ('$movie_title', '$image', '$description', '$release_date', '$genre')";

    if ($conn->query($insert_query) === TRUE) {
        echo "Movie uploaded successfully!";
    } else {
        echo "Error: " . $insert_query . "<br>" . $conn->error;
    }
} else {
    echo "Sorry, there was an error uploading your file.";
}
?>
