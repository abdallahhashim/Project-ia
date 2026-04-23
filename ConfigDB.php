<?php
session_start();

// Connect to database
$UserNameOfDB = "root";
$Password = "";
$HostName = "localhost";
$DataBaseName = "booked_beyond";
$DB = mysqli_connect($HostName, $UserNameOfDB, $Password, $DataBaseName);
if ($DB) {
    $DB->set_charset("utf8");
}

date_default_timezone_set("Africa/Cairo");

function Authunticate()
{
    global $DB;

    if (!isset($_SESSION["UserID"]) && !isset($_COOKIE["RememberMe"])) {
        header("location:Login.php");
        exit;
    }

    if (isset($_COOKIE["RememberMe"])) {
        $_SESSION["UserID"] = (int)$_COOKIE["RememberMe"];
    }

    $CurrentUserID = isset($_SESSION["UserID"]) ? (int)$_SESSION["UserID"] : 0;
    if ($CurrentUserID <= 0) {
        session_unset();
        session_destroy();
        setcookie("RememberMe", "", time() - 3600);
        header("location:Login.php");
        exit;
    }

    $CheckUser = mysqli_query($DB, "SELECT ID FROM users WHERE ID = $CurrentUserID LIMIT 1");
    if (!$CheckUser || mysqli_num_rows($CheckUser) !== 1) {
        session_unset();
        session_destroy();
        setcookie("RememberMe", "", time() - 3600);
        header("location:Login.php");
        exit;
    }
}

function IsUserLoggedIn()
{
    global $DB;

    if (isset($_SESSION["UserID"]) || isset($_COOKIE["RememberMe"])) {
        if (isset($_COOKIE["RememberMe"])) {
            $_SESSION["UserID"] = (int)$_COOKIE["RememberMe"];
        }

        $CurrentUserID = isset($_SESSION["UserID"]) ? (int)$_SESSION["UserID"] : 0;
        if ($CurrentUserID > 0) {
            $CheckUser = mysqli_query($DB, "SELECT ID FROM users WHERE ID = $CurrentUserID LIMIT 1");
            if ($CheckUser && mysqli_num_rows($CheckUser) === 1) {
                header("location:index.php");
                exit;
            }
        }
    }
}

function PrintMessage($text, $Type)
{
    if ($Type == "Danger") {
        echo "<div style='text-align:center;margin:15px auto' class='alert alert-danger' role='alert'>" . $text . "</div>";
    } else {
        echo "<div style='text-align:center;margin:15px auto' class='alert alert-primary' role='alert'>" . $text . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booked Beyond (PHP)</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/theme.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark modern-nav">
    <div class="container">
        <a class="navbar-brand" href="index.php"><span class="brand-dot">B</span>ooked Beyond</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto nav-pill-group">
                <li class="nav-item"><a class="nav-link" href="Library.php">Library</a></li>
                <li class="nav-item"><a class="nav-link" href="AddBook.php">Add Book</a></li>
                <li class="nav-item"><a class="nav-link" href="History.php">History</a></li>
                <li class="nav-item"><a class="nav-link" href="Categories.php">Categories</a></li>
                <?php if (isset($_SESSION["UserID"])) : ?>
                    <li class="nav-item"><a class="nav-link" href="Account.php">Account</a></li>
                <?php endif; ?>
                <?php if (!isset($_SESSION["UserID"])) : ?>
                    <li class="nav-item"><a class="nav-link auth-link" href="Login.php">Login</a></li>
                <?php else : ?>
                    <li class="nav-item"><a id="Logout" class="nav-link auth-link" href="index.php?LogOut=1">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>