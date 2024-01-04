<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title>Author Page</title>
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

    <h2 class="header">Author Information</h2>
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

    function getListAuthor($conn)
    {
        $sql = "SELECT book.book_title, author.au_id, author.au_name, author.au_address, author.au_gender, author.au_nationality
        FROM book
        JOIN book_author ON book.book_id = book_author.book_id
        JOIN author ON book_author.au_id = author.au_id";

        if (isset($_GET['title'])) {
            $searchAuthor = $_GET['title'];
            $sql .= " WHERE author.au_name LIKE '%$searchAuthor%'
                        OR author.au_address LIKE '%$searchAuthor%'
                        OR author.au_gender LIKE '%$searchAuthor%'
                        OR author.au_nationality LIKE '%$searchAuthor%'
                        OR book.book_title LIKE '%$searchAuthor%'";
        }

        $result = $conn->query($sql);
        return $result;
    }



    $listOfAuthor = getListAuthor($conn);

    if ($listOfAuthor->num_rows > 0) {
        // header
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Address</th><th>Gender</th><th>Nationality</th></tr>";
        // rows
        while ($row = $listOfAuthor->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["au_id"] . "</td>";
            echo "<td>" . $row["au_name"] . "</td>";
            echo "<td>" . $row["au_address"] . "</td>";
            echo "<td>" . $row["au_gender"] . "</td>";
            echo "<td>" . $row["au_nationality"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No authors found.";
    }
    ?>

    <h2>Create New Author</h2>

    <form action="author/create.php" method="post">
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label for="address">Address:</label>
            <input type="text" name="address" required>
        </div>
        <div>
            <label for="gender">Gender:</label>
            <select name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>
        <div>
            <label for="nationality">Nationality:</label>
            <input type="text" name="nationality" required>
        </div>
        <input type="submit" value="Create Author">
    </form>

</body>

</html>
<?php
$conn->close();
?>