<?php
include "../db.php";

$admin_id = $_POST['admin_id'];
$action = $_POST['action'];
$target_user_id = $_POST['target_user_id'] ?? NULL;

$stmt = $conn->prepare("INSERT INTO admin_logs (admin_id, action, target_user_id) VALUES (?, ?, ?)");
$stmt->bind_param("isi", $admin_id, $action, $target_user_id);

if($stmt->execute()){
    echo "log_success";
} else {
    echo "log_failed";
}
?>
