<?php include 'includes/header.php'; ?>

<body>
<div class="wrapper">

	  <div class="content-wrapper pt-5">
	    <div class="container pt-5">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row">
	        	<div class="col-md-12">
	        		<?php echo errorMsg() ?>
	        		<h3 class="col-12 section-title text-center pt-3">Profile</h3>
	        		<div class="box box-solid py-3 px-5">
	        			<h4 class="box-title"><b>Personal Information</b></h4>
	        				<div class="col-md-12">
	        					<img class="pull-left mr-5" src="<?php echo (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg'; ?>" width=180px>
	        					<p></p>
    							<p>Name: <?php echo $user['firstname'].' '.$user['lastname']; ?>
    								<span class="pull-right">
    									<a href="#edit" class="btn btn-success btn-flat btn-sm" data-toggle="modal"><i class="fa fa-edit"></i> Edit</a>
    								</span>
    							</p>
    							<p>Email: <?php echo $user['email']; ?></p>
    							<p>Contact Info: <?php echo (!empty($user['contact_info'])) ? $user['contact_info'] : 'N/a'; ?></p>
    							<p>Address: <?php echo (!empty($user['address'])) ? $user['address'] : 'N/a'; ?></p>
    							<p>Member Since: <?php echo date('M d, Y', strtotime($user['created_on'])); ?></p>
    						</div>

	        			</div>
	        		</div>

	        		<div class="box box-solid mt-5 pt-3 pb-5 px-5 col-md-12">
	        				<h4 class="box-title"><i class="fa fa-calendar"></i> <b>Order History</b></h4>

	        			<div class="table-responsive">
	        				<input id="myInput" type="text" placeholder="Search.." title="Search order" class="my-3">
	        				<table class="table table-bordered table-striped py-3" id="example1">
	        					<thead>
	        						<th>Date<i class="fa fa-fw fa-sort fa-lg pull-right" onclick="sortTable(0)"></i></th>
	        						<th>Order #<i class="fa fa-fw fa-sort fa-lg pull-right" onclick="sortTable(1)"></i></th>
	        						<th>Amount<i class="fa fa-fw fa-sort fa-lg pull-right" onclick="sortTable(2)"></i></th>
	        						<th>Pickup Time</th>
	        						<th>Full Details</th>
	        					</thead>
	        					<tbody>
	        					<?php
	        						$conn = $pdo->open();

	        						try{
	        							$stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id=:user_id ORDER BY placed_at DESC");
	        							$stmt->execute(['user_id'=>$user['id']]);
	        							foreach($stmt as $row){
	        								$stmt2 = $conn->prepare("SELECT * FROM order_details LEFT JOIN products ON products.id=order_details.product_id WHERE order_id=:id");
	        								$stmt2->execute(['id'=>$row['id']]);
	        								$total = 0;
	        								foreach($stmt2 as $row2){
	        									$subtotal = $row2['price']*$row2['quantity'];
	        									$total += $subtotal;
	        								}
	        								echo "
	        									<tr>
	        										<td>".date('M d, Y', strtotime($row['placed_at']))."</td>
	        										<td>".$row['id']."</td>
	        										<td>RM ".number_format($total, 2)."</td>
	        										<td>".$row['pickup_date']."</td>
	        										<td><a class='btn btn-sm btn-flat btn-info' href='invoice.php?id=".$row['id']."' target='_blank'><i class='fa fa-search'></i> View</a></td>
	        									</tr>
	        								";
	        							}

	        						}
        							catch(PDOException $e){
										echo "There is some problem in connection: " . $e->getMessage();
									}

	        						$pdo->close();
	        					?>
	        					</tbody>
	        				</table>
	        			</div>
	        		</div>
				</div>
	      </section>

	    </div>
	  </div>

  	<?php include 'includes/footer.php'; ?>
  	<?php include 'includes/profile_modal.php'; ?>
</div>

<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#example1 tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});

function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("example1");
  switching = true;
  //Set the sorting direction to ascending:
  dir = "asc";
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
};
</script>
</body>
</html>
