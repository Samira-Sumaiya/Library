<?php
include "db.php";

$user_id = $_POST['user_id'];
$book_id = $_POST['book_id'];

$borrow_date = date("Y-m-d");
$due_date = date("Y-m-d", strtotime("+10 days"));

$sql = "INSERT INTO borrow_records (user_id, book_id, borrow_date, due_date) 
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $user_id, $book_id, $borrow_date, $due_date);

if ($stmt->execute()) {
    // decrease available copies
    $conn->query("UPDATE books SET available_copies = available_copies - 1 WHERE book_id = $book_id");

    echo "borrow_success";
} else {
    echo "borrow_failed";
}
?>
