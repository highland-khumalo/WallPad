<?php

include "func.php";

create_table('wallpapers', $columns);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $device = $_POST['device'];
    $password = $_POST['pass'];

    // Validate and process file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK && $password = "HardCodedPassword") {
        $filename = $_FILES['file']['name'];
        $destination = 'uploads/' . $filename;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
            $date_uploaded = date('Y-m-d H:i:s');
            $conn = new mysqli('localhost', 'root', '', 'walltape');
            $sql = "INSERT INTO wallpapers (title, category, device, description, date_uploaded, filename) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssss', $title, $category, $device, $description, $date_uploaded, $filename);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            echo "Wallpaper uploaded successfully!";
        } else {
            echo "Error uploading the wallpaper.";
        }
    } else {
        echo "Invalid file upload or password.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title><?= $config['name'] ?>: Upload new image</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
</head>

<body>
    <h1>Upload Wallpaper</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        Title: <input type="text" name="title"><br>
        Category: <input type="text" name="category"><br>
        Device (mobile/desktop): <input type="text" name="device"><br>
        Description: <textarea name="description"></textarea><br>
        Wallpaper: <input type="file" name="file"><br>
        <br>
        Admin Pass: <input type="password" name="pass"><br>
        <input type="submit" value="Upload">
    </form>
</body>

</html>