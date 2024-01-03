<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title>Storage Page</title>
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

    <h2 class="header">Storage Information</h2>
    <?php
    include 'db.php';

    function getListStorage($conn)
    {
        $sql = "SELECT sto_id, sto_shelf, sto_block FROM storage";
        $result = $conn->query($sql);
        return $result;
    }

    $listOfStorage = getListStorage($conn);

    if ($listOfStorage->num_rows > 0) {
        // header
        echo "<table>";
        echo "<tr><th>ID</th><th>Shelf</th><th>Block</th></tr>";
        // rows
        while ($row = $listOfStorage->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["sto_id"] . "</td>";
            echo "<td>" . $row["sto_shelf"] . "</td>";
            echo "<td>" . $row["sto_block"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No storage found.";
    }
    ?>

    <h2>Create New Storage</h2>

    <form action="storage/create.php" method="post">
        <div>
            <label for="shelf">Shelf:</label>
            <input type="text" name="shelf" required>
        </div>
        <div>
            <label for="block">Block:</label>
            <input type="text" name="block" required>
        </div>
        <input type="submit" value="Create Storage">
    </form>

</body>

</html>
<?php
$conn->close();
?>
