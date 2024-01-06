<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title>Update Book</title>
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
<?php
include '../db.php';

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
        // Include the update form
        ?>

    <div class="wrap">
        <div class="submitBox">
            <h2 class="header">Edit Book</h2>
        <form action="book/update.php" method="POST">
            <input type="hidden" name="bookID" value="<?php echo $bookDetails['book_id']; ?>">

            <div>
                <label for="title">Title:</label>
                <input type="text" name="title" value="<?php echo $bookDetails['book_title']; ?>" required>
            </div>
            <div>
                <label for="edition">Edition:</label>
                <input type="text" name="edition" value="<?php echo $bookDetails['book_edition']; ?>" required>
            </div>

            <div>
                <label for="publisher">Publisher:</label>
                <input type="text" name="publisher" value="<?php echo $bookDetails['book_publisher']; ?>" required>
            </div>
            <div>
                <label for="year">Year:</label>
                <input type="number" name="year" value="<?php echo $bookDetails['book_year']; ?>" required>
            </div>
            <div>
                <label for="authorIDs">Authors:</label>
                <select name="authorIDs[]" multiple required>
                    <?php
                    $listOfAuthor = getListAuthor($conn);
                    if ($listOfAuthor->num_rows > 0) {
                        while ($author = $listOfAuthor->fetch_assoc()) {
                            $selected = in_array($author['au_id'], explode(",", $bookDetails['author_ids'])) ? 'selected' : '';
                            echo "<option value='{$author['au_id']}' $selected>{$author['au_name']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="categoryIDs">Categories:</label>
                <select name="categoryIDs[]" multiple required>
                    <?php
                    $listOfCategories = getListCategory($conn);
                    if ($listOfCategories->num_rows > 0) {
                        while ($category = $listOfCategories->fetch_assoc()) {
                            $selected = in_array($category['cat_id'], explode(",", $bookDetails['category_ids'])) ? 'selected' : '';
                            echo "<option value='{$category['cat_id']}' $selected>{$category['cat_name']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="storageID">Storage:</label>
                <select name="storageID" required>
                    <?php
                    $listOfStorage = getListStorage($conn);
                    if ($listOfStorage->num_rows > 0) {
                        while ($storage = $listOfStorage->fetch_assoc()) {
                            $selected = ($storage['sto_id'] == $bookDetails['sto_id']) ? 'selected' : '';
                            echo "<option value='{$storage['sto_id']}' $selected>{$storage['sto_shelf']}</option>";
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
