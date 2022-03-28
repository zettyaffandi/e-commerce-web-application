<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- CK Editor -->
<script src="bower_components/ckeditor/ckeditor.js"></script>


<!-- Custom Scripts -->
<script>
$(function(){

  getCart();

  $('#productForm').submit(function(e){
    e.preventDefault();
    var product = $(this).serialize();
    $.ajax({
      type: 'POST',
      url: 'cart_add.php',
      data: product,
      dataType: 'json',
      success: function(response){
        $('#callout').show();
        $('.message').html(response.message);
        if(response.error){
          $('#callout').removeClass('alert-success').addClass('alert-danger');
        }
        else{
        $('#callout').removeClass('alert-danger').addClass('alert-success');
        getCart();
        }
      }
    });
  });

  $(document).on('click', '.close', function(){
    $('#callout').hide();
  });

});

function getCart(){
  $.ajax({
    type: 'POST',
    url: 'cart_fetch.php',
    dataType: 'json',
    success: function(response){
      $('.cart_count').html(response.count);
    }
  });
}


$(function(){
	$(document).on('click', '.cart_delete', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		$.ajax({
			type: 'POST',
			url: 'cart_delete.php',
			data: {id:id},
			dataType: 'json',
			success: function(response){
				if(!response.error){
					getDetails();
          getOptionButton();
					getCart();
					getTotal();
				}
			}
		});
	});

  $(document).on('click', '.minus', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var qty = $('#qty_'+id).val();
		if(qty>1){
			qty--;
		}
		$('#qty_'+id).val(qty);
		$.ajax({
			type: 'POST',
			url: 'cart_update.php',
			data: {
				id: id,
				qty: qty
			},
			dataType: 'json',
			success: function(response){
				if(!response.error){
					getDetails();
          getOptionButton();
					getCart();
					getTotal();
				}
			}
		});
	});

	$(document).on('click', '.add', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var qty = $('#qty_'+id).val();
		qty++;
		$('#qty_'+id).val(qty);
		$.ajax({
			type: 'POST',
			url: 'cart_update.php',
			data: {
				id: id,
				qty: qty
			},
			dataType: 'json',
			success: function(response){
				if(!response.error){
					getDetails();
          getOptionButton();
					getCart();
					getTotal();
				}
			}
		});
	});

	getDetails();
	getTotal();
  getOptionButton();

});

function getDetails(){
  $.ajax({
    type: 'POST',
    url: 'cart_details.php',
    dataType: 'json',
    success: function(response){
      $('#tbody').html(response);
      getCart();
    }
  });
}

function getTotal(){
  $.ajax({
    type: 'POST',
    url: 'cart_total.php',
    dataType: 'json',
    success:function(response){
      total = response;
    }
  });
}

function getOptionButton(){
  $.ajax({
    type: 'POST',
    url: 'options_button.php',
    dataType: 'json',
    success: function(response){
      $('#optionsbutton').html(response);
    }
  });
}

</script>
