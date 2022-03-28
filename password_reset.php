<?php
  if(!isset($_GET['code']) OR !isset($_GET['user'])){
    header('location: index.php');
    exit();
  }
?>
<?php include 'includes/header.php'; ?>
<body>
<div class="container">

    <div class="container py-5 mt-2 col-md-4">
      <?php echo errorMsg() ?>
      <h4 class="col-12 section-title text-center mb-5 pt-5">Enter new password</h4>

      <form action="password_new.php?code=<?php echo $_GET['code']; ?>&user=<?php echo $_GET['user']; ?>" method="POST">
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password" placeholder="New password" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="repassword" placeholder="Re-type password" required>
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
          </div>
          <div class="row">
          <div class="col-md-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat" name="reset"><i class="fa fa-check-square-o"></i> Reset</button>
            </div>
          </div>
      </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
