<?php
include "../db.php";

$staff_id = $_POST['staff_id'];
$action = $_POST['action'];
$book_id = $_POST['book_id'] ?? NULL;

$stmt = $conn->prepare("INSERT INTO staff_activity_logs (staff_id, action, book_id) VALUES (?, ?, ?)");
$stmt->bind_param("isi", $staff_id, $action, $book_id);

if($stmt->execute()){
    echo "log_success";
} else {
    echo "log_failed";
}
?>
