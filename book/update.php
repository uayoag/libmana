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
include '../db.php';

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
        // Include the update form
        ?>

    <div class="wrap">
        <div class="submitBox">
        <h2 class="header">Edit Book</h2>
<form action="book/update.php" method="POST">
    <input type="hidden" name="bookID" value="<?php echo htmlspecialchars($bookDetails['book_id']); ?>">

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
        <select type="text" name="authorIDs" value="<?php echo htmlspecialchars($bookDetails['authors']); ?>" required>

        </select>
    </div>
    <div>
        <label for="categoryIDs">Categories:</label>
        <select type="text" name="categoryIDs[]" value="<?php echo htmlspecialchars($bookDetails['categories']); ?>" required>

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
</form>
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
