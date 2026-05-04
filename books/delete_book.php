<?php
include "../db.php";

$book_id = $_POST['book_id'];

$stmt = $conn->prepare("DELETE FROM books WHERE book_id = ?");
$stmt->bind_param("i", $book_id);

if($stmt->execute()){
    echo "delete_success";
} else {
    echo "delete_failed";
}
?>
