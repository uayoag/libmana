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
    // Replace these with your actual database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to retrieve book information with joins
    $sql = "SELECT b.id, s.shelf AS storage, a.name AS author, c.name AS category, b.edition, b.publisher, b.year, b.title 
            FROM book b
            LEFT JOIN book_author ba ON b.id = ba.book_id
            LEFT JOIN author a ON ba.author_id = a.id
            LEFT JOIN storage s ON b.storage_id = s.id
            LEFT JOIN book_cate bc ON b.id = bc.book_id
            LEFT JOIN category c ON bc.category_id = c.id
            GROUP BY b.id";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output table header
        echo "<table>";
        echo "<tr><th>ID</th><th>Storage</th><th>Author</th><th>Category</th><th>Edition</th><th>Publisher</th><th>Year</th><th>Title</th></tr>";

        // Output data from each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["storage"] . "</td>";
            echo "<td>" . $row["author"] . "</td>";
            echo "<td>" . $row["category"] . "</td>";
            echo "<td>" . $row["edition"] . "</td>";
            echo "<td>" . $row["publisher"] . "</td>";
            echo "<td>" . $row["year"] . "</td>";
            echo "<td>" . $row["title"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No books found.";
    }

    // Close connection
    $conn->close();
    ?>

<h2>Create New Book</h2>

<form action="book/create.php" method="post">
    <label for="title">Title:</label>
    <input type="text" name="title" required>

    <label for="edition">Edition:</label>
    <input type="text" name="edition" required>

    <label for="publisher">Publisher:</label>
    <input type="text" name="publisher" required>

    <label for="year">Year:</label>
    <input type="text" name="year" required>

    <label for="authorID">Author ID:</label>
    <input type="text" name="authorID" required>

    <label for="storageID">Storage ID:</label>
    <input type="text" name="storageID" required>

    <label for="categoryID">Category ID:</label>
    <input type="text" name="categoryID" required>

    <input type="submit" value="Create Book">
</form>

</body>

</html>