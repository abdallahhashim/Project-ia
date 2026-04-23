<?php
include("ConfigDB.php");
IsUserLoggedIn();
$Username = NULL;
$Password = NULL;
$ConfirmPassword = NULL;
$Email = NULL;
//When User Click On Sign Up Button, will save Data In Database 
if (isset($_POST['SignUpBTN'])) {
    $Username = mysqli_real_escape_string($DB, trim($_POST['Username']));
    $Password =  mysqli_real_escape_string($DB, trim($_POST['Password']));
    $ConfirmPassword =  mysqli_real_escape_string($DB, trim($_POST['ConfirmPassword']));
    $Email =  mysqli_real_escape_string($DB, trim($_POST['Email']));
    //If Pssword is not equal to Confirm Password
    if ($Username == "" || $Email == "" || $Password == "") {
        PrintMessage("All fields are required", "Danger");
    } elseif ($Password != $ConfirmPassword) {
        PrintMessage("Password Is Not Matched", "Danger");
    } 
    // Else means everything is fine
    else {
        $CheckEmailQuery = "SELECT ID FROM users WHERE Email = '$Email' LIMIT 1";
        $CheckEmailResult = mysqli_query($DB, $CheckEmailQuery);
        if ($CheckEmailResult && mysqli_num_rows($CheckEmailResult) > 0) {
            PrintMessage("This email is already used", "Danger");
        } else {
            $InsertQuery = "INSERT INTO `users` VALUES(NULL,'$Username','$Password','$Email')";
            $ExecuteAboveQuery = mysqli_query($DB, $InsertQuery);
            if ($ExecuteAboveQuery) {
                header("location:Login.php?DoneRegist=1");
                exit;
            } else {
                PrintMessage("Sign up failed: " . mysqli_error($DB), "Danger");
            }
        }
    }
}
?>




<div class="auth-wrap">
    <div class="main-card auth-card">
        <h2 class="mb-1">Create account</h2>
        <p class="soft-text mb-4">Start building your personal book library.</p>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" value="<?php echo htmlspecialchars((string)$Username); ?>" name="Username" class="form-control" placeholder="Your name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" value="<?php echo htmlspecialchars((string)$Email); ?>" name="Email" class="form-control" placeholder="you@email.com" required>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Password</label>
                    <input type="password" name="Password" class="form-control" placeholder="Password" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Confirm Password</label>
                    <input type="password" name="ConfirmPassword" class="form-control" placeholder="Confirm password" required>
                </div>
            </div>
            <button type="submit" name="SignUpBTN" class="btn btn-primary btn-block">Create Account</button>
            <a href="Login.php" class="btn btn-link btn-block">Back to login</a>
        </form>
    </div>
</div>