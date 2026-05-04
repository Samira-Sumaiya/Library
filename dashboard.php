<?php
session_start();
include 'db.php'; // Make sure path is correct

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login_prime.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];
$user_role = $_SESSION['user_role'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Library</title>
<link rel="stylesheet" href="dashboard.css"> <!-- Create your CSS separately -->
</head>
<body>

<header>
    <h1>Welcome, <?php echo htmlspecialchars($user_email); ?></h1>
    <p>Role: <?php echo htmlspecialchars($user_role); ?></p>
    <a href="auth/logout.php">Logout</a>
</header>

<main>
    <h2>Your Borrowed Books</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Book Title</th>
            <th>Author</th>
            <th>Borrow Date</th>
            <th>Return Date</th>
            <th>Status</th>
        </tr>

<?php
// Fetch borrowed books for this user
$stmt = $conn->prepare("
    SELECT b.title, b.author, br.borrow_date, br.return_date, br.status
    FROM books b
    JOIN borrow_status br ON b.book_id = br.book_id
    WHERE br.user_id = ?
    ORDER BY br.borrow_date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        echo "<tr>";
        echo "<td>".htmlspecialchars($row['title'])."</td>";
        echo "<td>".htmlspecialchars($row['author'])."</td>";
        echo "<td>".htmlspecialchars($row['borrow_date'])."</td>";
        echo "<td>".htmlspecialchars($row['return_date'])."</td>";
        echo "<td>".htmlspecialchars($row['status'])."</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No borrowed books found.</td></tr>";
}

$stmt->close();
$conn->close();
?>
    </table>
</main>

</body>
</html>
