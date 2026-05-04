<?php
session_start();

// Restrict access: only logged-in users can see the page
$user_role = $_SESSION['user_role'] ?? '';
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PCIU Central Library - E-Books</title>
  <link rel="stylesheet" href="test1.css">
  <link rel="icon" href="logo.png"/>
  <style>
    .download-btn{
        margin-top:10px;
        padding:6px 12px;
        border:none;
        border-radius:6px;
        background:#005a9c;
        color:#fff;
        cursor:pointer;
        transition:0.3s ease;
    }
    .download-btn:hover{
        background:#0072bb;
    }

    /* Profile dropdown */
    .profile-dropdown {
        position: relative;
        display: inline-block;
        margin-left: 10px;
    }
    .profile-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
    }
    .profile-dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        background: #fff;
        min-width: 150px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        border-radius: 6px;
        overflow: hidden;
        z-index: 1000;
    }
    .profile-dropdown-content a {
        color: #333;
        padding: 10px;
        text-decoration: none;
        display: block;
        transition: 0.3s;
    }
    .profile-dropdown-content a:hover {
        background: #f4f4f4;
    }
    .profile-dropdown:hover .profile-dropdown-content {
        display: block;
    }
  </style>
</head>
<body>

<!-- Loader -->
<div class="loader-screen">
  <div class="loader"><img src="pciu.png" alt="PCIU Logo"></div>
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
        <input type="text" id="searchInput">
        <button type="button" id="searchBtn">🔍</button>
      </form>
      <?php if($is_logged_in): ?>
      <div class="profile-dropdown">
        <img src="uploads/<?php echo $_SESSION['user_photo'] ?? 'default_photo.png'; ?>" alt="Profile" class="profile-icon">
        <div class="profile-dropdown-content">
          <a href="profile.php">Profile</a>
          <a href="logout.php">Logout</a>
        </div>
      </div>
      <?php else: ?>
      <a href="login.php" class="login-btn" id="loginBtn">LOGIN</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<!-- Navbar -->
<nav class="navbar">
  <ul>
    <li><a href="destiny.php">Home</a></li>
    <li class="dropdown">
      <a href="#">Books</a>
      <ul class="dropdown-menu">
        <li><a href="test1.php">Textbooks</a></li>
        <li><a href="ebook.php">E-Books</a></li>
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
</nav>

<!-- Main -->
<main class="textbooks-page">
  <?php if(!$is_logged_in): ?>
      <section style="flex:1; text-align:center; padding:60px; font-size:1.2rem;">
        You need to login to see this page.
      </section>
  <?php else: ?>
  <aside class="sidebar">
    <h2>Departments</h2>
    <ul id="deptList"></ul>
  </aside>
  <section class="textbooks-books">
    <h2 class="page-heading">E-Books</h2>
    <div class="books-row" id="booksGrid"></div>
  </section>
  <?php endif; ?>
</main>

<!-- Footer -->
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
      <div class="footer-column footer-map">
        <h3>FIND US</h3>
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.34523412345!2d91.823456789!3d22.3356789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30ad3abcdef12345%3A0xabcdef1234567890!2sPort+City+International+University!5e0!3m2!1sen!2sbd!4v1693113600000!5m2!1sen!2sbd" 
          width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 Port City International University. All Rights Reserved. Design & Maintenance by <span class="highlight">ICT Cell</span></p>
  </div>
</footer>

<!-- JavaScript -->
<script>
// BOOK LIST WITH DRIVE LINKS SUPPORTED
const books = [
  { dept: "cse", title: "Data Mining: Concepts and Techniques", author: "Jiawei Han <br>Micheline Kamber <br>Jian Pei", pdf: "https://drive.google.com/file/d/16gBo39D0AIjGV-1ZwL6R5hSLeKs7jI9h/view?usp=sharing", img: "datamining.jpg" },
  { dept: "cse", title: "Digital Image Processing", author: "Rafael C <br>Gonzalez <br>Richard E. Woods", pdf: "https://drive.google.com/file/d/1ycAbMPy41yksyYGbxn1DDGVbr1J2l4hS/view?usp=sharing", img: "dip.jpg" },
  { dept: "cse", title: "Interactive Computer Graphics", author: "Edward Angel <br>Dave Shreiner", pdf: "https://drive.google.com/file/d/1lu8c6s0985eI05GKQah1vt7uJpychK-i/view?usp=sharing", img: "icg.jpg" },
  { dept: "cse", title: "Software Engineering", author: "Sommerville", pdf: "https://drive.google.com/file/d/1Gkt3Mf-3Im2ke8LsLGERU6N2RIrKoUDz/view?usp=sharing", img: "soften.jpg" },
  { dept: "cse", title: "The C++ Programming Language", author: "Bjarne Strousturp", pdf: "https://drive.google.com/file/d/1eyOmosZ8lWtJvV89aGmR7TPrJRWsAEJR/view?usp=sharing", img: "pl.jpg" },
  { dept: "eee", title: "Introductory Circuit Analysis", author: "Robert L. Boylestad", pdf: "https://drive.google.com/file/d/1JzNUxQspwr_uRG5OlqhtM7SuTzZq-Kp3/view?usp=sharing", img: "ca.jpg" },
  { dept: "eee", title: "Elements of Electromagnetics", author: "Matthew N. <br>O. Sadiku", pdf: "https://drive.google.com/file/d/1Z7PrnEyeRI0RI8NDDU-SSaAU0zbynvvd/view?usp=sharing", img: "ee.jpg" },
  { dept: "english", title: "Shakespheare: The Invension of the Human", author: "Harold Bloom", pdf: "https://drive.google.com/file/d/1XHAwvSUPf0N2vCor2qxMd5f9yVX6zUQH/view?usp=sharing", img: "shake.jpg" },
  { dept: "english", title: "Orientalism", author: "Edward W. Said", pdf: "https://drive.google.com/file/d/1Tf1p-XIn5eYkeuc-NzTA3gFkfLF0z7Ge/view?usp=sharing", img: "nkd.jpg" },
  { dept: "english", title: "Mrs. Dalloway", author: "Virginia Woolf", pdf: "https://drive.google.com/file/d/1jSbCAfEfOTR4R3ih-uAj_RqVEbVRLSTj/view?usp=sharing", img: "msd.jpg" },
  { dept: "english", title: "Less Miserables", author: "Victor Hugo", pdf: "https://drive.google.com/file/d/1Su5YYV4WrgXqBxtu8JXU2E_ua2jyWNT5/view?usp=sharing", img: "lll.jpg" }
];

const departments = ["all","cse","eee","textile","bba","journalism","fashion","english","mechanical"];
const deptList = document.getElementById("deptList");
const booksGrid = document.getElementById("booksGrid");
const searchInput = document.getElementById("searchInput");

// Animated placeholder
const placeholderText = "Search by books...";
let index = 0;
function animatePlaceholder() {
  searchInput.setAttribute("placeholder", placeholderText.slice(0, index));
  index = (index + 1) % (placeholderText.length + 1);
  setTimeout(animatePlaceholder, 150);
}
animatePlaceholder();

<?php if($is_logged_in): ?>

// Render departments
departments.forEach(dept => {
  const li = document.createElement("li");
  li.textContent = dept.toUpperCase();
  li.dataset.dept = dept;
  if(dept==="all") li.classList.add("active");
  deptList.appendChild(li);
});

// RENDER BOOKS (supports Google Drive)
function renderBooks(filterDept="all", query="") {
  booksGrid.innerHTML = "";
  books
    .filter(b => filterDept==="all" || b.dept===filterDept)
    .filter(b => b.title.toLowerCase().includes(query.toLowerCase()))
    .forEach((b) => {

      // If link contains drive.google.com → open directly
      const pdfLink = b.pdf.includes("drive.google.com") 
                      ? b.pdf 
                      : `ebooks/${b.pdf}`;

      const card = document.createElement("div");
      card.className = "book-card fade-in";
      card.innerHTML = `
        <div class="book-inner">
          <img src="${b.img}" alt="${b.title} by ${b.author}">
          <h3>${b.title}</h3>
          <p>${b.author}</p>
          <a href="${pdfLink}" target="_blank">
            <button class="download-btn">Read / Download</button>
          </a>
        </div>`;
      booksGrid.appendChild(card);
    });
}

renderBooks();

// Department click
deptList.addEventListener("click", e => {
  if(e.target.matches("li")) {
    document.querySelectorAll(".sidebar li").forEach(li => li.classList.remove("active"));
    e.target.classList.add("active");
    renderBooks(e.target.dataset.dept, searchInput.value);
  }
});

// Search input
searchInput.addEventListener("input", () => {
  const activeDept = document.querySelector(".sidebar li.active").dataset.dept;
  renderBooks(activeDept, searchInput.value);
});

<?php endif; ?>

// Loader hide
window.addEventListener("load", () => {
  setTimeout(()=> document.querySelector(".loader-screen").style.display="none", 1500);
});

// Login button loader
<?php if(!$is_logged_in): ?>
document.getElementById("loginBtn").addEventListener("click", function(event){
  event.preventDefault();
  document.querySelector(".loader-screen").style.display="flex";
  setTimeout(()=> window.location.href=this.getAttribute("href"), 1500);
});
<?php endif; ?>
</script>

</body>
</html>
