<?php
    include 'includes/header.php';
?>

<link rel="stylesheet" type="text/css" href="datetimepicker/jquery.datetimepicker.css"/ >
<script src="datetimepicker/build/jquery.datetimepicker.full.min.js"></script>

<body>
    <?php
        $conn = $pdo->open();
        try{
            $stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM cart WHERE user_id=:user");
            $stmt->execute(['user'=>$user['id']]);
            $crow = $stmt->fetch();

            if($crow['numrows'] == 0){
                echo"
                <script type='text/javascript'>
                window.alert('Cart is empty. You are redirected back to your cart.');
                window.location.href='cart_view.php';
                </script>
                            ";
            }
        }
          catch(PDOException $e){
            $output['message'] = $e->getMessage();
            }

            $pdo->close();
    ?>

  <div class="site-wrap">
  	<div class="site-section" data-aos="fade">
  		<div class="container">
  		    <h3 class="col-12 section-title text-center">Checkout</h3>

          <div class="container pt-2">
              <div class="col-12">
                  <div class="checkout">
                      <div class="row">
                          <?php if(!empty($statusMsg) && ($statusMsgType == 'success')){ ?>
                          <div class="col-md-12">
                              <div class="alert alert-success"><?php echo $statusMsg; ?></div>
                          </div>
                          <?php } elseif(!empty($statusMsg) && ($statusMsgType == 'error')){ ?>
                          <div class="col-md-12">
                              <div class="alert alert-danger"><?php echo $statusMsg; ?></div>
                          </div>
                          <?php } ?>

                          <div class="col-md-4 order-md-2 mb-4">
                              <h4 class="d-flex justify-content-between align-items-center mb-3">
                                  <span class="text-muted">Order Summary</span>
                              </h4>
                              <ul class="list-group mb-3">
                                  <?php
                                  $conn = $pdo->open();
                                  try{



                                          $stmt = $conn->prepare("SELECT * FROM cart LEFT JOIN products on products.id=cart.product_id WHERE user_id=:user_id");
                                          $stmt->execute(['user_id'=>$user['id']]);

                                          $total = 0;
                                          foreach($stmt as $item){
                                              $subtotal = $item['price'] * $item['quantity'];
                                              $total += $subtotal;
                                              $grand = number_format($item["price"],2);
                                              echo "
                                              <li class='list-group-item d-flex justify-content-between lh-condensed'>
                                              <div>
                                                  <h6 class='my-0'>".$item['name']."</h6>
                                                  <small class='text-muted'>RM ".$grand."(".$item['quantity'].")</small>
                                              </div>
                                              <span class='text-muted'>RM ".number_format($subtotal,2)."</span>
                                          </li>";
                                          }

                                      }
                                  catch(PDOException $e){
                                      $output['message'] = $e->getMessage();
                                  }

                                  $pdo->close();

                                  ?>

                                  <li class="list-group-item d-flex justify-content-between">
                                      <span>Total (RM)</span>
                                      <strong><?php echo 'RM'.number_format($total,2); ?></strong>
                                  </li>
                              </ul>
                          </div>

                          <div class="col-md-8 order-md-1">
                              <form method="post" action="sales.php">
                                <div class="form-group row">
                                  <label for="payment" class="col-sm-4 col-form-label">Payment Method:</label>
                                  <div class="col-sm-6">
                                    <input class="form-control" type="text" name="payment" value="Pay at Counter" readonly>
                                  </div>
                                </div>

                                  <div class="form-group row">
                                    <label for="date" class="col-sm-4 col-form-label">Pickup Date & Time:</label>
                                    <div class="col-sm-6">
                                      <input type="datetime-local" class="form-control" id="date" placeholder="Select an option" name="date" required>
                                    </div>
                                  </div>

                                  <input class="btn btn-success btn-md" type="submit" name="checkoutSubmit" value="Place Order">
                              </form>
                          </div>

                      </div>
                  </div>
              </div>
          </div>
    </div>
  </div>

<?php
      include 'includes/footer.php';
?>
</body>

</html>
