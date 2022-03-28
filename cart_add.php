<?php
	include 'includes/session.php';

	$conn = $pdo->open();

	$output = array('error'=>false);

	$id =  str_replace(";", "", $_POST['id']);
	$quantity = $_POST['quantity'];

	if(isset($_SESSION['user'])){
		$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM cart WHERE user_id=:user_id AND product_id=:product_id");
		$stmt->execute(['user_id'=>$user['id'], 'product_id'=>$id]);
		$row = $stmt->fetch();
		if($row['numrows'] < 1){
			try{
				$stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
				$stmt->execute(['user_id'=>$user['id'], 'product_id'=>$id, 'quantity'=>$quantity]);
				$output['message'] = 'Item added to cart';

			}
			catch(PDOException $e){
				$output['error'] = true;
				$output['message'] = 'There is a problem when adding the item to cart' ;
			}
		}
		else{
			try{
				$stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id=:user_id AND product_id=:product_id");
				$stmt->execute(['user_id'=>$user['id'], 'product_id'=>$id]);
				$row = $stmt->fetch();
				$current = $row['quantity'];
					$stmt = $conn->prepare("UPDATE cart SET quantity=:quantity WHERE product_id=:product_id AND user_id=:user_id");
					$stmt->execute(['quantity'=>$quantity+$current, 'product_id'=>$id,'user_id'=>$user['id']]);
					$output['message'] = 'Item added to cart';
					}
					catch(PDOException $e){
						$output['error'] = true;
						$output['message'] = 'There is a problem when adding the item to cart' ;
			}
		}
	}
	else{
		if(!isset($_SESSION['cart'])){
			$_SESSION['cart'] = array();
		}

		$exist = array();

		foreach($_SESSION['cart'] as $row){
			array_push($exist, $row['productid']);
		}

		if(in_array($id, $exist)){
			foreach($_SESSION['cart'] as $key => $row){
				if($row['productid'] == $id){
					$_SESSION['cart'][$key]['quantity'] = $quantity + $row['quantity'];
					$output['message'] = 'Item added to cart';
				}
			}
		}
		else{
			$data['productid'] = $id;
			$data['quantity'] = $quantity;

			if(array_push($_SESSION['cart'], $data)){
				$output['message'] = 'Item added to cart';
			}
			else{
				$output['error'] = true;
				$output['message'] = 'Cannot add item to cart';
			}
		}

	}

	$pdo->close();
	echo json_encode($output);

?>
