<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title>Category Page</title>
</head>

<body style="background: #e6a681; padding: 40px">
    <div id="menu">
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="book.php">Book</a></li>
            <li><a href="author.php">Author</a></li>
            <li><a href="category.php">Category</a></li>
            <li><a href="storage.php">Storage</a></li>
        </ul>
    </div>

    <h2 class="header">Category Information</h2>
    <form method="GET" action="">
        <h3>Search Information</h3>
        <div>
            <label for="search">Search:</label>
            <input type="text" id="search" name="query" placeholder="Enter your search term">
            <button type="submit">Search</button>
        </div>
        <p>Please select field to search:</p>
        <div>
            <input type="radio" id="name" name="field" value="cat_name">
            <label for="name">Name</label><br>

            <input type="radio" id="keyword" name="field" value="cat_keyword">
            <label for="keyword">Key Word</label>

            <input type="radio" id="language" name="field" value="cat_language">
            <label for="language">Language</label>
        </div>
    </form>

    <?php
    include 'db.php';

    function getListCategory($conn)
    {
        $sql = "SELECT cat_id, cat_name, cat_keyword, cat_language FROM category";
        
        if (isset($_GET['query']) && isset($_GET['field'])) {
            $searchQuery = $_GET['query'];
            $searchField = $_GET['field'];
            
            switch ($searchField) {
                case 'cat_name':
                case 'cat_keyword':
                case 'cat_language':
                    $sql .= " WHERE " . $searchField . " LIKE '%" . $searchQuery . "%'";
                    break;
                default:
                    break;
            }
        }

        $result = $conn->query($sql);
        return $result;
    }

    $listOfCategory = getListCategory($conn);

    if ($listOfCategory->num_rows > 0) {
        // Table header
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Keyword</th><th>Language</th></tr>";

        // Table rows
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

    <?php
    $conn->close();
    ?>
</body>

</html>
