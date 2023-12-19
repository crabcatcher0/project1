<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostelDiscover</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <header>
      <nav class="navbar">
        <a class="logo" href="#">HostelDiscover</a>
        <ul class="menu-links">
          <li><a href="#">Home</a></li>
          <li><a href="./hostel/hostel.php">Hostels</a></li>
          <?php
          // starting a session to manage user data across pages
          //session variable is set email
                session_start();
                    // Check if the user is logged in
                    //uses session of user
                    if (isset($_SESSION['email'])) { //loop
                        // User is logged in show "Account" link
                        echo '<li><a href="./accounts/account.php">Account</a></li>';
                    } else {
                        // User is not logged in show "Sign Up" link
                        echo '<li><a href="./signup/signup.php">Sign Up</a></li>';
                    }
                ?>
          <li><a href="./contact/contact.php">Contact us</a></li>
        </ul>
        </nav>
    </header>
    <section class="mid-section">
      <div class="content">
        <h1>Welcome To <span style="color: #A9A9A9;">HostelDiscover.</span></h1>

        <p>
          Find What You Are Looking. Today.
        </p>
      </div>
      <div class="search-box">
        <form action="./search/search.php" method="GET">
          <input type="text" id="search" name="search" placeholder="eg.(Boys/Girls hostel)" required>
          <button type="submit">Go</button>
        </form>
      </div>
    </section>
    <footer>
      <div class="footer-content">
        <p>&copy; 2023 HostelDiscover. All rights reserved.</p>
      </div>
    </footer>
    
</body>
</html>