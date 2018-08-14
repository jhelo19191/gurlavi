<div class="row">
  <div class="col-md-5">
    <?php if($this->session->userdata('user_level')==0 || $this->session->userdata('user_level')==1): ?>
    <button href="<?php echo site_url('customer/index'); ?>" class="btn btn-primary add-payment" /><i class="fa fa-plus"></i> Add Payment</button>
    <?php endif; ?>
  </div>
  <div class="col-md-7">
    <div class="row">
      <div class="col-md-5">
      </div>
      <div class="col-md-7">
        <form id="product-search" action="<?php echo site_url('pages/onhold'); ?>" method="post">
            <div class="input-group">
                <input class="form-control search-query" name="search" placeholder="Type your query" type="text">
                <span class="input-group-btn">
                  <button type="button" class="btn btn-inverse btn-white" onclick="$('#product-search').submit(); return false;">
                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                    Search
                  </button>
                </span>
            </div>   
        </form>
        
      </div>
    </div>

  </div>
</div>
<div class="hr"></div>
<div class="row">
  <div class="col-xs-12">
        <table id="simple-table" class="table  table-bordered table-hover">
          <thead>
            <tr>
              <th class="center">
                #
              </th>
              <th>Receiver Name</th>
              <th>Payment Type</th>
              <th>Amount</th>
              <th>Created By</th>
              <th>Update Date</th>
              <th>Created Date</th>
              <?php if($this->session->userdata('user_level')==0 || $this->session->userdata('user_level')==1): ?>
              <th></th>
              <?php endif; ?>
            </tr>
          </thead>

          <tbody>
            <tr>
                <?php echo $html; ?>
            </tr>
          </tbody>
        </table>
      </div>
</div>

<div id="addPaymentModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Payment Form</h4>
      </div>
      <div class="modal-body">
        <div id="message-onhold"></div>
        <form id="onhold-form">
            <input type="hidden" name="activity" class="form-control activity" />
            <input type="hidden" name="tid" class="form-control tid" />
            <div class="form-group">
                <label>Receiver Name</label>
                <select class="form-control" name="receiver_name" id="form-field-select-3" data-placeholder="Choose a PSR...">
                    <option value="">  </option>
                    <?php echo $options; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Payment Type</label>
                <select class="form-control" name="payment_type" id="payment_type" data-placeholder="Choose a Payment...">
                    <option value="">  </option>
                    <option value="cash">Cash</option>
                    <option value="pdc">PDC</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="bank_transfer">Bank Transfer</option>
                </select>
            </div>
            <div class="form-group">
                <label>Amount</label>
                <input type="number" class="form-control" id="amount" name="amount" />
            </div>
            <div class="form-group">
                <label>Created By</label>
                <input type="text" class="form-control" id="created_by" name="created_by" readonly value="<?php echo $this->session->userdata('account_name'); ?>"/>
            </div>
        </form>
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submit-onhold"><i class="fa fa-send"></i> Submit</button>
      </div>
    </div>

  </div>
</div>
<div id="showDeleteModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Confirmation Box</h4>
      </div>
      <div class="modal-body" align="center">
        <form id="remove-form">
            <input type="hidden" name="activity" class="form-control activity" />
            <input type="hidden" name="tid" class="form-control tid" />
        </form>
        <h4 id="message-delete">Are you sure you want to remove this record?</h4>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="remove-onhold"><i class="fa fa-times"></i> Remove</button>
      </div>
    </div>

  </div>
</div>

<script src="<?php echo site_url(); ?>public/js/onhold.js"></script>