<?php
$password = "admin1234"; // replace with the password you want
$hashed = password_hash($password, PASSWORD_DEFAULT);
echo $hashed;
?>
