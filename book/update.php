<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../style.css">
    <title>Update Book</title>
</head>

<body style="background: #e6a681;padding: 40px">
    <div id="menu">
        <ul>
            <li>
                <a href="../index.html">Home</a>
            </li>
            <li>
                <a href="../book.php">Book</a>
            </li>
            <li>
                <a href="../author.php">Author</a>
            </li>
            <li>
                <a href="../category.php">Category</a>
            </li>
            <li>
                <a href="../storage.php">Storage</a>
            </li>
        </ul>
    </div>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../db.php';

    // Get form data
    $bookID = $_POST["book_id"];
    $title = $_POST["title"];
    $edition = $_POST["edition"];
    $publisher = $_POST["publisher"];
    $year = $_POST["year"];
    $authors = $_POST["authorIDs"];
    $storageID = $_POST["storageID"];
    $categories = $_POST["categoryIDs"];

    // Update the book table
    $sql = "UPDATE book 
            SET sto_id = $storageID, 
                book_edition = '$edition', 
                book_publisher = '$publisher', 
                book_year = '$year', 
                book_title = '$title' 
            WHERE book_id = $bookID;";

    // Delete existing author and category associations
    $sql .= "DELETE FROM book_author WHERE book_id = $bookID;";
    $sql .= "DELETE FROM book_cate WHERE book_id = $bookID;";

    // Insert into book_author for each author
    foreach ($authors as $authorID) {
        $sql .= "INSERT INTO book_author (au_id, book_id) VALUES ($authorID, $bookID);";
    }

    // Insert into book_cate for each category (assuming similar structure)
    foreach ($categories as $categoryID) {
        $sql .= "INSERT INTO book_cate (cat_id, book_id) VALUES ($categoryID, $bookID);";
    }

    // Execute the SQL statement
    $conn->multi_query($sql);

    // Close connection
    $conn->close();

    echo "Book updated successfully!";
    // Redirect
    echo '<script>window.location.href = "../book.php";</script>';
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
// Check if book_id is set in the URL
if (isset($_GET['book_id'])) {
    $bookID = $_GET['book_id'];

    // Function to get book details
    function getBookDetails($conn, $bookID)
    {
        $sql = "SELECT * FROM book WHERE book_id = $bookID";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
        return null;
    }
    // Get book details
    $bookDetails = getBookDetails($conn, $bookID);
    if ($bookDetails) {
        ?>
<div class="wrap">
    <div class="submitBox">
        <h2 class="header">Edit Book</h2>
        <form action="book/update.php" method="POST">
            <input type="hidden" name="bookID" value="<?php echo isset($bookDetails['book_id']) ? htmlspecialchars($bookDetails['book_id']) : ''; ?>">

    <div>
        <label for="title">Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($bookDetails['book_title']); ?>" required>
    </div>
    <div>
        <label for="edition">Edition:</label>
        <input type="text" name="edition" value="<?php echo htmlspecialchars($bookDetails['book_edition']); ?>" required>
    </div>
    <div>
        <label for="publisher">Publisher:</label>
        <input type="text" name="publisher" value="<?php echo htmlspecialchars($bookDetails['book_publisher']); ?>" required>
    </div>
    <div>
        <label for="year">Year:</label>
        <input type="number" name="year" value="<?php echo htmlspecialchars($bookDetails['book_year']); ?>" required>
    </div>
    <div>
    <label for="authorIDs">Authors:</label>
    <select multiple name="authorIDs[]" required>
        <?php
        $listOfAuthor = getListAuthor($conn);
        if ($listOfAuthor->num_rows > 0) {
            while ($author = $listOfAuthor->fetch_assoc()) {
                $selected = (in_array($author['au_id'], $bookDetails['author_ids'])) ? 'selected' : '';
                echo "<option value='{$author['au_id']}' $selected>{$author['au_name']}</option>";
            }
        }
        ?>
    </select>
</div>
    <div>
    <label for="categoryIDs">Categories:</label>
    <select multiple name="categoryIDs[]" required>
        <?php
        $listOfCategories = getListCategory($conn);
        if ($listOfCategories->num_rows > 0) {
            while ($category = $listOfCategories->fetch_assoc()) {
                $selected = (in_array($category['cat_id'], $bookDetails['category_ids'])) ? 'selected' : '';
                echo "<option value='{$category['cat_id']}' $selected>{$category['cat_name']}</option>";
            }
        }
        ?>
    </select>
</div>
    <div>
        <label for="storageID">Storage:</label>
        <select type="text" name="storageID" required>
            <?php
            $listOfStorage = getListStorage($conn);
            if ($listOfStorage->num_rows > 0) {
                while ($storage = $listOfStorage->fetch_assoc()) {
                    $selected = ($storage['sto_id'] == $bookDetails['sto_id']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($storage['sto_id']) . "' $selected>" . htmlspecialchars($storage['sto_shelf']) . "</option>";
                }
            }
            ?>
        </select>
    </div>
            <div class="chooseBox">
            <input type="submit" name="submit" value="Update Book">
            </div>
        </form>
        <?php
    } else {
        echo "Book not found.";
    }
} else {
    echo "Invalid request. Book ID is missing.";
}
?>
</body>
</html>
<?php
// Close the database connection at the end of the file
$conn->close();
?>
