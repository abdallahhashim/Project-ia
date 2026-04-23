<?php
include("ConfigDB.php");
Authunticate();
$UserID = (int)$_SESSION["UserID"];

$ID = isset($_GET["EditBook"]) ? (int)$_GET["EditBook"] : 0;
$BookQuery = mysqli_query($DB, "SELECT * FROM books WHERE ID = $ID AND User_ID = $UserID");
$BookData = $BookQuery ? mysqli_fetch_assoc($BookQuery) : null;
$Categories = mysqli_query($DB, "SELECT * FROM categories ORDER BY Name ASC");

if (!$BookData) {
    header("location:Library.php");
    exit;
}

if (isset($_POST["UpdateBookBTN"])) {
    $Title = mysqli_real_escape_string($DB, trim($_POST["Title"]));
    $Author = mysqli_real_escape_string($DB, trim($_POST["Author"]));
    $CategoryID = (int)$_POST["CategoryID"];
    $Year = mysqli_real_escape_string($DB, trim($_POST["PublishYear"]));
    $Status = $_POST["Status"] === "Borrowed" ? "Borrowed" : "Available";
    $Description = mysqli_real_escape_string($DB, trim($_POST["Description"]));

    $UpdateQuery = "UPDATE books SET Title='$Title', Author='$Author', Category_ID=$CategoryID, Publish_Year='$Year', Status='$Status', Description='$Description' WHERE ID = $ID AND User_ID = $UserID";
    mysqli_query($DB, $UpdateQuery);
    header("location:Library.php?DONEUPDATE=1");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        textarea{
            unicode-bidi:plaintext;
        }
    </style>
</head>

<body>
<div style="margin-top: 30px;" class="container">
    <div class="main-card">
    <h2>Edit Book</h2>
    <form action="" method="POST">
        <label>Title</label>
        <input class="form-control mb-2" type="text" name="Title" value="<?php echo htmlspecialchars($BookData["Title"]); ?>" required>

        <label>Author</label>
        <input class="form-control mb-2" type="text" name="Author" value="<?php echo htmlspecialchars($BookData["Author"]); ?>" required>

        <label>Category</label>
        <select class="form-control mb-2" name="CategoryID" required>
            <?php foreach ($Categories as $Category): ?>
                <option value="<?php echo $Category["ID"]; ?>" <?php echo ((int)$BookData["Category_ID"] === (int)$Category["ID"]) ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($Category["Name"]); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Publish Year</label>
        <input class="form-control mb-2" type="text" name="PublishYear" value="<?php echo htmlspecialchars($BookData["Publish_Year"]); ?>">

        <label>Status</label>
        <select class="form-control mb-2" name="Status">
            <option value="Available" <?php echo $BookData["Status"] === "Available" ? "selected" : ""; ?>>Available</option>
            <option value="Borrowed" <?php echo $BookData["Status"] === "Borrowed" ? "selected" : ""; ?>>Borrowed</option>
        </select>

        <label>Description</label>
        <textarea class="form-control mb-3" name="Description"><?php echo htmlspecialchars($BookData["Description"]); ?></textarea>

        <button type="submit" name="UpdateBookBTN" class="btn btn-primary">Save Changes</button>
        <a href="Library.php" class="btn btn-outline-secondary">Cancel</a>
    </form>
    </div>
</div>

</body>

</html>