<?php
    include 'includes/header.php';
?>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<link rel="stylesheet" type="text/css" href="datetimepicker/jquery.datetimepicker.css"/ >
<script src="datetimepicker/build/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript">
//set your publishable key
Stripe.setPublishableKey('stripeKey');

//callback to handle the response from stripe
function stripeResponseHandler(status, response) {
    if (response.error) {
        //enable the submit button
        $('#payBtn').removeAttr("disabled");
        //display the errors on the form
        $(".payment-errors").html(response.error.message);
    } else {
        var form$ = $("#paymentFrm");
        //get token id
        var token = response['id'];
        //insert the token into the form
        form$.append("<input type='hidden' name='stripeToken' value='"
+ token + "' />");
        //submit form to the server
        form$.get(0).submit();
    }
}
$(document).ready(function() {
    //on form submit
    $("#paymentFrm").submit(function(event) {
        //disable the submit button to prevent repeated clicks
        $('#payBtn').attr("disabled", "disabled");

        //create single-use token to charge the user
        Stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
        }, stripeResponseHandler);

        //submit from callback
        return false;
    });
});
</script>

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
                          <!-- display errors returned by createToken -->
                          <span class="payment-errors alert-danger"></span>
                          <form action="stripe_process.php" method="POST" id="paymentFrm">
                            <div class="form-group row">
                              <label for="payment" class="col-sm-4 col-form-label">Payment Method:</label>
                              <div class="col-sm-6">
                                <input class="form-control" type="text" name="payment" value="Pay using Card" readonly>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label for="date" class="col-sm-4 col-form-label">Pickup Date & Time:</label>
                              <div class="col-sm-6">
                                <input type="datetime-local" class="form-control" id="date" placeholder="Select an option" name="date" required>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label for="card_num" class="col-sm-4 col-form-label">Card Number:</label>
                              <div class="col-sm-6">
                                <input type="text" name="card_num" size="20" autocomplete="off" class="card-number form-control" required>
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-4">
                                <label for="exp_month">Exp Month</label>
                                <select name="exp_month" class="card-expiry-month form-control py-0" required>
                                  <option data-display="Select an option">Month</option>
                                  <option value="01">January</option>
                                  <option value="02">Febuary</option>
                                  <option value="03">March</option>
                                  <option value="04">April</option>
                                  <option value="05">May</option>
                                  <option value="06">June</option>
                                  <option value="07">July</option>
                                  <option value="08">August</option>
                                  <option value="09">September</option>
                                  <option value="10">October</option>
                                  <option value="11">November</option>
                                  <option value="12">December</option>
                                </select>
                              </div>

                              <div class="form-group col-md-2">
                                 <label for="exp_year">Exp Year</label>
                                 <input type="text" class="card-expiry-year form-control" placeholder="YYYY" name="exp_year" required>
                              </div>

                              <div class="form-group col-md-2">
                                 <label for="cvc">CVC</label>
                                <input type="text" class="card-cvc form-control" placeholder="CVC" name="cvc" required>
                              </div>
                            </div>

                            <input type="hidden" name="payment" value="Pay using Card">
                            <input type="hidden" name="amount" value="<?php echo $total; ?>">

                            <input class="btn btn-success btn-md text-white my-2" type="submit" name="checkoutSubmit" value="Place Order">
                          </form>
                      </div>

                  </div>
              </div>
          </div>
      </div>
    </div>
  </div>
</div>

</body>

<?php
      include ("includes/footer.php");
?>

</html>
