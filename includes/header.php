<?php
  include 'includes/session.php';
  include 'function.php';
?>
<?php include 'includes/scripts.php'; ?>

<?php
  $currentPageUrl = 'https://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

  if($currentPageUrl == 'https://localhost/ThirstKCH/login.php' || $currentPageUrl == 'https://localhost/ThirstKCH/signup.php'){
    if(isset($_SESSION['user'])){
      header('location: index.php');
    }
  }

  if($currentPageUrl == 'https://localhost/ThirstKCH/profile.php'){
    if(!isset($_SESSION['user'])){
      header('location: index.php');
    }
  }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Thirst Milkshake & Smoothies Bar</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <!-- Bootstrap 4.1.3 -->

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">

    <link rel="stylesheet" href="css/jquery.fancybox.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">

    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">

    <link rel="stylesheet" href="css/aos.css">
    <link href="css/jquery.mb.YTPlayer.min.css" media="all" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

    <!-- Google Recaptcha -->
    <script src='https://www.google.com/recaptcha/api.js'></script>

</head>

 <header data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

  <div class="site-wrap">
    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div>

     <div class="header-top">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-12 text-center">
            <a href="index.php" class="site-logo">
              <img src="images/logo.png" alt="Thirst Logo" class="img-fluid" width="200px">
            </a>
          </div>
          <a href="#" class="mx-auto d-inline-block d-lg-none site-menu-toggle js-menu-toggle">
            <span class="icon-menu h3"></span></a>
        </div>
      </div>

  <div class="site-navbar py-2 site-navbar-target d-none pl-0 d-lg-block" role="banner">

      <nav class="site-navigation position-relative text-left" role="navigation">
      <div class="container">
        <div class="d-flex align-items-center">
          <div class="mr-auto">
              <ul class="site-menu main-menu js-clone-nav mx-auto d-none pl-0 d-lg-block border-none">
                <li><a href="index.php" class="nav-link text-left">Home</a></li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle text-left" data-toggle="dropdown">Menu<span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="menu.php" class="clickable">All</a></li>
                    <?php
                    echo getCat();
                     ?>
                  </ul>
                </li>
                <li><a href="about.php" class="nav-link text-left">About Us</a></li>
                <li><a href="findUs.php" class="nav-link text-left">Find Us</a></li>
              </ul>
          </div>

      <!-- Navbar Right Menu -->
      <div class="pull-right ml-auto">
            <ul class=" site-menu main-menu js-clone-nav mx-auto d-none pl-0 d-lg-block">
                <li>
                    <a href="cart_view.php">
                      <i class="cart_icon fa fa-shopping-cart fa-2x">
                        <span class="badge badge-pill badge-primary cart_count"></span>
                      </i>
                    </a>
                 </li>
          <?php
            if(isset($_SESSION['user'])){
              $image = (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg';
              echo '
                <li class="dropdown user user-menu px-3">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="'.$image.'" class="img-fluid rounded-circle profile_pic" alt="User Image">
                    <span class="hidden-xs">'.$user['firstname'].' '.$user['lastname'].'</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li class="user-footer px-1">
                      <li>
                        <a href="profile.php" class="dropdown-item">Profile & Order History</a>
                      </li>
                      <li>
                        <a href="logout.php" class="dropdown-item">Sign out</a>
                      </li>
                    </li>
                  </ul>
                </li>
              ';
            }
            else{
               echo "
                <li class='px-2'><a href='login.php' class='nav-link text-left'>Login</a></li>
                <li><a href='signup.php' class='nav-link text-left'>Signup</a></li>
              ";
            }
          ?>
        </ul>
      </div>
    </div>
  </div>
</nav>
</div>
</div>
</div>
<?php $pdo->close(); ?>

<!-- loader -->
  <div id="loader" class="show fullscreen">
    <svg class="circular" width="48px" height="48px">
      <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
      <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#ff5e15"/>
    </svg>
  </div>

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/jquery.countdown.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.fancybox.min.js"></script>
  <script src="js/jquery.sticky.js"></script>
  <script src="js/jquery.mb.YTPlayer.min.js"></script>
  <script src="js/main.js"></script>

</header>
