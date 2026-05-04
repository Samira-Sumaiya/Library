<?php
session_start();
include '../db.php'; // adjust the path if needed

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $password   = $_POST['password'] ?? '';
    $role       = $_POST['role'] ?? 'student';

    if (!$first_name || !$last_name || !$email || !$password) {
        $errors[] = "All fields are required.";
    }

    // Check email uniqueness
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = "Email already registered.";
    }
    $stmt->close();

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (first_name,last_name,email,password_hash,role,status,created_at) VALUES (?,?,?,?,?,'active',NOW())");
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $password_hash, $role);
        if ($stmt->execute()) {
            $success = "Registration successful! You can now <a href='login.php'>login</a>.";
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Function for safe profile photo (default if not logged in)
$user_photo = $_SESSION['user_photo'] ?? 'default_photo.png';
function user_photo_path($fname){
    $f = '../uploads/' . $fname;
    return file_exists($f) ? $f : '../uploads/default_photo.png';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - PCIU Library</title>
<link rel="stylesheet" href="../destiny.css">
<link rel="stylesheet" href="../profile.css">
<style>
.register-container {
    max-width: 500px; 
    margin: 40px auto; 
    padding: 20px; 
    background: #f4f4f4; 
    border-radius: 10px;
}
.register-container h2 {
    color: #005a9c; 
    text-align: center; 
    margin-bottom: 20px;
}
.register-container label {
    display: block; 
    margin-top: 10px; 
    font-weight: bold;
}
.register-container input[type=text],
.register-container input[type=email],
.register-container input[type=password],
.register-container select {
    width: 100%; 
    padding: 8px; 
    margin-top: 5px; 
    border-radius: 5px; 
    border: 1px solid #ccc;
}
.register-container button {
    margin-top: 20px; 
    padding: 10px 20px; 
    background: #005a9c; 
    color: #fff; 
    border: none; 
    border-radius: 5px; 
    cursor: pointer;
}
.register-container button:hover {
    background: #0072bb;
}
.success-msg { color: green; margin-top: 10px; }
.error-msg { color: red; margin-top: 10px; }
</style>
</head>
<body>

<!-- Header -->
<header class="header">
  <div class="top-bar">
    <div class="logo-title">
      <a href="../destiny.html"><img src="../bg.png" alt="PCIU Logo" class="logo"></a>
      <div class="title-text">
        <h1>Ratnagarva Begum Ashrafunnesa Library</h1>
        <p>Port City International University</p>
      </div>
    </div>
    <div class="login-search-section">
      <!-- Profile Icon Dropdown -->
      <div class="profile-container">
          <img src="<?php echo htmlspecialchars(user_photo_path($user_photo)); ?>" class="profile-icon" alt="Profile">
          <div class="profile-dropdown">
              <div class="user-info">
                  <img src="<?php echo htmlspecialchars(user_photo_path($user_photo)); ?>" class="user-photo">
                  <p class="user-name">Guest</p>
                  <p class="user-email">Not logged in</p>
              </div>
              <hr class="profile-divider">
              <a href="../auth/login.php">Login</a>
              <a href="../auth/register.php">Register</a>
          </div>
      </div>
    </div>
  </div>
</header>

<main class="library-page">
<div class="register-container">
    <h2>Register</h2>

    <?php 
    foreach($errors as $err) echo '<p class="error-msg">'.$err.'</p>'; 
    if($success) echo '<p class="success-msg">'.$success.'</p>'; 
    ?>

    <form method="POST">
        <label>First Name</label>
        <input type="text" name="first_name" required>

        <label>Last Name</label>
        <input type="text" name="last_name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Role</label>
        <select name="role" required>
            <option value="student" selected>Student</option>
            <option value="staff">Staff</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit">Register</button>
    </form>
</div>
</main>

<script>
// Profile dropdown toggle
const profileContainer = document.querySelector('.profile-container');
if(profileContainer){
    const profileDropdown = profileContainer.querySelector('.profile-dropdown');
    const profileIcon = profileContainer.querySelector('.profile-icon');
    profileIcon.addEventListener('click', e => {
        e.stopPropagation();
        profileContainer.classList.toggle('active');
        profileDropdown.setAttribute('aria-hidden', !profileContainer.classList.contains('active'));
    });
    window.addEventListener('click', e => {
        if(!profileContainer.contains(e.target)){
            profileContainer.classList.remove('active');
            profileDropdown.setAttribute('aria-hidden', 'true');
        }
    });
}
</script>

</body>
</html>
