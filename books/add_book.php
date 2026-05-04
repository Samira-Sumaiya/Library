<?php
include "db.php";

$title = $_POST['title'];
$author = $_POST['author'];
$category = $_POST['category_id'];
$total = $_POST['total_copies'];

$sql = "INSERT INTO books (title, author, category_id, total_copies, available_copies)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssiii", $title, $author, $category, $total, $total);

if ($stmt->execute()) {
    echo "book_added";
} else {
    echo "add_failed";
}
?>
