<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../db.php';

    $deletedId = $_POST["id"];
    
    $sql = "DELETE from book WHERE book_id = $deletedId;";

    // Execute the SQL statement
    $conn->query($sql);

    // Close connection
    $conn->close();

    echo "Book created successfully!";
    // Redirect
    echo '<script>window.location.href = "../book.php";</script>';
}
?>