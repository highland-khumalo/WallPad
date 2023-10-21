<?php
include "func.php";

create_table('wallpapers', $columns);


$sql = "SELECT * FROM wallpapers WHERE device = 'mobile' ORDER BY date_uploaded DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html  data-theme="light">

<?php render("head", $config['name'] . " : Get High-Quality Wallpapers Free"); ?>

<body  data-theme="light">

    <?php render("nav"); ?>

    <div class="hero min-h-screen bg-base-100">
        <div class="hero-content text-center">
            <a href="mobile.php" class="card w-96  shadow-xl">
                <button class="btn  card-body">Mobile Wallpapers</button>
            </a>
            <br>
            <br>
            <a href="desktop.php" class="card w-96  shadow-xl">
                <button class="btn card-body">Desktop Wallpapers</button>
            </a>
        </div>
    </div>
</body>