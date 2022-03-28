<?php
include 'includes/session.php';

	if(isset($_POST['print'])){
		require('../fpdf/fpdf.php');
	
		$pdf = new FPDF( 'P', 'mm', 'A4' );
		$ex = explode(' - ', $_POST['date_range']);
		$from = date('Y-m-d', strtotime($ex[0]));
		$to = date('Y-m-d', strtotime($ex[1]));
		$from_title = date('M d, Y', strtotime($ex[0]));
		$to_title = date('M d, Y', strtotime($ex[1]));

		$conn = $pdo->open();

		$pdf->SetAutoPagebreak(False);
		$pdf->SetMargins(0,0,0);

		$pdf->AddPage();

		// logo 
		$pdf->Image('../images/logo.png', 70, 10, 80, 55);

		$title = "Sales Report";
		$title2 = "".$from_title." - ".$to_title."";
		$pdf->SetLineWidth(0.1); 
		$pdf->SetFillColor(173,220,222); 
		$pdf->Rect(58, 70, 110, 20, "DF");
		$pdf->SetXY( 70, 72 ); 
		$pdf->SetFont( "Arial", "B", 14 ); 
		$pdf->Cell( 85, 8, $title, 0, 0, 'C');
		$pdf->SetXY( 70, 78 ); 
		$pdf->Cell( 85, 10, $title2, 0, 0, 'C');

		$pdf->Cell( 100, 8, "", 0, 0, 'C');
		
		$pdf->SetFont('Arial','B',12);
		$pdf->SetXY( 10, 100 );
		$pdf->Cell( 45, 8, "Date", 1, 0); 
		$pdf->Cell( 80, 8, "Customer Name", 1, 0); 
		$pdf->Cell( 30, 8, "Order #", 1, 0); 
		$pdf->Cell( 35, 8, "Amount", 1, 1);

		$pdf->SetFont('Arial','',11);
		
		$stmt = $conn->prepare("SELECT *, orders.id AS salesid FROM orders LEFT JOIN users ON users.id=orders.customer_id WHERE placed_at BETWEEN '$from' AND '$to' ORDER BY placed_at DESC");
				$stmt->execute();
				$total = 0;
				foreach($stmt as $row){
					$stmt = $conn->prepare("SELECT * FROM order_details LEFT JOIN products ON products.id=order_details.product_id WHERE order_id=:id");
					$stmt->execute(['id'=>$row['salesid']]);
					$amount = 0;
					foreach($stmt as $details){
						$subtotal = $details['price']*$details['quantity'];
						$amount += $subtotal;						
					}
					$amount = number_format($amount,2);
					$total += $amount;
					$pdf->SetX( 10);
					$pdf->Cell( 45, 8, $row['placed_at'], 1, 0); 
					$pdf->Cell( 80, 8, $row['firstname'].' '.$row['lastname'], 1, 0); 
					$pdf->Cell( 30, 8, $row['salesid'], 1, 0); 
					$pdf->Cell( 35, 8, 'RM '.$amount, 1, 1);

				}
		$pdf->SetFont('Arial','B',11);
		$pdf->SetX( 135);
		$total = number_format($total,2);
		$pdf->Cell( 30, 8, 'Total', 1, 0); 
		$pdf->Cell( 35, 8, 'RM '.$total, 1, 0); 

		

		$name_file = "Sales.pdf";
		$pdf->Output("I", $name_file);

	}
	else{
		$_SESSION['error'] = 'Need date range to provide sales print';
		header('location: sales.php');
	}

$pdo->close();
?>

