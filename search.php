<body style="background: #e6a681; padding: 40px">
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
    <html lang="en">
        <h2>Search Book Information</h2>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Search Example</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <form action="search.php" method="post">
            <div>
                <label for="search">Search:</label>
                <input type="text" id="search" name="query" placeholder="Enter your search term">
                <button type="submit">Search Book </button>
            </div>

        </form>
    </body>

    </html>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'db.php';
    // Get form data
    // var_dump($_POST);
    $title = $_POST["query"];
    
        // Search data
        $sql = "SELECT
                book.book_id,
                book.book_title,
                book.book_edition,
                book.book_publisher,
                book.book_year,
                author.au_name AS author_name,
                category.cat_name AS category_name,
                storage.sto_shelf,
                storage.sto_block
                FROM
                book
                JOIN book_author ON book.book_id = book_author.book_id
                JOIN author ON book_author.au_id = author.au_id
                JOIN book_cate ON book.book_id = book_cate.book_id
                JOIN category ON book_cate.cat_id = category.cat_id
                JOIN storage ON book.sto_id = storage.sto_id
                WHERE
                book.book_title = '$title';";

        // Execute the SQL statement
        $result = $conn->query($sql);

        // Check if the query was successful
        if ($result) {
            // Check if any rows were returned
            if ($result->num_rows > 0) {
                // Output table header
                echo "<table border='1'>";
                echo "<tr>
                        <th>Title</th>
                        <th>Edition</th>
                        <th>Publisher</th>
                        <th>Year</th>
                        <th>Authors</th>
                        <th>Categories</th>
                        <th>Storage</th>
                      </tr>";
        
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["book_title"] . "</td>";
                    echo "<td>" . $row["book_edition"] . "</td>";
                    echo "<td>" . $row["book_publisher"] . "</td>";
                    echo "<td>" . $row["book_year"] . "</td>";
                    // Display Authors
                    echo "<td>" . $row["author_name"] . "</td>";
                    // Display Categories
                    echo "<td>" . $row["category_name"] . "</td>";
                    // Display Storage
                    echo "<td>Shelf " . $row["sto_shelf"] . ", Block " . $row["sto_block"] . "</td>";
                    // Add additional fields as needed
                    echo "</tr>";
                }
        
                // Close the table
                echo "</table>";
            } else {
                echo "No results found.";
            }
        } else {
            // Handle the case where the query failed
            echo "Error: " . $conn->error;
        }        
        // Close the connection
        $conn->close();
    }
    ?>

</body>

</html>