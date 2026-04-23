<?php
include("ConfigDB.php");
Authunticate();
$UserID = (int)$_SESSION["UserID"];
$WantedDate = isset($_POST["WantedDate"]) ? mysqli_real_escape_string($DB, $_POST["WantedDate"]) : "";

$HistoryQuery = "SELECT books.Title, books.Author, categories.Name AS CategoryName, books.Status, books.Created_At
                 FROM books INNER JOIN categories ON books.Category_ID = categories.ID
                 WHERE books.User_ID = $UserID";
if ($WantedDate != "") {
    $HistoryQuery .= " AND DATE(books.Created_At) = '$WantedDate'";
}
$HistoryQuery .= " ORDER BY books.Created_At DESC";
$HistoryRows = mysqli_query($DB, $HistoryQuery);
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

    <div class="container mt-4">
        <div class="main-card">
        <h3>Library History</h3>
        <p class="soft-text">Search all books by date of creation.</p>
        <form action="" method="POST" class="mb-3">
            <input class="form-control" required type="date" name="WantedDate" id="">
            <button style="margin:15px 0" type="submit" name="SearchBTN" class="btn btn-primary">Search</button>
        </form>
        <table class="table table-hover bg-white">
            <thead>
            <tr>
                <th>Created At</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($HistoryRows as $Row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($Row["Created_At"]); ?></td>
                    <td><?php echo htmlspecialchars($Row["Title"]); ?></td>
                    <td><?php echo htmlspecialchars($Row["Author"]); ?></td>
                    <td><?php echo htmlspecialchars($Row["CategoryName"]); ?></td>
                    <td><?php echo htmlspecialchars($Row["Status"]); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</body>

</html>