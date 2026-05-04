<?php
include "../db.php";

$borrow_id = $_POST['borrow_id'];

// Get borrow record to know which book
$stmt = $conn->prepare("SELECT * FROM borrow_records WHERE borrow_id = ?");
$stmt->bind_param("i", $borrow_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 1){
    $borrow = $result->fetch_assoc();
    $book_id = $borrow['book_id'];

    // Update borrow record
    $stmt2 = $conn->prepare("UPDATE borrow_records SET return_date = NOW(), status = 'returned' WHERE borrow_id = ?");
    $stmt2->bind_param("i", $borrow_id);

    if($stmt2->execute()){
        // Increase available copies
        $conn->query("UPDATE books SET available_copies = available_copies + 1 WHERE book_id = $book_id");
        echo "return_success";
    } else {
        echo "return_failed";
    }
} else {
    echo "borrow_not_found";
}
?>
