<!-- Edit -->
<div class="modal fade" id="edit_sales">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Edit Payment & Pickup Status</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="edit_sales.php">
                <input type="hidden" class="orderid" name="id">
                <div class="form-group">
                    <label for="edit_payment" class="col-sm-3 control-label">Payment Status</label>
                    <div class="col-sm-5">
                    <select class="form-control" id="edit_payment" name="edit_payment">  
                      <option value="Paid">Paid</option>
                      <option value="Pending">Pending</option>
                      <option value="Cancelled">Cancelled</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                      <label for="edit_pickup" class="col-sm-3 control-label">Pickup Status</label>

                      <div class="col-sm-5">
                        <select class="form-control" id="edit_pickup" name="edit_pickup">
                          <option selected id="edit_pickup"></option>
                          <option value="Collected">Collected</option>
                          <option value="Pending">Pending</option>
                          <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>      
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-success btn-flat" name="edit_sales"><i class="fa fa-check-square-o"></i> Update</button>
            </div>
              </form>
            </div>
        </div>
    </div>
</div>


