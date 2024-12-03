<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Movie</title>
</head>
<body>
    <h2>Add a New Movie</h2>
    <form method="POST" action="upload_movie.php" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Movie Title" required>
        <textarea name="description" placeholder="Movie Description"></textarea>
        <input type="file" name="image" accept="image/*" required>
        <input type="date" name="release_date" required>
        <input type="text" name="genre" placeholder="Genre" required>
        <button type="submit">Upload Movie</button>
    </form>
</body>
</html>
