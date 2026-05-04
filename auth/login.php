<?php
session_start();
include '../db.php'; // your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        die("Email and password are required.");
    }

    // Fetch user by email
    $stmt = $conn->prepare("SELECT user_id, first_name, last_name, email, password_hash, role, photo 
                            FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password_hash'])) {
            // Login successful
            $_SESSION['user_id']    = $user['user_id'];
            $_SESSION['user_name']  = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role']  = $user['role'];
            $_SESSION['user_photo'] = $user['photo'] ?? 'default_photo.png';

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: index.php"); // admin homepage
            } else {
                header("Location: destiny.php"); // student/staff homepage
            }
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No account found with that email.";
    }
}

?>
<!-- Optional: simple HTML to show login errors -->
<?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
