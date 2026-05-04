<?php
session_start();
session_unset();
session_destroy();

// Redirect to public homepage
header("Location: destiny.html");
exit();
?>
