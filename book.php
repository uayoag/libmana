<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title>Book Page</title>
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
                <a href="storage,php">Storage</a>
            </li>
        </ul>
    </div>

    <h2 class="header">Book Information</h2>
    <?php
    // Connect to db
    include 'db.php';

 
    function getListBook($conn)
    {
        $sql = "SELECT b.book_id as bid, sto_shelf , au_name , cat_name , book_edition, book_publisher, book_year, book_title 
        FROM book b 
        LEFT JOIN book_author ON b.book_id = book_author.book_id 
        LEFT JOIN author ON book_author.au_id = author.au_id 
        LEFT JOIN storage ON b.sto_id = storage.sto_id 
        LEFT JOIN book_cate ON b.book_id = book_cate.book_id 
        LEFT JOIN category ON book_cate.cat_id = category.cat_id 
        GROUP BY b.book_id;";
        $result = $conn->query($sql);
        return $result;
    }

    function getListAuthor($conn)
    {
        $sql = "SELECT au_id, au_name FROM author";
        $result = $conn->query($sql);
        return $result;
    }
    function getListStorage($conn)
    {
        $sql = "SELECT sto_id, sto_shelf FROM storage";
        $result = $conn->query($sql);
        return $result;
    }
    function getListCategory($conn)
    {
        $sql = "SELECT cat_id, cat_name FROM category";
        $result = $conn->query($sql);
        return $result;
    }
    $listOfBook = getListBook($conn);


    if ($listOfBook->num_rows > 0) {
        // header
        echo "<table>";
        echo "<tr><th>ID</th><th>Title</th><th>Storage</th><th>Author</th><th>Category</th><th>Edition</th><th>Publisher</th><th>Year</th></tr>";
        // rows
        while ($row = $listOfBook->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["bid"] . "</td>";
            echo "<td>" . $row["book_title"] . "</td>";
            echo "<td>" . $row["sto_shelf"] . "</td>";
            echo "<td>" . $row["au_name"] . "</td>";
            echo "<td>" . $row["cat_name"] . "</td>";
            echo "<td>" . $row["book_edition"] . "</td>";
            echo "<td>" . $row["book_publisher"] . "</td>";
            echo "<td>" . $row["book_year"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No books found.";
    }
    ?>

    <h2>Create New Book</h2>

    <form action="book/create.php" method="post">
        <div>
            <label for="title">Title:</label>
            <input type="text" name="title" required>
        </div>
        <div>
            <label for="edition">Edition:</label>
            <input type="text" name="edition" required>
        </div>

        <div>
            <label for="publisher">Publisher:</label>
            <input type="text" name="publisher" required>
        </div>
        <div>
            <label for="year">Year:</label>
            <input type="number" name="year" required>
        </div>
        <div>
            <label for="authorID">Author:</label>
            <select name="authorID" required>
                <option value="auto">Choose the author</option>
                <?php
                $listOfAuthor = getListAuthor($conn);
                if ($listOfAuthor->num_rows > 0) {
                    while ($author = $listOfAuthor->fetch_assoc()) {
                        echo "<option value='{$author['au_id']}'>{$author['au_name']}</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div>
            <label for="storageID">Storage:</label>
            <select name="storageID" required>
                <option value="auto">Choose the storage</option>
                <?php
                $listOfStorage = getListStorage($conn);
                if ($listOfStorage->num_rows > 0) {
                    while ($storage = $listOfStorage->fetch_assoc()) {
                        echo "<option value='{$storage['sto_id']}'>{$storage['sto_shelf']}</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div>
            <label for="categoryID">Category:</label>
            <select name="categoryID" required>
                <option value="auto">Choose the category</option>
                <?php
                $listOfCategory = getListCategory($conn);
                if ($listOfCategory->num_rows > 0) {
                    while ($category = $listOfCategory->fetch_assoc()) {
                        echo "<option value='{$category['cat_id']}'>{$category['cat_name']}</option>";
                    }
                }
                ?>
            </select>
        </div>

        <input type="submit" value="Create Book">
    </form>

</body>



</html>
<?php
// Close the database connection at the end of the file
$conn->close();
?>