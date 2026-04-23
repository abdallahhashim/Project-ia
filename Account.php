<?php
include("ConfigDB.php");
Authunticate();

$UserID = (int)$_SESSION["UserID"];
$CurrentUserQuery = mysqli_query($DB, "SELECT Name, Email FROM users WHERE ID = $UserID LIMIT 1");
$CurrentUser = $CurrentUserQuery ? mysqli_fetch_assoc($CurrentUserQuery) : null;

if (!$CurrentUser) {
    session_unset();
    session_destroy();
    setcookie("RememberMe", "", time() - 3600);
    header("location:Login.php");
    exit;
}

$Name = $CurrentUser["Name"];
$Email = $CurrentUser["Email"];

if (isset($_POST["UpdateAccountBTN"])) {
    $Name = mysqli_real_escape_string($DB, trim($_POST["Name"]));
    $Email = mysqli_real_escape_string($DB, trim($_POST["Email"]));
    $Password = mysqli_real_escape_string($DB, trim($_POST["Password"]));
    $ConfirmPassword = mysqli_real_escape_string($DB, trim($_POST["ConfirmPassword"]));

    if ($Name === "" || $Email === "") {
        PrintMessage("Name and email are required", "Danger");
    } elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        PrintMessage("Email format is not valid", "Danger");
    } elseif ($Password !== "" && $Password !== $ConfirmPassword) {
        PrintMessage("Password is not matched", "Danger");
    } else {
        $CheckEmailQuery = "SELECT ID FROM users WHERE Email = '$Email' AND ID <> $UserID LIMIT 1";
        $CheckEmailResult = mysqli_query($DB, $CheckEmailQuery);

        if ($CheckEmailResult && mysqli_num_rows($CheckEmailResult) > 0) {
            PrintMessage("This email is already used", "Danger");
        } else {
            $UpdateQuery = "UPDATE users SET Name = '$Name', Email = '$Email'";
            if ($Password !== "") {
                $UpdateQuery .= ", Password = '$Password'";
            }
            $UpdateQuery .= " WHERE ID = $UserID";

            if (mysqli_query($DB, $UpdateQuery)) {
                PrintMessage("Account updated successfully. Changes are live in database.", "Normal");
            } else {
                PrintMessage("Failed updating account", "Danger");
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
</head>
<body>
<div class="container mt-4">
    <div class="main-card">
        <h2>Account Settings</h2>
        <p class="soft-text">Update your account and see it directly in phpMyAdmin.</p>
        <form method="POST" class="mt-4">
            <div class="form-group">
                <label>Name</label>
                <input class="form-control" type="text" name="Name" required value="<?php echo htmlspecialchars($Name); ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="email" name="Email" required value="<?php echo htmlspecialchars($Email); ?>">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>New Password (optional)</label>
                    <input class="form-control" type="password" name="Password" placeholder="Leave empty to keep current password">
                </div>
                <div class="form-group col-md-6">
                    <label>Confirm New Password</label>
                    <input class="form-control" type="password" name="ConfirmPassword" placeholder="Repeat new password">
                </div>
            </div>
            <button type="submit" name="UpdateAccountBTN" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>
</body>
</html>
