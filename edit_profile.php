<?php
session_start();
include 'db.php'; // your DB connection

// Redirect if not logged in
if(!isset($_SESSION['user_name'])){
    header("Location: destiny.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? '';
$user_email = $_SESSION['user_email'] ?? '';
$user_photo = $_SESSION['user_photo'] ?? 'default_photo.png';
$errors = [];
$success = '';

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    // Upload photo if provided
    if(isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK){
        $fileTmpPath = $_FILES['profile_photo']['tmp_name'];
        $fileName = $_FILES['profile_photo']['name'];
        $fileSize = $_FILES['profile_photo']['size'];
        $fileType = $_FILES['profile_photo']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedExtensions = ['jpg','jpeg','png','gif'];

        if(in_array($fileExtension, $allowedExtensions)){
            $newFileName = $user_id . '_' . time() . '.' . $fileExtension;
            $uploadFileDir = 'uploads/';
            if(!is_dir($uploadFileDir)) mkdir($uploadFileDir, 0755, true);

            $dest_path = $uploadFileDir . $newFileName;
            if(move_uploaded_file($fileTmpPath, $dest_path)){
                // Update DB column 'photo'
                $stmt = $conn->prepare("UPDATE users SET photo = ? WHERE user_id = ?");
                $stmt->bind_param("si", $newFileName, $user_id);
                if($stmt->execute()){
                    $_SESSION['user_photo'] = $newFileName;
                    $user_photo = $newFileName;
                    $success = "Profile photo updated successfully!";
                } else {
                    $errors[] = "Database update failed!";
                }
            } else {
                $errors[] = "Failed to move uploaded file.";
            }
        } else {
            $errors[] = "Only JPG, JPEG, PNG, GIF files are allowed.";
        }
    }

    // Update name or email if needed
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if(empty($errors)){
        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=? WHERE user_id=?");
        $stmt->bind_param("sssi", $first_name, $last_name, $email, $user_id);
        if($stmt->execute()){
            $_SESSION['user_name'] = $first_name . ' ' . $last_name;
            $_SESSION['user_email'] = $email;
            $user_name = $_SESSION['user_name'];
            $user_email = $email;
            $success .= "<br>Information updated successfully!";
        } else {
            $errors[] = "Failed to update information!";
        }
    }
}

// Function to safely get photo
function user_photo_path($fname){
    $f = 'uploads/' . $fname;
    return file_exists($f) ? $f : 'uploads/default_photo.png';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Profile - PCIU Library</title>
<link rel="stylesheet" href="destiny.css">
<link rel="stylesheet" href="profile.css">
<style>
/* Styles remain same as your original */
.edit-profile-container{
    max-width:500px; margin:40px auto; padding:20px; background:#f4f4f4; border-radius:10px;
}
.edit-profile-container h2{color:#005a9c; text-align:center; margin-bottom:20px;}
.edit-profile-container label{display:block; margin-top:10px; font-weight:bold;}
.edit-profile-container input[type=text], input[type=email]{width:100%; padding:8px; margin-top:5px; border-radius:5px; border:1px solid #ccc;}
.edit-profile-container input[type=file]{margin-top:5px;}
.edit-profile-container button{margin-top:20px; padding:10px 20px; background:#005a9c; color:#fff; border:none; border-radius:5px; cursor:pointer;}
.edit-profile-container button:hover{background:#0072bb;}
.success-msg{color:green; margin-top:10px;}
.error-msg{color:red; margin-top:10px;}
</style>
</head>
<body>

<header class="header">
  <div class="top-bar">
    <div class="logo-title">
      <a href="destiny.php">
    <img src="bg.png" alt="PCIU Logo" class="logo">
</a>
      <div class="title-text">
        <h1>Ratnagarva Begum Ashrafunnesa Library</h1>
        <p>Port City International University</p>
      </div>
    </div>
    <div class="login-search-section">
      <div class="profile-container">
          <img src="<?php echo htmlspecialchars(user_photo_path($user_photo)); ?>" class="profile-icon" alt="Profile">
          <div class="profile-dropdown">
              <div class="user-info">
                  <img src="<?php echo htmlspecialchars(user_photo_path($user_photo)); ?>" class="user-photo">
                  <p class="user-name"><?php echo htmlspecialchars($user_name); ?></p>
                  <p class="user-email"><?php echo htmlspecialchars($user_email); ?></p>
              </div>
              <hr class="profile-divider">
              <a href="dashboard.php">Dashboard</a>
              <a href="edit_profile.php">Edit Information</a>
              <a href="logout.php">Logout</a>
          </div>
      </div>
    </div>
  </div>
</header>

<main class="library-page">
<div class="edit-profile-container">
    <h2>Edit Profile</h2>

    <?php if(!empty($success)) echo '<p class="success-msg">'.$success.'</p>'; ?>
    <?php if(!empty($errors)) foreach($errors as $err) echo '<p class="error-msg">'.$err.'</p>'; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>First Name</label>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars(explode(' ',$user_name)[0] ?? ''); ?>" required>

        <label>Last Name</label>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars(explode(' ',$user_name)[1] ?? ''); ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required>

        <label>Profile Photo</label>
        <input type="file" name="profile_photo" accept="image/*">

        <button type="submit">Update Profile</button>
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
    });
    window.addEventListener('click', e => {
        if(!profileContainer.contains(e.target)){
            profileContainer.classList.remove('active');
        }
    });
}
</script>

</body>
</html>
