<form id="SalesOrderForm" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-12">
			<div class="message-sales-order"></div>
		</div>
	</div>
  <div class="row">
  <div class="col-md-5">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
    			<label class="control-label" for="form-field-1"> Sales Order Number </label>

    				<input type="text" id="form-field-1" onkeypress="return isNumber(event)" placeholder="000000000000" maxlength="12" name="sales_order_no" class="form-control">
    		</div>
      </div>
      <div class="col-md-6">
       <!-- <div class="form-group">
    			<label class="control-label" for="form-field-2"> Sales Invoice Number </label>

    				<input type="text" id="form-field-2" onkeypress="return isNumber(event)" name="invoice_number" placeholder="0000000" maxlength="12" class="form-control">
    		</div>-->
      </div>
    </div>
    <div class="row">
        <div class="col-md-6">
          <label class="control-label" for="form-field-3"> Sales Order Date </label>
          <div class="input-group">
						<input class="form-control date-picker" readonly name="so_date" id="id-date-picker-1" type="text" data-date-format="dd-mm-yyyy">
						<span class="input-group-addon">
							<i class="fa fa-calendar bigger-110"></i>
						</span>
					</div>
        </div>
        <div class="col-md-6">
            <label class="control-label" for="form-field-4"> Approved Date </label>
          <div class="input-group">
				<input class="form-control date-picker" readonly name="approve_date" id="id-date-picker-2" type="text" data-date-format="dd-mm-yyyy">
				<span class="input-group-addon">
					<i class="fa fa-calendar bigger-110"></i>
				</span>
			</div>
        </div>
    </div>
	<br>
    <div class="row">
      <div class="col-md-12">
		<div class="input-form">
			<i class="fa fa-flag"></i> <label>Ship-To:</label>
			<textarea class="form-control" name="shipto" placeholder="Address"></textarea>
		</div>
      </div>
    </div>
	
	<br>
    <div class="row">
      <div class="col-md-12">

          <div class="form-group">
              <input multiple="" name="files" class="upload-images" type="file" id="id-input-file-3" />
          </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <i class="fa fa-comment"></i> <label>Comment:</label>
        <textarea class="form-control" name="comment" placeholder="Write your comment here. . ."></textarea>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
			<label class="control-label" for="form-field-5"> PSR Name </label>

			<input type="text" id="form-field-5" name="psr_name" placeholder="e.g. Jaun dela Cruz" class="form-control typeahead scrollable" />
		</div>
      </div>
      <div class="col-md-4">
        <!--<div class="form-group">-->
			<label class="control-label" for="form-field-6"> Customer Name </label>
			
			<select class="form-control" name="customer_name">
				<?php echo $options_customer; ?>
			</select>
		<!--	<input type="text" autocomplete="off" placeholder="e.g. Maria Clara" class="form-control customer_name" name="customer_name" id="customer-list" />
			<input type="hidden" class="form-control" name="customer_id" id="customer-id" /> -->
		<!--</div>-->
      </div>
      <div class="col-md-4">
        <div class="form-group">
    			<label class="control-label" for="form-field-7"> Delivery Date </label>
          <div class="input-group">
            <input class="form-control date-picker" readonly name="delivery_date" id="id-date-picker-2" type="text" data-date-format="dd-mm-yyyy">
            <span class="input-group-addon">
              <i class="fa fa-calendar bigger-110"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="hr dotted"></div>
        <div style="text-align:right;">
          <button class="btn btn-primary register-product"><i class="fa fa-plus"></i> Add Product</button>
        </div>
        <div class="hr dotted"></div>
        <div class="table table-responsive" style="height: 230px;">
          <table id="simple-table" class="table  table-bordered table-hover">
            <thead>
              <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th class="hidden-480">Unit</th>

                <th>
                  <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
                  Price
                </th>
                <th>Total Price</th>
                <th></th>
              </tr>
            </thead>

            <tbody class="td-record" style="overflow-y: scroll;">
              <tr>

                <td colspan="7" align="center">
                  No Data Found!
                </td>
              </tr>

            </tbody>
          </table>
        </div>
		<div class="total-net-price" align="right"></div>
      </div>
    </div>
  </div>
</div>
<div class="hr dotted"></div>
<div class="row">
  <div class="col-md-12">
      <div style="text-align:right;">
        <a href="<?php echo site_url('pages/index'); ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> Back</a>
        <button id="submit-sales-order" class="btn btn-primary"><i class="fa fa-send"></i> Submit</button>
      </div>
  </div>
</div>
</form>

<!-- Modal -->
<div id="addProducts" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Register Product</h4>
      </div>
      <div class="modal-body">
		<div class="message-order"></div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
				<label>Product Name</label>	
				<select class="form-control" id="product-id">
					<?php echo $options_product; ?>
				</select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Quantity</label>
              <input type="text" autocomplete="off" onkeypress="return isNumber(event)" name="quantity" class="form-control quantity" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Unit of Measure</label>
			  <select class="form-control unit" name="unit">
				<option>Box</option>
				<option>Piece</option>
				<option>Kit</option>
				<option>Package</option>
			  </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Price</label>
              <input type="text" autocomplete="off" onkeypress="return isNumber(event)" name="price" class="form-control price" />
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary create-product">Submit</button>
      </div>
    </div>

  </div>
</div>
<script src="<?php echo site_url(); ?>public/js/autocomplete.js"></script>
<script>
	var main = "gurlavi";
	var main_path =  window.location.protocol+"//"+window.location.hostname+"/"+main;
	
	autocompleteCustomer();
	autocompleteProducts();
	
	function autocompleteCustomer() {
		$.get(main_path+'/customer/searchName',function(data){
			var _obj = JSON.parse(data);
			console.log(_obj);
			autocomplete(document.getElementById("customer-list"), _obj, 'customer_id', 'customer_name');
		});
	}
	
	function autocompleteProducts() {
		$.get(main_path+'/products/showProductList',function(data){
			var _obj = JSON.parse(data);
			
			autocomplete(document.getElementById("product_name"), _obj, 'product_id', 'product_name');
		});
	}
</script>
<script src="<?php echo site_url(); ?>public/js/sales_order.js"></script>
<script>
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
</script>
