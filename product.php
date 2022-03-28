<?php include 'includes/header.php'; ?>

<body>

	<div class="site-wrap">
    <div class="site-section py-5 " data-aos="fade">
      <div class="container" align="center">
				<h3 class="col-12 section-title text-center">Product Details</h3>
				<div class="container pt-5">

		      <!-- Main content -->
		      <section class="content">
						<div class="row justify-content-center">
							<div class="col-md-4 callout mb-5 text-center font-weight-bold" id="callout" style="display:none;">
								<button type="button" class="close"><span aria-hidden="true">&times;</span></button>
								<span class="message"></span>
							</div>
						</div>

	          <div class="container">
	          	<?php echo getProductDetails() ?>
	          </div>
		      </section>

		    </div>
			</div>
		</div>


  	<?php include 'includes/footer.php'; ?>

<script>
$(function(){
	$('#add').click(function(e){
		e.preventDefault();
		var quantity = $('#quantity').val();
		quantity++;
		$('#quantity').val(quantity);
	});
	$('#minus').click(function(e){
		e.preventDefault();
		var quantity = $('#quantity').val();
		if(quantity > 1){
			quantity--;
		}
		$('#quantity').val(quantity);
	});

});
</script>
</body>
</html>
