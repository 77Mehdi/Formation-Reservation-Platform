<?php

include 'db.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

//var_dump($_SESSION['user']['role']);

// Handle logout directly from this file
if (isset($_GET['logout'])) {
  session_destroy();
  header('Location: index.php');
  exit();
}
?>

<!-- Slider Stylesheet -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />

<!-- Bootstrap Core CSS -->
<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

<!-- Fonts Style -->
<link href="https://fonts.googleapis.com/css?family=Lato:400,700|Poppins:400,700|Roboto:400,700&display=swap" rel="stylesheet" />

<!-- Custom Styles for this Template -->
<link href="css/style.css" rel="stylesheet" />

<!-- Responsive Styles -->
<link href="css/responsive.css" rel="stylesheet" />

<header class="header_section">
  <div class="container">
    <nav class="navbar navbar-expand-lg custom_nav-container">
      <a class="navbar-brand" href="index.php">
        <img src="images/logo.png" alt="Brighton Logo" />
        <span>Brighton</span>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="d-flex ml-auto flex-column flex-lg-row align-items-center mx-1">
          <ul class="navbar-nav">
            <li class="nav-item active">
              <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about.php">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="program.php">Programs</a>
            </li>
          
            <li class="nav-item">
              <a class="nav-link" href="formationPage.php"> Formation</a>
            </li>

            <!-- Admin Link Visible Only to Admins -->
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'): ?>
              <li class="nav-item">
                <a class="nav-link text-white" href="admin.php">Admin Page</a>
              </li>
            <?php endif; ?>

            <?php if (isset($_SESSION['user'])): ?>
              <li class="nav-item">
                <a class="nav-link text-white" href="?logout=true">Déconnecter</a>
              </li>
            <?php else: ?>
              <li class="nav-item">
                <a class="nav-link text-white" href="login.php">Login</a>
              </li>
    
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
  </div>
</header>
