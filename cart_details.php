<?php
	include 'includes/session.php';
	$conn = $pdo->open();

	$output = '';

	if(isset($_SESSION['user'])){
		if(isset($_SESSION['cart'])){
			foreach($_SESSION['cart'] as $row){
				$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM cart WHERE user_id=:user_id AND product_id=:product_id");
				$stmt->execute(['user_id'=>$user['id'], 'product_id'=>$row['productid']]);
				$crow = $stmt->fetch();
				if($crow['numrows'] < 1){
					$stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
					$stmt->execute(['user_id'=>$user['id'], 'product_id'=>$row['productid'], 'quantity'=>$row['quantity']]);
				}
				else{
					$stmt = $conn->prepare("UPDATE cart SET quantity=:quantity WHERE user_id=:user_id AND product_id=:product_id");
					$stmt->execute(['quantity'=>$row['quantity'], 'user_id'=>$user['id'], 'product_id'=>$row['productid']]);
				}
			}
			unset($_SESSION['cart']);
		}

		try{

			$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM cart WHERE user_id=:user");
			$stmt->execute(['user'=>$user['id']]);
			$crow = $stmt->fetch();

			if($crow['numrows'] >0){
			$total = 0;
			$stmt = $conn->prepare("SELECT * ,cart.id AS cartid FROM cart LEFT JOIN products ON products.id=cart.product_id WHERE user_id=:user");
			$stmt->execute(['user'=>$user['id']]);
			$stmt2 = $stmt->fetchAll();
			foreach($stmt2 as $row){
				$image = (!empty($row['image'])) ? 'images/'.$row['image'] : 'images/noimage.jpg';
				$subtotal = $row['price']*$row['quantity'];
				$total += $subtotal;
				$output .= "
					<tr>
						<td><img src='".$image."' width='50px' height='50px'>".$row['name']."</td>
						<td>RM ".number_format($row['price'], 2)."</td>
						<td class='input-group'>
						<div>
							<span class='input-group-btn'>
            					<button type='button' id='minus' class='btn btn-default btn-flat minus' data-id='".$row['cartid']."'><i class='fa fa-minus'></i></button>
            				</span>
            				<input type='text' class='qty-form' value='".$row['quantity']."' id='qty_".$row['cartid']."'>
				            <span class='input-group-btn'>
				                <button type='button' id='add' class='btn btn-default btn-flat add' data-id='".$row['cartid']."'><i class='fa fa-plus'></i>
				                </button>
				            </span>
							</div>
						</td>
						<td>RM ".number_format($subtotal, 2)."</td>
						<td><button type='button' data-id='".$row['cartid']."' class='btn btn-danger btn-flat cart_delete'><i class='fa fa-trash'></i></button></td>
					</tr>
				";
			}
			$output .= "
				<tr>
					<td colspan='3' class='text-right'><b>Total</b></td>
					<td><b>RM ".number_format($total, 2)."</b></td>
					<td></td>
				<tr>
			";

			}

			else{
				$output .= "
				<tr>
					<td colspan='6' class='text-center font-weight-bold'>Shopping cart is empty</td>
				<tr>
			";
			}
		}
		catch(PDOException $e){
			$output .= $e->getMessage();
		}

	}
	else{
		if(count($_SESSION['cart']) != 0){
			$total = 0;
			foreach($_SESSION['cart'] as $row){
				$stmt = $conn->prepare("SELECT * FROM products WHERE id=:id");
				$stmt->execute(['id'=>$row['productid']]);
				$product = $stmt->fetch();
				$image = (!empty($product['image'])) ? 'images/'.$product['image'] : 'images/noimage.jpg';
				$subtotal = $product['price']*$row['quantity'];
				$total += $subtotal;
				$prodId =  str_replace(";", "", ($row['productid']));
				$output .= "
				<tr>
					<td><a href=\"product.php?product=".$product['id']."\"><img src='".$image."' width='50px' height='50px'>".$product['name']."</a></td>
					<td>RM ".number_format($product['price'], 2)."</td>
					<td class='input-group'>
						<div>
							<span class='input-group-btn'>
            					<button type='button' id='minus' class='btn btn-default btn-flat minus' data-id='".$prodId."'><i class='fa fa-minus'></i></button>
            				</span>
            				<input type='text' class='qty-form' value='".$row['quantity']."' id='qty_".$prodId."'>
				            <span class='input-group-btn'>
				                <button type='button' id='add' class='btn btn-default btn-flat add' data-id='".$prodId."'><i class='fa fa-plus'></i>
				                </button>
				            </span>
							</div>
						</td>
						<td>RM ".number_format($subtotal, 2)."</td>
						<td><button type='button' data-id='".$row['productid']."' class='btn btn-danger btn-flat cart_delete'><i class='fa fa-trash'></i></button></td>
					</tr>
				";

			}

			$output .= "
				<tr>
					<td colspan='3' class='text-right'><b>Total</b></td>
					<td><b>RM ".number_format($total, 2)."</b></td>
					<td></td>
				<tr>
			";
		}

		else{
			$output .= "
				<tr>
					<td colspan='6' class='text-center font-weight-bold'>Shopping cart is empty</td>
				<tr>
			";
		}

	}

	$pdo->close();
	echo json_encode($output);

?>
