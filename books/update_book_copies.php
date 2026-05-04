<?php
include "db.php";

$book_id = $_POST['book_id'];
$available = $_POST['available_copies'];

$sql = "UPDATE books SET available_copies = ? WHERE book_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $available, $book_id);

if ($stmt->execute()) {
    echo "update_success";
} else {
    echo "update_failed";
}
?>
