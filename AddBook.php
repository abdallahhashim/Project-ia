<?php
include("ConfigDB.php");
Authunticate();
$UserID = (int)$_SESSION["UserID"];
$Categories = mysqli_query($DB, "SELECT * FROM categories ORDER BY Name ASC");

if (isset($_POST["AddBookBTN"])) {
    $Title = mysqli_real_escape_string($DB, trim($_POST["Title"]));
    $Author = mysqli_real_escape_string($DB, trim($_POST["Author"]));
    $CategoryID = (int)$_POST["CategoryID"];
    $Year = mysqli_real_escape_string($DB, trim($_POST["PublishYear"]));
    $Description = mysqli_real_escape_string($DB, trim($_POST["Description"]));

    if ($Title == "" || $Author == "" || $CategoryID <= 0) {
        PrintMessage("Title, author and category are required", "Danger");
    } else {
        $InsertQuery = "INSERT INTO books VALUES (NULL,$UserID,'$Title','$Author',$CategoryID,'$Year','Available','$Description',NOW())";
        $ExecuteAboveQuery = mysqli_query($DB, $InsertQuery);
        if ($ExecuteAboveQuery) {
            PrintMessage("Done adding new book", "Normal");
        } else {
            PrintMessage("Failed adding book", "Danger");
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
    <title>Document</title>
    <style>
        input,
        textarea {
            unicode-bidi: plaintext;
        }
    </style>
</head>

<body>
<div class="container mt-4">
    <div class="main-card">
    <h2>Add New Book</h2>
    <p class="soft-text">Create a new book record in your library.</p>

    <form action="" method="POST" class="mt-4">
        <div class="form-group">
            <label>Title</label>
            <input class="form-control" type="text" name="Title" placeholder="Book title" required>
        </div>
        <div class="form-group">
            <label>Author</label>
            <input class="form-control" type="text" name="Author" placeholder="Author name" required>
        </div>
        <div class="form-group">
            <label>Category</label>
            <select class="form-control" name="CategoryID" required>
                <option value="">Select category</option>
                <?php foreach ($Categories as $Category): ?>
                    <option value="<?php echo $Category["ID"]; ?>"><?php echo htmlspecialchars($Category["Name"]); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Publish Year</label>
            <input class="form-control" type="text" name="PublishYear" placeholder="2024">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" name="Description" placeholder="Small description"></textarea>
        </div>
        <button type="submit" name="AddBookBTN" class="btn btn-primary px-4">Add Book</button>
    </form>
    </div>
</div>

</body>

</html>