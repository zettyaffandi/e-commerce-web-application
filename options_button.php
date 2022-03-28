<?php
	include 'includes/session.php';

	$conn = $pdo->open();

  global $conn;
  global $user;
  $returnval = "";;

    if(isset($_SESSION['user'])){
      // $user = $_SESSION['user'];

      $stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM cart WHERE user_id=:user");
			$stmt->execute(['user'=>$user['id']]);
			$crow = $stmt->fetch();

      if($crow['numrows'] >0){
        $returnval .= "

                <div class='row paybtn'>
                    <div class='col-sm-12 col-md-4'>

                        <a href='checkout.php' class='btn btn-block btn-success'>Pay at Counter</a>

                    </div>
                    <div class='col-sm-12 col-md-4'>

                        <a href='stripe.php' class='btn btn-block btn-success'>Pay using Card</a>

                    </div>
                </div>
        ";
      }

      else{
        $returnval .= "
                <div class='row paybtn'>
                    <div class='col-sm-4 col-md-4'>
                        <a href='menu.php' class='btn btn-block btn-info'>Continue Shopping</a>
                    </div>

                </div>
        ";
      }
    }
    else{

      if(count($_SESSION['cart']) != 0){
      $returnval .= "
        <h4>You need to <a href='login.php'>Login</a> to checkout.</h4>
      ";
      }
      else{
        $returnval .= "
              <div class='row paybtn'>
                  <div class='col-sm-4 col-md-4'>
                      <a href='menu.php' class='btn btn-block btn-info'>Continue Shopping</a>
                  </div>
              </div>
      ";
      }
    }
		$pdo->close();
		echo json_encode($returnval);
?>
