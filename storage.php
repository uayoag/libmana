<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title>Storage Page</title>
</head>

<body style="background: #DED0B6;padding: 40px">
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
        </ul>
    </div>

    <h2 class="header">Storage Information</h2>
    <form method="GET" action="">
    </form>
    <?php
    include 'db.php';

    function getListBook($conn)
    {
        $sql = "SELECT 
        b.book_id,
        b.book_title,
        b.book_edition,
        b.book_publisher,
        b.book_year,
        b.sto_id,
        GROUP_CONCAT(DISTINCT a.au_name ORDER BY a.au_name ASC SEPARATOR ', ') AS authors,
        GROUP_CONCAT(DISTINCT c.cat_name ORDER BY c.cat_name ASC SEPARATOR ', ') AS categories
    FROM
        book b
    LEFT JOIN 
        book_author ba ON b.book_id = ba.book_id
    LEFT JOIN 
        author a ON ba.au_id = a.au_id
    LEFT JOIN 
        book_cate bc ON b.book_id = bc.book_id
    LEFT JOIN 
        category c ON bc.cat_id = c.cat_id";
        $result = $conn->query($sql);
        return $result;
    }
    function getListStorage($conn)
    {
        $sql = "SELECT book.book_title, storage.* FROM book
                JOIN storage ON book.sto_id = storage.sto_id";
        $result = $conn->query($sql);
        return $result;
    }
    

    $listOfStorage = getListStorage($conn);

    if ($listOfStorage->num_rows > 0) {
        // header
        echo "<table>";
        echo "<tr><th>ID</th><th>Shelf</th><th>Block</th><th>Delete</th></tr>";
        // rows
        while ($row = $listOfStorage->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["sto_id"] . "</td>";
            echo "<td>" . $row["sto_shelf"] . "</td>";
            echo "<td>" . $row["sto_block"] . "</td>";
            echo "<td>";
            echo '<form method="POST" action="book/delete.php">';
            echo '<input type="hidden" name="id" value="' . $row["sto_id"] . '" />';
            echo '<input class="submit-button delete" type="submit" value="Delete" />';
            echo '</form>';
            echo "</td>";
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