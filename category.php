<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title>Category Page</title>
</head>

<body style="background: #e6a681;padding: 40px">
    <div id="menu">
        <ul>
            <li>
                <a href="index.html">Home</a>
            </li>
            <li>
                <a href="book.php">Book</a>
            </li>
            <li>
                <a href="author.php">Author</a>
            </li>
            <li>
                <a href="category.php">Category</a>
            </li>
            <li>
                <a href="storage.php">Storage</a>
            </li>
            <li>
                <a href="search.php">Search</a>
            </li>
            <li>
                <a href="update.php">Update</a>
            </li>
        </ul>
    </div>

    <h2 class="header">Category Information</h2>
    <?php
    include 'db.php';

    function getListCategory($conn)
    {
        $sql = "SELECT cat_id, cat_name, cat_keyword, cat_language FROM category";
        $result = $conn->query($sql);
        return $result;
    }

    $listOfCategory = getListCategory($conn);

    if ($listOfCategory->num_rows > 0) {
        // header
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Keyword</th><th>Language</th></tr>";
        // rows
        while ($row = $listOfCategory->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["cat_id"] . "</td>";
            echo "<td>" . $row["cat_name"] . "</td>";
            echo "<td>" . $row["cat_keyword"] . "</td>";
            echo "<td>" . $row["cat_language"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No categories found.";
    }
    ?>

    <h2>Create New Category</h2>

    <form action="category/create.php" method="post">
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label for="keyword">Keyword:</label>
            <input type="text" name="keyword" required>
        </div>
        <div>
            <label for="language">Language:</label>
            <input type="text" name="language" required>
        </div>
        <input type="submit" value="Create Category">
    </form>

</body>

</html>
<?php
$conn->close();
?>
