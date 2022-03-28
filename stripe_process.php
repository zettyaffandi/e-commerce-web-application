<?php

include 'includes/session.php';
//check whether stripe token is not empty
if(!empty($_POST['stripeToken'])){
    //get token, card and user info from the form
    $token  = $_POST['stripeToken'];
    $card_num = $_POST['card_num'];
    $card_cvc = $_POST['cvc'];
    $card_exp_month = $_POST['exp_month'];
    $card_exp_year = $_POST['exp_year'];
    $payment = strip_tags($_POST['payment']);
    $date = strip_tags($_POST['date']);

    $conn = $pdo->open();
    try{
            $stmt = $conn->prepare("SELECT * FROM cart LEFT JOIN users on users.id=cart.user_id WHERE user_id=:user_id");
            $stmt->execute(['user_id'=>$user['id']]);
            $row = $stmt->fetch();
            $email = $row['email'];
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];


        }
    catch(PDOException $e){
        $output['message'] = $e->getMessage();
    }

    $pdo->close();


    //include Stripe PHP library
    require_once('stripe-php-master/init.php');

    //set api key
    $stripe = array(
      "secret_key"      => "sk_test_BSfXE3lNBiFor2hivBsPn9HV00mrOw1Oyq",
      "publishable_key" => "pk_test_XkTimsK2SvrPHS6uktjtb6H300ngZTrCJ4"
    );

    \Stripe\Stripe::setApiKey($stripe['secret_key']);

    //add customer to stripe
    $customer = \Stripe\Customer::create(array(
        'email' => $email,
        'source'  => $token
    ));

    //item information
    $details = "".$firstname." ".$lastname."";
    $paying= (int)($_POST['amount'] * 100);
    $itemPrice = $paying;
    $currency = "myr";
    $pickup = date('Y-m-d H:i:s',strtotime($_POST['date']));

    //charge a credit or a debit card
    $charge = \Stripe\Charge::create(array(
        'customer' => $customer->id,
        'amount'   => $itemPrice,
        'currency' => $currency,
        'description' => 'Order Pickup Date & Time: '.$pickup,
        'metadata' => array(
            'Order by' => $details
        )
    ));

    //retrieve charge details
    $chargeJson = $charge->jsonSerialize();

    //check whether the charge is successful
    if($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1){
        //order details
        $balance_transaction = $chargeJson['balance_transaction'];
        $status = 'Paid';
        $orderdate = date("Y-m-d");


        try{

            $time1 = date('Y-m-d H:i:s',strtotime($_POST['date']));
            $stmt = $conn->prepare("INSERT INTO orders (customer_id, placed_at, payment_method, pickup_date, card_num, card_cvc, card_exp_month, card_exp_year, txn_id, payment_status) VALUES (:user_id, :placed_at, :payment, :pickup, :card_num, :card_cvc, :card_exp_month, :card_exp_year, :txn_id, :payment_status)");
            $stmt->execute(['user_id'=>$user['id'], 'placed_at'=>$orderdate, 'payment'=>$payment, 'pickup'=>$time1, 'card_num'=>$card_num, 'card_cvc'=>$card_cvc, 'card_exp_month'=>$card_exp_month, 'card_exp_year'=>$card_exp_year, 'txn_id'=>$balance_transaction, 'payment_status'=>$status]);
            $salesid = $conn->lastInsertId();

            try{
                $stmt = $conn->prepare("SELECT * FROM cart LEFT JOIN products ON products.id=cart.product_id WHERE user_id=:user_id");
                $stmt->execute(['user_id'=>$user['id']]);

                foreach($stmt as $row){
                    $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)");
                    $stmt->execute(['order_id'=>$salesid, 'product_id'=>$row['product_id'], 'quantity'=>$row['quantity']]);
                }

                $stmt = $conn->prepare("DELETE FROM cart WHERE user_id=:user_id");
                $stmt->execute(['user_id'=>$user['id']]);
                $_SESSION['orderid'] = $salesid;
                require_once 'mail customer.php';
                $redirectLoc = 'profile.php';

            }
            catch(PDOException $e){
                $_SESSION['error'] = $e->getMessage();
                $redirectLoc = 'profile.php';
            }

        }
        catch(PDOException $e){
            $_SESSION['error'] = $e->getMessage();
            $redirectLoc = 'profile.php';
        }
    }
    else {
      $statusMsg = "Form submission error.......";
    }
}
else{
    $statusMsg = "Form submission error.......";
}
       header("Location: $redirectLoc");
?>
