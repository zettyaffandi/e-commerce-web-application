<?php
	include 'includes/session.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
		$name = $_POST['name'];
		$category = $_POST['category'];
		$price = $_POST['price'];
		$description = $_POST['description'];
		$avail =  $_POST['availability'];

		$conn = $pdo->open();

		try{
			$stmt = $conn->prepare("UPDATE products SET name=:name, cat_id=:category, price=:price, description=:description, avail=:avail WHERE id=:id");
			$stmt->execute(['name'=>$name, 'category'=>$category, 'price'=>$price, 'description'=>$description, 'avail'=>$avail, 'id'=>$id]);
			$_SESSION['success'] = 'Product updated successfully';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}
		
		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Fill up edit product form first';
	}

	header('location: products.php');

?>