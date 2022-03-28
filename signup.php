<?php include 'includes/header.php'; ?>
<?php
  if(isset($_SESSION['captcha'])){
    $now = time();
    if($now >= $_SESSION['captcha']){
      unset($_SESSION['captcha']);
    }
  }

?>
<body>

  <div class="site-wrap">
    <div class="site-section py-5 " data-aos="fade">
        <div class="container" align="center">
          <div class="container">
            <div class="box-form container py-2 mt-2 col-md-4">
              <h3 class="col-12 section-title text-center">Register a new membership</h3>
              <?php echo errorMsg() ?>
              <form action="register.php" method="POST">
                  <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="firstname" placeholder="First Name" value="<?php echo (isset($_SESSION['firstname'])) ? $_SESSION['firstname'] : '' ?>" required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="lastname" placeholder="Last Name" value="<?php echo (isset($_SESSION['lastname'])) ? $_SESSION['lastname'] : '' ?>"  required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo (isset($_SESSION['email'])) ? $_SESSION['email'] : '' ?>" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="repassword" placeholder="Retype password" required>
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                  </div>
                  <?php
                    if(!isset($_SESSION['captcha'])){
                      echo '
                        <di class="form-group" style="width:100%;">
                          <div class="g-recaptcha" data-sitekey="6Lf3MMgUAAAAADZ2za34GIdHb7iDSMpthVToLJ3Q"></div>
                        </di>
                      ';
                    }
                  ?>
                  <br>
                  <div class="row justify-content-center">
                  <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-block btn-flat" name="signup"></i> Sign Up</button>
                    </div>
                  </div>
              </form>
            <p><a class="link-pop" href="login.php">I already have a membership</a></p>
          </div>
      </div>
        </div>
      </div>
    </div>


<?php include 'includes/footer.php'; ?>
</body>
</html>
