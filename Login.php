<?php
include("ConfigDB.php");

//Debug
// echo "Login Page";

IsUserLoggedIn();
// echo $_COOKIE['RememberMe'];
//When User Click On Sign in Button, will check if Data exist In Database or not 
if (isset($_POST['LogInBTN'])) {
    $UserName = mysqli_real_escape_string($DB, $_POST['Username']);
    $Password = mysqli_real_escape_string($DB, $_POST['Password']);
    $RememberMe = NULL;
    if(isset($_POST['RememberMe'])) $RememberMe =$_POST['RememberMe'];
    if(!empty($RememberMe)) $RememberMe = 1;
    else $RememberMe = 0;
    $Check = "SELECT * FROM `users` WHERE (Name = '$UserName' OR Email = '$UserName') AND Password = '$Password' ";
    $ExecuteAboveStatement  = mysqli_query($DB, $Check);
    $NumOfRows = mysqli_num_rows($ExecuteAboveStatement);
    if ($NumOfRows == 1) {
        $FetchData = mysqli_fetch_array($ExecuteAboveStatement);
        $_SESSION['UserID'] = $FetchData['ID'];
        if($RememberMe) setcookie("RememberMe",$_SESSION['UserID'],time() + 2 * 24 * 60 * 60);
        header('location:index.php?DoneLogIn=1');
    } else  PrintMessage("User Is Not Exist", "Danger");
}



// When User Sign Up For First Time And After Redircting From Sigup Page To Login Page (this Page), an Alert Will Be Shown
if (isset($_GET['DoneRegist'])) {
    PrintMessage("Done Creating Account", "Normal");
}




?>



<div class="auth-wrap">
    <div class="main-card auth-card">
        <h2 class="mb-1">Welcome back</h2>
        <p class="soft-text mb-4">Login to manage your library.</p>
        <form method="POST">
            <div class="form-group">
                <label>Username or Email</label>
                <input required type="text" name="Username" class="form-control" placeholder="ex: ahmed or you@email.com" />
            </div>
            <div class="form-group">
                <label>Password</label>
                <input required type="password" name="Password" class="form-control" placeholder="Your password" />
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="RememberMe" id="rememberMe" value="1">
                <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <button type="submit" name="LogInBTN" class="btn btn-primary btn-block">Login</button>
            <a href="Register.php" class="btn btn-link btn-block">Create account</a>
        </form>
    </div>
</div>