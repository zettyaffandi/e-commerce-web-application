<?php include 'includes/header.php'; ?>

<body>
  <div class="site-wrap">
    <div class="site-section py-5 " data-aos="fade">
        <div class="container" align="center">

          <div class="container">
            <div class="box-form container py-2 my-2 col-md-4">
              <h4 class="col-12 section-title text-center">Sign in</h4>
              <?php echo errorMsg() ?>

                <form action="verify.php" method="POST">
                    <div class="form-group has-feedback input-preprend input-container justify-content-center">
                     <i class="fa fa-envelope form-control-feedback icon"></i>
                      <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group has-feedback input-preprend input-container justify-content-center">
                      <i class="fa fa-lock form-control-feedback icon"></i>
                      <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    <div class="row justify-content-center">
                    <div class="col-md-4 col-sm-6">
                          <button type="submit" class="btn btn-primary btn-block btn-flat" name="login"><i class="fa fa-sign-in"></i> Sign In</button>
                      </div>
                    </div>
                </form>
                <!-- <a href="password_forgot.php">I forgot my password</a><br> -->
                <a href="signup.php" class="text-center link-pop">Register a new membership</a><br>
                <a href="password_forgot.php" class="text-center link-pop">Forgot Password</a><br>
              </div>
          </div>
        </div>
      </div>
    </div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
