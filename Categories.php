<?php
include("ConfigDB.php");
Authunticate();
$UserID = (int)$_SESSION["UserID"];

if (isset($_POST['CategoryBTN'])) {
    $Category = mysqli_real_escape_string($DB, trim($_POST['CategoryIN']));
    $InsertQueryQuestionT = "INSERT INTO categories VALUES (NULL,'$Category',$UserID)";
    $ExceuteAboveQuery = mysqli_query($DB, $InsertQueryQuestionT);
    if ($ExceuteAboveQuery) {
        PrintMessage("Done adding new category", "Normal");
    } else {
        PrintMessage("Failed adding category", "Danger");
    }
}

if (isset($_GET["DeleteCategory"])) {
    $ID = (int)$_GET["DeleteCategory"];
    $DeleteQuery = "DELETE FROM categories WHERE ID = $ID AND User_ID = $UserID";
    mysqli_query($DB, $DeleteQuery);
    PrintMessage("Category deleted", "Danger");
}

$Categories = mysqli_query($DB, "SELECT * FROM categories WHERE User_ID = $UserID ORDER BY Name ASC");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div style="margin-top: 30px;" class="container">
        <div class="main-card">
                <h2>Book Categories</h2>
                <form action="" method="POST" class="mb-3">
                    <label style="font-weight: bold;" for="CategoryIN">Category name:</label>
                    <input class="form-control" type="text" name="CategoryIN" placeholder="Enter new category" required>
                    <button style="margin-top: 15px;" type="submit" name="CategoryBTN" class="btn btn-primary">Add Category</button>
                </form>

                <hr>
                <table class="table table-hover bg-white">
                    <thead>
                    <tr>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($Categories as $Category): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($Category["Name"]); ?></td>
                            <td>
                                <a class="btn btn-danger btn-sm" href="Categories.php?DeleteCategory=<?php echo $Category["ID"]; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
        </div>
    </div>
</body>

</html>