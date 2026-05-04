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
  <title>PCIU Central Library - Textbooks</title>
  <link rel="stylesheet" href="test1.css">
  <link rel="icon" href="logo.png"/>

  <style>
    .view-details-btn{
        margin-top:10px;
        padding:6px 12px;
        border:none;
        border-radius:6px;
        background:#005a9c;
        color:#fff;
        cursor:pointer;
        transition:0.3s ease;
    }
    .view-details-btn:hover{
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
        object-fit: cover;
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
        <img src="uploads/<?php echo $_SESSION['user_photo'] ?? 'default_photo.png'; ?>" 
             alt="Profile" class="profile-icon">
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
    <h2 class="page-heading">Textbooks</h2>
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
        <p>Administrative Building (Ground Floor)<br>
           7-14, Nikunja Housing Society,<br>
           South Khulshi, Chattogram</p>
      </div>

      <div class="footer-column footer-map">
        <h3>FIND US</h3>
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.34523412345!2d91.823456789!3d22.3356789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30ad3abcdef12345%3A0xabcdef1234567890!2sPort+City+International+University!5e0!3m2!1sen!2sbd!4v1693113600000!5m2!1sen!2sbd"
          width="100%" height="200" style="border:0;" allowfullscreen loading="lazy">
        </iframe>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <p>© 2025 Port City International University. All Rights Reserved. 
       Design & Maintenance by <span class="highlight">ICT Cell</span></p>
  </div>
</footer>

<!-- JavaScript -->
<script>
// ALL TEXTBOOKS + ALL EBOOK BOOKS MERGED
const books = [



  // --- NEW EBOOK BOOKS ADDED TO TEXTBOOKS ---
  { dept: "cse", title: "Data Mining: Concepts and Techniques", author: "Jiawei Han, Micheline Kamber, Jian Pei", img: "datamining.jpg" },
  { dept: "cse", title: "Digital Image Processing", author: "Rafael C. Gonzalez & Richard E. Woods", img: "dip.jpg" },
  { dept: "cse", title: "Interactive Computer Graphics", author: "Edward Angel & Dave Shreiner", img: "icg.jpg" },
  { dept: "cse", title: "Software Engineering", author: "Ian Sommerville", img: "soften.jpg" },
  { dept: "cse", title: "The C++ Programming Language", author: "Bjarne Stroustrup", img: "pl.jpg" },

  { dept: "eee", title: "Introductory Circuit Analysis", author: "Robert L. Boylestad", img: "ca.jpg" },
  { dept: "eee", title: "Elements of Electromagnetics", author: "Matthew N. O. Sadiku", img: "ee.jpg" },

  { dept: "english", title: "Shakespeare: The Invention of the Human", author: "Harold Bloom", img: "shake.jpg" },
  { dept: "english", title: "Orientalism", author: "Edward W. Said", img: "nkd.jpg" },
  { dept: "english", title: "Mrs. Dalloway", author: "Virginia Woolf", img: "msd.jpg" },
  { dept: "english", title: "Les Misérables", author: "Victor Hugo", img: "lll.jpg" }
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

// Render books
function renderBooks(filterDept="all", query="") {
  booksGrid.innerHTML = "";
  books
    .filter(b => filterDept==="all" || b.dept===filterDept)
    .filter(b => b.title.toLowerCase().includes(query.toLowerCase()))
    .forEach((b, idx) => {
      const card = document.createElement("div");
      card.className = "book-card fade-in";
      card.innerHTML = `
        <div class="book-inner">
          <img src="${b.img}" alt="${b.title}">
          <h3>${b.title}</h3>
          <p>${b.author}</p>
          <button class="view-details-btn" onclick="viewDetails(${idx})">View Details</button>
        </div>`;
      booksGrid.appendChild(card);
    });
}
renderBooks();

// View Details → redirect to book details page
function viewDetails(index){
    window.location.href = `books/get_book_details.php?id=${index+1}`;
}

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
