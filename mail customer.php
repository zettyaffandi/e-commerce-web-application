<?php

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	$order = $_SESSION['orderid'];

	require('fpdf/fpdf.php');
		
		$pdf = new FPDF( 'P', 'mm', 'A4' );

		$pdf->SetAutoPagebreak(False);
		$pdf->SetMargins(0,0,0);

		$pdf->AddPage();

		// logo 
		$pdf->Image('images/logo.png', 10, 10, 80, 55);


		$num_fact = "Order Invoice";
		$pdf->SetLineWidth(0.1); 
		$pdf->SetFillColor(192); 
		$pdf->Rect(120, 15, 85, 8, "DF");
		$pdf->SetXY( 120, 15 ); 
		$pdf->SetFont( "Arial", "B", 16 ); 
		$pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');


		$name_file = "Invoice_#".$order.".pdf";


		$pdf->SetFont('Arial','',11); $pdf->SetXY( 122, 30 ); $pdf->Cell( 60, 8, "Order No. : #".$order, 0, 0, '');
		$conn = $pdo->open();
		try{
			$stmt = $conn->prepare("SELECT placed_at, payment_method, pickup_date, card_num FROM orders where id = $order");
			$stmt->execute();
			foreach($stmt as $row){
				$date = date_create($row['placed_at']);
				$date2 = date_format($date,'d/m/Y');
				$date3 = "Order Placed at : ".$date2;
				$pdf->SetFont('Arial','',11); $pdf->SetXY( 122, 35 ); $pdf->Cell( 60, 8, $date3, 0, 0, '');
				$pdf->SetFont('Arial','',11); $pdf->SetXY( 122, 40 ); $pdf->Cell( 60, 8, "Payment method : ".$row['payment_method'], 0, 0, '');
				if($row['payment_method']=="Pay using Card"){
					$cardnum = "Card Number : ".$row['card_num'];
					$pdf->SetFont('Arial','',11); $pdf->SetXY( 122, 45 ); $pdf->Cell( 60, 8, $cardnum, 0, 0, '');
					$pickup = date_create($row['pickup_date']);
					$pickup2 = date_format($pickup,'h:i A, d/m/Y');
					$pickup3 = "Pickup Date & Time : ".$pickup2;
					$pdf->SetFont('Arial','B',11); $pdf->SetXY( 122, 50 ); $pdf->Cell( 60, 8, $pickup3, 0, 0, '');
				}
				else{
					$pickup = date_create($row['pickup_date']);
					$pickup2 = date_format($pickup,'h:i A, d/m/Y');
					$pickup3 = "Pickup Date & Time : ".$pickup2;
					$pdf->SetFont('Arial','B',11); $pdf->SetXY( 122, 45 ); $pdf->Cell( 60, 8, $pickup3, 0, 0, '');
				}
			}

		}
		catch(PDOException $e){
			echo "There is some problem in connection: " . $e->getMessage();
		}


		$pdf->SetLineWidth(0.2); 
		$pdf->SetFillColor(192); 
		$pdf->Rect(10, 205, 166, 8, "DF");


		$pdf->Rect(10, 205, 190, 8, "D");
				
		try{
			$stmt = $conn->prepare("SELECT c.id, c.firstname, c.lastname, c.contact_info, c.email FROM users c LEFT JOIN orders o ON c.id = o.customer_id where o.id = $order");
			$stmt->execute();
			foreach($stmt as $row){
				$pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 10, 75 ) ; $pdf->Cell($pdf->GetStringWidth("Customer Name : "), 0, "Customer Name : ".$row['firstname']." ".$row['lastname'], 0, "L");
				$pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 10, 78 ) ; $pdf->MultiCell(190, 4,"Contact Number : ".$row['contact_info'], 0, "L");
				$pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 10, 83 ) ; $pdf->MultiCell(190, 4,"Email : ".$row['email'], 0, "L");
			}

		}
		catch(PDOException $e){
			echo "There is some problem in connection: " . $e->getMessage();
		}

		$pdf->SetLineWidth(0.1); $pdf->Rect(10, 95, 190, 118, "D");

		$pdf->Line(10, 105, 200, 105);

		$pdf->Line(130, 95, 130, 205); $pdf->Line(152, 95, 152, 205); $pdf->Line(176, 95, 176, 205); 
		$pdf->SetXY( 1, 96 ); $pdf->SetFont('Arial','B',10); $pdf->Cell( 140, 8, "Products", 0, 0, 'C');
		$pdf->SetXY( 135, 96 ); $pdf->SetFont('Arial','B',10); $pdf->Cell( 13, 8, "Quantity", 0, 0, 'C');
		$pdf->SetXY( 153, 96 ); $pdf->SetFont('Arial','B',10); $pdf->Cell( 22, 8, "Price", 0, 0, 'C');
		$pdf->SetXY( 182, 96 ); $pdf->SetFont('Arial','B',10); $pdf->Cell( 10, 8, "Total", 0, 0, 'C');


		$pdf->SetFont('Arial','',10);
		$y = 97;

		try{
			$stmt = $conn->prepare("SELECT i.product_id, i.quantity, p.name, p.price FROM order_details as i LEFT JOIN products as p ON p.id = i.product_id WHERE i.order_id= $order");
			$stmt->execute();
			foreach($stmt as $row){
				$pro_id = $row['product_id'];			

				// quantity
				$pdf->SetXY( 130, $y+9 );
				$pdf->Cell( 13, 5, $row['quantity'], 0, 0, 'R');

				// total price for total quantity 
				$pdf->SetXY( 182, $y+9 );
				$grand = $row['price']*$row['quantity'];
				$grand2 = number_format($grand,2);
				$pdf->Cell( 10, 5, $grand2, 0, 0, 'R');				

				// product & price per quantity
					
				$pdf->SetXY( 160, $y+9 );
				$pdf->Cell( 140, 5, $row['price'], 0, 0, 'L');

				$pdf->SetXY( 15, $y+9 );
				$pdf->Cell( 140, 5, $row['name'], 0, 0, 'L');
					

				
				$pdf->Line(10, $y+14, 200, $y+14);			
				
				
				$y += 6;
			}

		}
		catch(PDOException $e){
				echo "There is some problem in connection: " . $e->getMessage();
		}
			
		// show total on last page
					
		$TotalAmount = "Net paid/to pay : " ;
		$pdf->SetFont('Arial','B',14);
		$pdf->SetXY( 50, 205 );
		$pdf->Cell( 105, 8, $TotalAmount, 0, 0, 'C');
		

		try{
			$stmt2 = $conn->prepare("SELECT * FROM order_details LEFT JOIN products ON products.id=order_details.product_id WHERE order_id=$order");
			$stmt2->execute();
			$total = 0;
			foreach($stmt2 as $row2){
				$subtotal = $row2['price']*$row2['quantity'];
				$total += $subtotal;
			}

			$totalorder = number_format($total,2);
			$pdf->SetFont('Arial','B',12); 
			$pdf->SetXY( 172, 205 );
			$pdf->Cell( 30, 8, "RM".$totalorder, 0, 0, 'C');
			

		}
		catch(PDOException $e){
			echo "There is some problem in connection: " . $e->getMessage();
		}	

		// **************************
		//footer
		// **************************

		$pdf->SetXY( 1, 255 ); $pdf->SetFont('Arial','',7);
		$pdf->Cell( $pdf->GetPageWidth(), 7, "Thank you for buying and using our service. If any assistance is needed, do contact us at thirst8kch@gmail.com", 0, 0, 'C');

		$pdf->SetLineWidth(0.1); $pdf->Rect(5, 260, 200, 6, "D");
		$pdf->SetXY( 1, 260 ); $pdf->SetFont('Arial','',10);
		$pdf->Cell( $pdf->GetPageWidth(), 7,chr(169)." Copyright 2019 THIRST MILKSHAKE & SMOOTHIES BAR All rights reserved", 0, 0, 'C');

		// attachment name
		$filename = "invoice.pdf";

		// encode data (puts attachment in proper format)
		$pdfdoc = $pdf->Output('F', $filename);

		$time = $date2;
		$pickupchoice = $pickup2;
		
		$stmt = $conn->prepare("SELECT c.email FROM users c LEFT JOIN orders o ON c.id = o.customer_id where o.id = $order");
		$stmt->execute();
		foreach($stmt as $row){
			$to = $row['email'];
		}
		$from = "Thirst Milkshake"; 
		$subject = "Your invoice from Thirst Milkshake & Smoothies Bar (Order #".$order.")"; 
		$message = "Thank you for your order placed on ".$time.". You can collect your order at our kiosk at your selected time by showing the invoice attached.";

	

		require 'vendor/autoload.php';

		$mail = new PHPMailer(true);                             
		try {
		    //Server settings
		    $mail->isSMTP();                                     
		    $mail->Host = 'smtp.gmail.com';                      
		    $mail->SMTPAuth = true;                               
		    $mail->Username = 'projectemail.g6@gmail.com';     
		    $mail->Password = 'eeghsjyvtpazjlts';                    
		    $mail->SMTPOptions = array(
		        'ssl' => array(
		        'verify_peer' => false,
		        'verify_peer_name' => false,
		        'allow_self_signed' => true
		        )
		    );                         
		    $mail->SMTPSecure = 'ssl';                           
		    $mail->Port = 465;                                   

		    $mail->setFrom('projectemail.g6@gmail.com', "Thirst Milkshake & Smoothies Bar");
		    
		    //Recipients
		    $mail->addAddress($to);              
		    $mail->addReplyTo('projectemail.g6@gmail.com');
		   
		    //Content
		    $mail->isHTML(true);                                  
		    $mail->Subject = $subject;
		    $mail->Body    = $message;
		    $mail->AddAttachment($filename);

		    $mail->send();

		    $_SESSION['success'] = 'Your order has been placed successfully. A confirmation email has been sent to your email. Thank you.';
		 
		} 
		catch (Exception $e) {
		    $_SESSION['error'] = 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
		}
			$subject2 = "New order received from Thirst Milkshake & Smoothies Bar Website (Order #".$order.")"; 
		$message2 = "A new order has been placed on ".$time.". The customer will collect the order at our kiosk at your time stated in the invoice attached.";
			$mail2 = new PHPMailer(true);                             
		try {
		    //Server settings
		    $mail2->isSMTP();                                     
		    $mail2->Host = 'smtp.gmail.com';                      
		    $mail2->SMTPAuth = true;                               
		    $mail2->Username = 'projectemail.g6@gmail.com';     
		    $mail2->Password = 'eeghsjyvtpazjlts';                    
		    $mail2->SMTPOptions = array(
		        'ssl' => array(
		        'verify_peer' => false,
		        'verify_peer_name' => false,
		        'allow_self_signed' => true
		        )
		    );                         
		    $mail2->SMTPSecure = 'ssl';                           
		    $mail2->Port = 465;                                   

		    $mail2->setFrom('projectemail.g6@gmail.com', "Thirst Milkshake & Smoothies Bar");
		    
		    //Recipients
		    $mail2->addAddress('projectemail.g6@gmail.com');              
		    $mail2->addReplyTo('projectemail.g6@gmail.com');
		   
		    //Content
		    $mail2->isHTML(true);                                  
		    $mail2->Subject = $subject2;
		    $mail2->Body    = $message2;
		    $mail2->AddAttachment($filename);

		    $mail2->send();
		 
		} 
		catch (Exception $e) {
		    $_SESSION['error'] = 'Message to admin could not be sent. Mailer Error: '.$mail->ErrorInfo;
		}

	$pdo->close();
header('location: profile.php');
?>