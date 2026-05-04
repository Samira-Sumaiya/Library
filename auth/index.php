<?php
session_start();
include '../db.php'; // Adjust path to your db.php

// Redirect if not logged in or not admin
if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'){
    header("Location: ../destiny.html");
    exit();
}

// Fetch all users for admin dashboard
$users = [];
$result = $conn->query("SELECT user_id, first_name, last_name, email, role, status, photo FROM users");
if($result){
    while($row = $result->fetch_assoc()){
        $users[] = $row;
    }
}

// Function for safe profile photo
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
<title>Admin Dashboard - PCIU Library</title>
<link rel="stylesheet" href="../destiny.css">
<link rel="stylesheet" href="../profile.css">
<style>
/* Admin Dashboard Table */
.admin-table {width:100%; border-collapse: collapse; margin-top:20px;}
.admin-table th, .admin-table td {border:1px solid #ccc; padding:10px; text-align:left;}
.admin-table th {background:#005a9c; color:#fff;}
.admin-table td img {width:50px; height:50px; object-fit:cover; border-radius:50%;}
.admin-table button {padding:5px 10px; margin-right:5px; background:#005a9c; color:#fff; border:none; border-radius:5px; cursor:pointer;}
.admin-table button:hover {background:#0072bb;}

/* Profile dropdown */
.profile-container {position:relative; display:inline-block;}
.profile-icon {width:50px; height:50px; border-radius:50%; cursor:pointer;}
.profile-dropdown {display:none; position:absolute; right:0; background:#fff; min-width:180px; box-shadow:0 4px 8px rgba(0,0,0,0.1); border-radius:5px; z-index:10;}
.profile-container.active .profile-dropdown {display:block;}
.profile-dropdown .user-info {padding:10px; text-align:center;}
.profile-dropdown .user-photo {width:60px; height:60px; border-radius:50%; margin-bottom:5px;}
.profile-divider {margin:5px 0; border:none; border-top:1px solid #ccc;}
.profile-dropdown a {display:block; padding:8px 12px; text-decoration:none; color:#333;}
.profile-dropdown a:hover {background:#f4f4f4;}
</style>
</head>
<body>

<!-- Header -->
<header class="header">
  <div class="top-bar">
    <div class="logo-title">
      <a href="index.php"><img src="../bg.png" alt="PCIU Logo" class="logo" style="height:60px;"></a>
      <div class="title-text">
        <h1>Ratnagarva Begum Ashrafunnesa Library</h1>
        <p>Port City International University</p>
      </div>
    </div>
    <div class="login-search-section">
      <!-- Profile dropdown for admin -->
      <div class="profile-container" id="profileContainer">
          <img src="<?php echo htmlspecialchars(user_photo_path($_SESSION['user_photo'])); ?>" class="profile-icon" alt="Profile">
          <div class="profile-dropdown" id="profileDropdown">
              <div class="user-info">
                  <img src="<?php echo htmlspecialchars(user_photo_path($_SESSION['user_photo'])); ?>" class="user-photo" alt="User photo">
                  <p class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
                  <p class="user-email"><?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
              </div>
              <hr class="profile-divider">
              <a href="index.php">Dashboard</a>
              <a href="../edit_profile.php">Edit Information</a>
              <a href="../logout.php">Logout</a>
          </div>
      </div>
    </div>
  </div>
</header>

<main class="library-page">
<h1>Admin Dashboard - Users</h1>
<table class="admin-table">
    <thead>
        <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $u): ?>
        <tr>
            <td><img src="<?php echo user_photo_path($u['photo']); ?>" alt="User Photo"></td>
            <td><?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?></td>
            <td><?php echo htmlspecialchars($u['email']); ?></td>
            <td><?php echo htmlspecialchars($u['role']); ?></td>
            <td><?php echo htmlspecialchars($u['status']); ?></td>
            <td>
                <form style="display:inline;" method="POST" action="delete_user.php" onsubmit="return confirm('Delete this user?');">
                    <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
                    <button type="submit">Delete</button>
                </form>
                <form style="display:inline;" method="GET" action="../edit_profile.php">
                    <input type="hidden" name="edit_user_id" value="<?php echo $u['user_id']; ?>">
                    <button type="submit">Edit</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</main>

<script>
// Profile dropdown toggle
const profileContainer = document.getElementById('profileContainer');
const profileDropdown = document.getElementById('profileDropdown');
profileContainer.addEventListener('click', e => {
    e.stopPropagation();
    profileContainer.classList.toggle('active');
});
window.addEventListener('click', e => {
    if(!profileContainer.contains(e.target)){
        profileContainer.classList.remove('active');
    }
});
</script>

</body>
</html>
