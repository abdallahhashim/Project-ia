<?php
include("ConfigDB.php");
Authunticate();
$UserID = (int)$_SESSION["UserID"];
$Search = "";

if (isset($_GET["DeleteBook"])) {
    $ID = (int)$_GET["DeleteBook"];
    $DeleteQuery = "DELETE FROM books WHERE ID = $ID AND User_ID = $UserID";
    $ExecuteAboveQuery = mysqli_query($DB, $DeleteQuery);
    if ($ExecuteAboveQuery) {
        PrintMessage("Done deleting book", "Danger");
    } else {
        PrintMessage("Failed deleting book", "Danger");
    }
}

if (isset($_GET["ToggleStatus"])) {
    $ID = (int)$_GET["ToggleStatus"];
    $CurrentStatus = $_GET["Current"] === "Borrowed" ? "Borrowed" : "Available";
    $NewStatus = $CurrentStatus === "Borrowed" ? "Available" : "Borrowed";
    $UpdateQuery = "UPDATE books SET Status = '$NewStatus' WHERE ID = $ID AND User_ID = $UserID";
    mysqli_query($DB, $UpdateQuery);
    PrintMessage("Book status changed to $NewStatus", "Normal");
}

if (isset($_GET["DONEUPDATE"])) {
    PrintMessage("Done updating", "Normal");
}

if (isset($_GET["search"])) {
    $Search = mysqli_real_escape_string($DB, trim($_GET["search"]));
}

$BooksQuery = "SELECT books.ID, books.Title, books.Author, categories.Name AS CategoryName, books.Publish_Year, books.Status, books.Description
               FROM books INNER JOIN categories ON categories.ID = books.Category_ID
               WHERE books.User_ID = $UserID";
if ($Search !== "") {
    $BooksQuery .= " AND (books.Title LIKE '%$Search%' OR books.Author LIKE '%$Search%' OR categories.Name LIKE '%$Search%')";
}
$BooksQuery .= " ORDER BY books.ID DESC";
$Books = mysqli_query($DB, $BooksQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        td {
            unicode-bidi: plaintext;
        }
        h3{
            text-align: center;
        }
    </style>
</head>

<body>

<div class="container mt-4">
    <div class="main-card">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h3 class="mb-2">Your Library</h3>
        <a href="AddBook.php" class="btn btn-primary btn-sm">+ Add Book</a>
    </div>

    <form class="form-inline mb-3" method="GET">
        <input class="form-control mr-2" type="text" name="search" placeholder="Search title, author, category" value="<?php echo htmlspecialchars($Search); ?>">
        <button class="btn btn-outline-primary" type="submit">Search</button>
    </form>

    <table class="table table-hover bg-white">
        <thead>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Year</th>
            <th>Status</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($Books as $Book): ?>
            <tr>
                <td><?php echo htmlspecialchars($Book["Title"]); ?></td>
                <td><?php echo htmlspecialchars($Book["Author"]); ?></td>
                <td><?php echo htmlspecialchars($Book["CategoryName"]); ?></td>
                <td><?php echo htmlspecialchars($Book["Publish_Year"]); ?></td>
                <td>
                    <span class="badge <?php echo $Book["Status"] === "Borrowed" ? "badge-warning" : "badge-success"; ?>">
                        <?php echo $Book["Status"]; ?>
                    </span>
                </td>
                <td><?php echo htmlspecialchars($Book["Description"]); ?></td>
                <td>
                    <a href="Library.php?ToggleStatus=<?php echo $Book["ID"]; ?>&Current=<?php echo $Book["Status"]; ?>" class="btn btn-info btn-sm">Status</a>
                    <a href="EditBook.php?EditBook=<?php echo $Book["ID"]; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="Library.php?DeleteBook=<?php echo $Book["ID"]; ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>


</body>

</html>