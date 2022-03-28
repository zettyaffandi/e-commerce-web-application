<?php
	include 'includes/session.php';

	$id = $_POST['id'];

	$conn = $pdo->open();

	$output = array('list'=>'');

	$stmt = $conn->prepare("SELECT * FROM order_details LEFT JOIN products ON products.id=order_details.product_id LEFT JOIN orders ON orders.id=order_details.order_id WHERE order_details.order_id=:id");
	$stmt->execute(['id'=>$id]);

	$total = 0;
	foreach($stmt as $row){
		$output['transaction'] = $row['id'];
		$output['date'] = date('M d, Y', strtotime($row['placed_at']));
		$subtotal = $row['price']*$row['quantity'];
		$total += $subtotal;
		$output['list'] .= "
			<tr class='prepend_items'>
				<td>".$row['name']."</td>
				<td>RM ".number_format($row['price'], 2)."</td>
				<td>".$row['quantity']."</td>
				<td>RM ".number_format($subtotal, 2)."</td>
			</tr>
		";
	}
	
	$output['total'] = '<b>RM '.number_format($total, 2).'<b>';
	$pdo->close();
	echo json_encode($output);

?>?>