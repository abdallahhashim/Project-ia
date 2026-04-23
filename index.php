<?php
include("ConfigDB.php");
$UserName = "Reader";

if (isset($_GET["LogOut"])) {
    session_unset();
    session_destroy();
    setcookie("RememberMe", "", time() - 3600);
    header("location:Login.php");
    exit;
}

Authunticate();
$UserID = (int)$_SESSION["UserID"];
$UserQuery = mysqli_query($DB, "SELECT Name FROM users WHERE ID = $UserID");
if ($UserQuery && mysqli_num_rows($UserQuery) === 1) {
    $UserData = mysqli_fetch_assoc($UserQuery);
    $UserName = $UserData["Name"];
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booked Beyond Home</title>
</head>

<body>
<div class="container mt-4">
    <div class="hero-card">
        <h1 class="display-5 mb-3">Welcome, <?php echo htmlspecialchars($UserName); ?>!</h1>
        <p class="lead">Booked Beyond on PHP + MySQL with a fresh new UI.</p>
        <hr class="my-4">
        <p class="soft-text">Manage books, categories, borrowing status, and history from one clean dashboard.</p>
        <a class="btn btn-primary btn-lg mr-2" href="Library.php">Open Library</a>
        <a class="btn btn-outline-dark btn-lg" href="AddBook.php">Add New Book</a>
    </div>
</div>

</body>

</html>