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
        <h3>Search Information</h3>
        <div>
            <label for="search">Search:</label>
            <input type="text" id="search" name="query" placeholder="Enter your search term">
            <button type="submit">Search</button>
        </div>
        <p>Please select field to search:</p>
        <div>
            <input type="radio" id="name" name="field" value="name">
            <label for="name">Name</label><br>

            <input type="radio" id="address" name="field" value="cat_address">
            <label for="address">Address</label>

            <input type="radio" id="gender" name="field" value="gender">
            <label for="gender">Gender</label>

            <input type="radio" id="nationality" name="field" value="nationality">
            <label for="nationality">Nationality</label>
        </div>
    </form>
    <?php
    include 'db.php';

    function getListAuthor($conn)
    {
        $sql = "SELECT au_id, au_name, au_address, au_gender, au_nationality FROM author";

        if (isset($_GET['query']) && isset($_GET['field'])) {
            $searchQuery = $_GET['query'];
            $searchField = $_GET['field'];
            switch ($searchField) {
                case 'au_id':
                case 'au_name':
                case 'au_address':
                case 'au_gender':
                case 'au_nationality':
                    $sql .= " WHERE " . $searchField . " LIKE '%" . $searchQuery . "%'";
                    break;
                default:
                    // Handle other fields if needed
                    break;
            }
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