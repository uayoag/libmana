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
        </ul>
    </div>

    <h2 class="header">Category Information</h2>
    <form method="GET" action="">
        <div>
            <label for="search">Search:</label>
            <input type="text" id="search" name="title" placeholder="Enter your search term">
            <button type="submit">Search</button>
        </div>
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
    function getListCategory($conn)
    {
        $sql = "SELECT b.book_title, c.cat_id, c.cat_name, c.cat_keyword, c.cat_language
        FROM book b
        LEFT JOIN book_cate bc ON b.book_id = bc.book_id
        LEFT JOIN category c ON bc.cat_id = c.cat_id;";
        if (isset($_GET['title'])) {
            $searchCategory = $_GET['title'];
            $sql .= " WHERE c.cat_name LIKE '%$searchCategory%' OR c.cat_keyword LIKE '%$searchCategory%' OR c.cat_language LIKE '%$searchCategory%' OR b.book_title LIKE '%$searchCategory%'";
        }

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
