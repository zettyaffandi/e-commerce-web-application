<?php
	include 'includes/session.php';

	if(isset($_POST['edit_sales'])){
		$id = $_POST['id'];
		$payment = $_POST['edit_payment'];
		$pickup = $_POST['edit_pickup'];

		try{
			$stmt = $conn->prepare("UPDATE orders SET payment_status=:payment, pickup_status=:pickup WHERE id=:id");
			$stmt->execute(['payment'=>$payment, 'pickup'=>$pickup, 'id'=>$id]);
			$_SESSION['success'] = 'Status updated successfully';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}
		
		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Fill up edit form first';
	}

	header('location: sales.php');

?>