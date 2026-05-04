<?php
session_start();
include 'db.php'; // DB connection

// Redirect non-admin users
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: destiny.html");
    exit();
}

// Users pagination and search
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$start = ($page - 1) * $limit;
$search = trim($_GET['search'] ?? '');
$search_sql = $search ? "WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ?" : "";

// Total users count
if($search){
    $stmt_count = $conn->prepare("SELECT COUNT(*) AS total FROM users $search_sql");
    $like = "%$search%";
    $stmt_count->bind_param("sss",$like,$like,$like);
}else{
    $stmt_count = $conn->prepare("SELECT COUNT(*) AS total FROM users");
}
$stmt_count->execute();
$total = $stmt_count->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total / $limit);

// Fetch users
if($search){
    $stmt = $conn->prepare("SELECT * FROM users $search_sql ORDER BY user_id ASC LIMIT ?,?");
    $stmt->bind_param("ssiis",$like,$like,$like,$start,$limit);
}else{
    $stmt = $conn->prepare("SELECT * FROM users ORDER BY user_id ASC LIMIT ?,?");
    $stmt->bind_param("ii",$start,$limit);
}
$stmt->execute();
$users_result = $stmt->get_result();

// Fetch all books
$books_result = $conn->query("SELECT * FROM books ORDER BY book_id ASC");

// Session info
$admin_name = $_SESSION['user_name'] ?? 'Admin';
$admin_email = $_SESSION['user_email'] ?? '';
$admin_photo = $_SESSION['user_photo'] ?? 'default_photo.png';
function user_photo_path($fname){
    $f = 'uploads/'.$fname;
    return file_exists($f) ? $f : 'uploads/default_photo.png';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - PCIU Library</title>
<link rel="stylesheet" href="destiny.css">
<link rel="stylesheet" href="profile.css">
<style>
body{font-family:Arial,sans-serif;background:#f4f4f4;margin:0;}
.container{max-width:1200px;margin:20px auto;padding:0 15px;}
h1,h2{color:#005a9c;}
section{margin-bottom:40px;}
.table-container{overflow-x:auto;background:#fff;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,0.1);padding:10px;}
table{width:100%;border-collapse: collapse;}
th, td{padding:12px 15px;text-align:left;border-bottom:1px solid #ddd;}
th{background:#005a9c;color:#fff;}
tr:hover{background:#f1f1f1;}
img.user-photo,img.book-cover{width:50px;height:50px;border-radius:5px;object-fit:cover;}
.btn{padding:6px 12px;border:none;border-radius:5px;cursor:pointer;text-decoration:none;color:#fff;margin-right:5px;font-size:14px;}
.btn-edit{background:#0072bb;}
.btn-delete{background:#c0392b;}
.btn-add{background:#27ae60;margin-bottom:10px;display:inline-block;}
.pagination{margin-top:15px;text-align:center;}
.pagination a{padding:5px 10px;margin:0 3px;text-decoration:none;border:1px solid #ccc;border-radius:5px;color:#005a9c;}
.pagination a.active{background:#005a9c;color:#fff;border:1px solid #005a9c;}
.search-form{text-align:right;margin-bottom:10px;}
.search-form input[type=text]{padding:6px;width:200px;border-radius:5px;border:1px solid #ccc;}
.search-form button{padding:6px 10px;border:none;background:#005a9c;color:#fff;border-radius:5px;cursor:pointer;}
.book-cards{display:flex;flex-wrap:wrap;gap:15px;}
.book-card{background:#fff;padding:10px;border-radius:10px;width:calc(25% - 15px);box-shadow:0 2px 6px rgba(0,0,0,0.1);}
.book-card img{width:100%;height:180px;object-fit:cover;border-radius:5px;}
.book-card h3{margin:10px 0 5px;font-size:16px;color:#005a9c;}
.book-card p{margin:0;font-size:14px;}
.book-card a{display:inline-block;margin-top:5px;}
@media screen and (max-width:992px){
    .book-card{width:calc(50% - 15px);}
}
@media screen and (max-width:600px){
    .book-card{width:100%;}
    .search-form{text-align:center;}
    .search-form input[type=text]{width:80%;}
}
</style>
</head>
<body>

<!-- Header -->
<header class="header">
  <div class="top-bar">
    <div class="logo-title">
      <a href="index.php"><img src="bg.png" alt="PCIU Logo" class="logo"></a>
      <div class="title-text">
        <h1>Ratnagarva Begum Ashrafunnesa Library</h1>
        <p>Port City International University</p>
      </div>
    </div>
    <div class="login-search-section">
      <div class="profile-container">
          <img src="<?php echo htmlspecialchars(user_photo_path($admin_photo)); ?>" class="profile-icon" alt="Profile">
          <div class="profile-dropdown">
              <div class="user-info">
                  <img src="<?php echo htmlspecialchars(user_photo_path($admin_photo)); ?>" class="user-photo">
                  <p class="user-name"><?php echo htmlspecialchars($admin_name); ?></p>
                  <p class="user-email"><?php echo htmlspecialchars($admin_email); ?></p>
              </div>
              <hr class="profile-divider">
              <a href="edit_profile.php">Edit Profile</a>
              <a href="logout.php">Logout</a>
          </div>
      </div>
    </div>
  </div>
</header>

<div class="container">

<!-- Users Section -->
<section>
<h2>Manage Users</h2>
<a href="add_user.php" class="btn btn-add">Add New User</a>

<form class="search-form" method="GET">
    <input type="text" name="search" placeholder="Search by name/email..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<div class="table-container">
<table>
<tr>
  <th>ID</th>
  <th>Photo</th>
  <th>Name</th>
  <th>Email</th>
  <th>Role</th>
  <th>Status</th>
  <th>Actions</th>
</tr>

<?php while($user = $users_result->fetch_assoc()): ?>
<tr>
  <td><?php echo $user['user_id']; ?></td>
  <td><img src="uploads/<?php echo $user['photo'] ?: 'default_photo.png'; ?>" class="user-photo"></td>
  <td><?php echo htmlspecialchars($user['first_name'].' '.$user['last_name']); ?></td>
  <td><?php echo htmlspecialchars($user['email']); ?></td>
  <td><?php echo $user['role']; ?></td>
  <td><?php echo $user['status']; ?></td>
  <td>
    <a href="edit_user.php?user_id=<?php echo $user['user_id']; ?>" class="btn btn-edit">Edit</a>
    <a href="delete_user.php?user_id=<?php echo $user['user_id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure?');">Delete</a>
  </td>
</tr>
<?php endwhile; ?>
</table>
</div>

<div class="pagination">
<?php
for($i=1;$i<=$total_pages;$i++){
    $active = $i==$page ? 'active' : '';
    $url = "?page=$i";
    if($search) $url .= "&search=".urlencode($search);
    echo "<a class='$active' href='$url'>$i</a>";
}
?>
</div>
</section>

<!-- Books Section -->
<section>
<h2>Library Books</h2>
<div class="book-cards">
<?php while($book = $books_result->fetch_assoc()): ?>
    <div class="book-card">
        <img src="<?php echo htmlspecialchars($book['cover_image'] ?: 'default_book.png'); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
        <h3><?php echo htmlspecialchars($book['title']); ?></h3>
        <p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
        <p>Available: <?php echo $book['available_copies']; ?></p>
        <a href="books/get_book_details.php?book_id=<?php echo $book['book_id']; ?>" class="btn btn-edit">View</a>
    </div>
<?php endwhile; ?>
</div>
</section>

</div>

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
