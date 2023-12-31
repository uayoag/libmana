<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Get form data
    $title = $_POST["title"];
    $edition = $_POST["edition"];
    $publisher = $_POST["publisher"];
    $year = $_POST["year"];
    $authorID = $_POST["authorID"];
    $storageID = $_POST["storageID"];
    $categoryID = $_POST["categoryID"];

    // Call the stored procedure
    $sql = "CALL CreateNewBook('$title', '$edition', '$publisher', '$year', $authorID, $storageID, $categoryID)";
    $conn->query($sql);

    // Close connection
    $conn->close();

    echo "Book created successfully!";
    // echo '<script>window.location.href = "../book.php";</script>';
}
if ($_SERVER["RE"] == "POST") 
?>
