<?php
include "../db.php";

$book_id = $_GET['book_id'];

$stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 1){
    $book = $result->fetch_assoc();
    echo json_encode($book);
} else {
    echo json_encode(["error" => "Book not found"]);
}
?>
