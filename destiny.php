<?php
session_start();
include 'db.php'; // your DB connection

// Redirect to public homepage if not logged in
if(!isset($_SESSION['user_name'])) {
    header("Location: destiny.html");
    exit();
}

// Session data
$user_name  = $_SESSION['user_name'] ?? '';
$user_email = $_SESSION['user_email'] ?? '';
$user_photo = $_SESSION['user_photo'] ?? 'default_photo.png';

// Safe user photo function
function user_photo_path($fname) {
    $f = 'uploads/' . $fname;
    return file_exists($f) ? $f : 'uploads/default_photo.png';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PCIU Central Library - Home</title>
<link rel="stylesheet" href="destiny.css">
<link rel="stylesheet" href="profile.css">
</head>
<body>

<!-- Loader -->
<div class="loader-screen" id="loaderScreen">
    <div class="loader">
        <img src="pciu.png" alt="PCIU Logo">
    </div>
</div>

<!-- Header -->
<header class="header">
  <div class="top-bar">
    <div class="logo-title">
      <img src="bg.png" alt="PCIU Logo" class="logo">
      <div class="title-text">
        <h1>Ratnagarva Begum Ashrafunnesa Library</h1>
        <p>Port City International University</p>
      </div>
    </div>
    <div class="login-search-section">
      <form class="search-bar" onsubmit="return false;">
          <input type="text" placeholder="Search by books..." id="searchInput">
          <button type="button" id="searchBtn">🔍</button>
      </form>

      <!-- Profile Icon Dropdown -->
      <div class="profile-container" id="profileContainer">
          <img src="<?php echo htmlspecialchars(user_photo_path($user_photo)); ?>" class="profile-icon" alt="Profile">
          <div class="profile-dropdown" id="profileDropdown" aria-hidden="true">
              <div class="user-info">
                  <img src="<?php echo htmlspecialchars(user_photo_path($user_photo)); ?>" class="user-photo" alt="User photo">
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

<!-- Navigation Bar -->
<div class="navbar">
    <ul>
        <li><a href="destiny.php">Home</a></li>
        <li class="dropdown">
            <a href="#">Books</a>
            <ul class="dropdown-menu">
                <li><a href="test1.php">Textbooks</a></li>
                <li><a href="ebook.html">E-Books</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#">Download</a>
            <ul class="dropdown-menu">
                <li><a href="https://drive.google.com/file/d/1uJgGPKmt10RgI21_sARMFu-WWm19jaKS/view?usp=drivesdk">Forms</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#">About</a>
            <ul class="dropdown-menu">
                <li><a href="services.html">Library Hours & Services</a></li>
                <li><a href="rule.html">Library Rules & Regulations</a></li>
            </ul>
        </li>
    </ul>
</div>

<!-- Hero Slider -->
<div class="hero-slider" id="heroSlider">
    <div class="hero-slide"><img src="l3.jpg" alt="Library Image 1"></div>
    <div class="hero-slide"><img src="l2.jpg" alt="Library Image 2"></div>
    <div class="hero-slide"><img src="l1.jpg" alt="Library Image 3"></div>
</div>

<!-- Main Content -->
<main class="library-page">

    <!-- New Arrivals -->
    <section class="new-arrivals">
        <h2>New Arrivals</h2>
         <div class="book-cards">
            <div class="book-card">
                <div class="book-card-inner">
                    <div class="book-front">
                        <img src="rselvam.jpg" alt="Book 1">
                        <h3>Software Engineering</h3>
                        <p>Author: Dr. R. Selvam</p>
                    </div>
                    <div class="book-back">
                        <h3>Software Engineering</h3>
                        <p>Explore modern software development concepts.</p>
                    </div>
                </div>
            </div>
            <div class="book-card">
                <div class="book-card-inner">
                    <div class="book-front">
                        <img src="aemhkd.jpg" alt="Book 2">
                        <h3>Advanced Engineering Mathematics</h3>
                        <p>Author: H. K. Dass</p>
                    </div>
                    <div class="book-back">
                        <h3>Advanced Engineering Mathematics</h3>
                        <p>Essential topics for engineering students.</p>
                    </div>
                </div>
            </div>
            <div class="book-card">
                <div class="book-card-inner">
                    <div class="book-front">
                        <img src="absarsir.jpg" alt="Book 3">
                        <h3>Intel Microprocessor</h3>
                        <p>Author: Albert Newton</p>
                    </div>
                    <div class="book-back">
                        <h3>Intel Microprocessor</h3>
                        <p>Learn architecture and programming basics.</p>
                    </div>
                </div>
            </div>
            <div class="book-card">
                <div class="book-card-inner">
                    <div class="book-front">
                        <img src="cf.jpg" alt="Book 4">
                        <h3>Computer Fundamentals</h3>
                        <p>Author: Pradeep K. Sinha, Preti Sinha</p>
                    </div>
                    <div class="book-back">
                        <h3>Computer Fundamentals</h3>
                        <p>Know about fundamentals of Computer.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Books -->
    <section class="upcoming-books">
        <h2>Upcoming Books</h2>
        <div class="book-cards">
            <div class="book-card">
                <div class="book-card-inner">
                    <div class="book-front">
                        <img src="hack.jpg" alt="Upcoming Book 1">
                        <h3>Hacking</h3>
                        <p>Author: John Erickson</p>
                    </div>
                    <div class="book-back">
                        <h3>Hacking</h3>
                        <p>The Art Of Exploitation</p>
                    </div>
                </div>
            </div>
            <div class="book-card">
                <div class="book-card-inner">
                    <div class="book-front">
                        <img src="tpw.jpg" alt="Upcoming Book 2">
                        <h3>The Python Workbook</h3>
                        <p>Author: Ben Stephenson</p>
                    </div>
                    <div class="book-back">
                        <h3>The Python Workbook</h3>
                        <p>Practice Python exercises efficiently.</p>
                    </div>
                </div>
            </div>
            <div class="book-card">
                <div class="book-card-inner">
                    <div class="book-front">
                        <img src="piansic.jpg" alt="Upcoming Book 3">
                        <h3>Programming In ANSI C</h3>
                        <p>Author: E. Balaguruswamy</p>
                    </div>
                    <div class="book-back">
                        <h3>Programming In ANSI C</h3>
                        <p>Step-by-step learning for beginners.</p>
                    </div>
                </div>
            </div>
            <div class="book-card">
                <div class="book-card-inner">
                    <div class="book-front">
                        <img src="ast.jpg" alt="Upcoming Book 4">
                        <h3>Computer Networks</h3>
                        <p>Author: Andrew S. Tanenbaum</p>
                    </div>
                    <div class="book-back">
                        <h3>Computer Networks</h3>
                        <p>Networking concepts and protocols explained.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br>

    <!-- Library Staff -->
    <section class="library-intro">
        <h1>Library Staff Members</h1>
        <p>Meet our dedicated staff members.</p>
    </section>
    <section class="library-staff">
        <div class="staff-cards">
             <div class="staff-card">
                <img src="deputy.jpg" alt="Frhana Noor Chowdhury">
                <h3>Ms. Frhana Noor Chowdhury</h3>
                <p>Deputy Librarian</p>
            </div>
            <div class="staff-card">
                <img src="e1.jpg" alt="Tabassum Ahmed">
                <h3>Tabassum Ahmed</h3>
                <p>Executive</p>
            </div>
            <div class="staff-card">
                <img src="e2.jpg" alt="Nure Alam">
                <h3>Nure Alam</h3>
                <p>Executive</p>
            </div>
            <div class="staff-card">
                <img src="oa.jpg" alt="Rasel Mandi">
                <h3>Rasel Mandi</h3>
                <p>Office Assistant</p>
            </div>
            <div class="staff-card">
                <img src="at.jpg" alt="Al Amin Paik">
                <h3>Al Amin Paik</h3>
                <p>Attendant</p>
            </div>
            <div class="staff-card">
                <img src="oa2.jpg" alt="Mohabbat Ali">
                <h3>Mohabbat Ali</h3>
                <p>Office Assistant</p>
            </div>
        </div>
    </section>

    <!-- Contact Info -->
    <section class="library-contact">
        <h2>Contact Information</h2>
        <p>Email: <a href="mailto:admission@portcity.edu.bd">admission@portcity.edu.bd</a></p>
        <p>Phone: +880 23333 69877 / +880 23333 69899</p>
        <p>Mobile: +880 18511 20791 / +880 17732 25500 / +880 17732 25511</p>
        <p>Address: Administrative Building (Ground Floor)<br>7-14, Nikunja Housing Society,<br>South Khulshi, Chattogram</p>
    </section>

</main>

<!-- Footer with Map -->
<footer>
  <div class="footer-container">
    <div class="footer-info">
      <div class="footer-column">
        <h3>QUICK LINKS</h3>
        <ul>
          <li><a href="#">Press Release</a></li>
          <li><a href="#">ICSDTIR-2021</a></li>
          <li><a href="#">IQAC SURVEY</a></li>
          <li><a href="#">Student Portal</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h3>CONNECT</h3>
        <p>01851120791<br>01773225500<br>01773225511</p>
        <p><a href="mailto:admission@portcity.edu.bd">admission@portcity.edu.bd</a></p>
      </div>
      <div class="footer-column">
        <h3>LOCATION</h3>
        <p>Administrative Building (Ground Floor)<br>7-14, Nikunja Housing Society,<br>South Khulshi, Chattogram</p>
      </div>
    </div>
    <div class="footer-map">
      <iframe src="https://www.google.com/maps?q=Port+City+International+University&output=embed" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 Port City International University. All Rights Reserved. Design, Development and Maintenance by <span class="highlight">ICT Cell</span></p>
  </div>
</footer>

<script>
// Loader
window.addEventListener("load", () => {
    setTimeout(() => { document.getElementById("loaderScreen").style.display = "none"; }, 2000);
});

// Profile dropdown toggle
const profileContainer = document.getElementById('profileContainer');
if(profileContainer){
    const profileDropdown = document.getElementById('profileDropdown');
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

// Search toggle
const searchBtn = document.getElementById('searchBtn');
const searchInput = document.getElementById('searchInput');
searchBtn.addEventListener('click', e=>{
    e.stopPropagation();
    searchInput.classList.toggle('active');
    searchBtn.textContent = searchInput.classList.contains('active') ? '✖' : '🔍';
    if(searchInput.classList.contains('active')) searchInput.focus();
});
document.addEventListener('click', e=>{
    if(!searchInput.contains(e.target) && !searchBtn.contains(e.target)){
        searchInput.classList.remove('active');
        searchBtn.textContent = '🔍';
    }
});

// Hero slider
(function(){
  const slides = document.querySelectorAll('.hero-slide');
  if(slides.length<2) return;
  let idx=0;
  slides.forEach((s,i)=> s.style.opacity = i===0?'1':'0');
  setInterval(()=>{
      slides[idx].style.transition='opacity 1s';
      slides[idx].style.opacity='0';
      idx=(idx+1)%slides.length;
      slides[idx].style.transition='opacity 1s';
      slides[idx].style.opacity='1';
  },4500);
})();

// Animated placeholder
const placeholderText = "Search by books...";
let index = 0;
function animatePlaceholder() {
    searchInput.setAttribute("placeholder", placeholderText.slice(0,index));
    index++;
    if(index>placeholderText.length) index=0;
    setTimeout(animatePlaceholder,150);
}
animatePlaceholder();
</script>

</body>
</html>
