<?php
	include 'includes/session.php';


		$conn = $pdo->open();

		$payment = strip_tags($_POST['payment']);
        // $date = strip_tags($_POST['date']);
				$date = str_replace('T',' ', $_POST['date']);
				$date = strip_tags($date);
        $orderdate = date('Y-m-d');
        $status = "Pending";

		try{

			$time1 = date('Y-m-d H:i:s', strtotime($date));
			$stmt = $conn->prepare("INSERT INTO orders (customer_id, placed_at, payment_method, pickup_date, payment_status) VALUES (:user_id, :placed_at, :payment, :pickup, :payment_status)");
			$stmt->execute(['user_id'=>$user['id'], 'placed_at'=>$orderdate, 'payment'=>$payment, 'pickup'=>$time1, 'payment_status'=>$status]);
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

				$_SESSION['success'] = 'Your order has been placed successfully. A confirmation email has been sent to your email. Thank you.';
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

		$pdo->close();


	header("Location: $redirectLoc");

?>
