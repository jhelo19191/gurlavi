<div class="space-6"></div>

<div class="row">
    <div class="col-sm-10 col-sm-offset-1">

        <div class="widget-box transparent">
            <div class="widget-header widget-header-large">
                <?php if($this->session->userdata('user_level')==1 && $page_active=='request' || $this->session->userdata('user_level')==0 && $page_active=='request'): ?>
                <button class="btn btn-primary request-invoice" data-value="<?php echo $segment; ?>"><i class="fa fa-flag"></i> Invoice</button>
                <button class="btn btn-danger cancel-invoice" data-value="<?php echo $segment; ?>"><i class="fa fa-times"></i> Cancel</button>
                <?php endif; ?>
                <div class="widget-toolbar no-border invoice-info">
                    <span class="invoice-info-label">SO Number:</span>
                    <span class="red">#<?php echo $sales_order_no; ?></span>

                    <br />
                    <span class="invoice-info-label">Date:</span>
                    <span class="blue"><?php echo date('m/d/Y', strtotime($so_date)); ?></span>
                </div>

                <div class="widget-toolbar hidden-480">
                    <a href="#">
                        <i class="ace-icon fa fa-print"></i>
                    </a>
                </div>
            </div>

            <div class="widget-body">
                <div class="widget-main padding-24">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                                    <b>Customer Info</b>
                                </div>
                            </div>

                            <div>
                                <ul class="list-unstyled  spaced">
                                    <li>
                                        
                                        <div class="row">
                                            <div class="col-md-5">
                                                <i class="ace-icon fa fa-caret-right green"></i> 
                                                Customer Name:
                                            </div>
                                            <div class="col-md-7">
                                                <strong><?php echo $customer_name; ?></strong>
                                            </div>
                                        </div>
                                        <!--<label>-->
                                        <!--</label>-->
                                    </li>

                                    <li>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <i class="ace-icon fa fa-caret-right green"></i> 
                                                Ship To:
                                            </div>
                                            <div class="col-md-7">
                                                <strong><?php echo $shipto; ?></strong>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="row">
                                            <div class="col-md-5">
                                        <i class="ace-icon fa fa-caret-right green"></i>
                                        Contact Number: 
                                            </div>
                                            <div class="col-md-7">
                                        <strong><?php echo $contact_no; ?></strong>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="divider"></li>

                                    <li>
                                        <div class="row">
                                            <div class="col-md-5">
                                        <i class="ace-icon fa fa-caret-right green"></i>
                                        TIN: 
                                            </div>
                                            <div class="col-md-7">
                                        <strong><?php echo $tin; ?></strong>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
                                    <b>Company Info</b>
                                </div>
                            </div>

                            <div>
                                <ul class="list-unstyled spaced">
                                    <li>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <i class="ace-icon fa fa-caret-right blue"></i> 
                                                PSR Name:
                                            </div>
                                            <div class="col-md-7">
                                                <strong><?php echo $psr_name; ?></strong>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <i class="ace-icon fa fa-caret-right blue"></i> 
                                                Approved Date: 
                                            </div>
                                            <div class="col-md-7">
                                                <strong><?php echo date('m/d/Y',strtotime($approved_date)); ?></strong>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <i class="ace-icon fa fa-caret-right blue"></i> 
                                                Delivery Date:
                                            </div>
                                            <div class="col-md-7">
                                                <strong><?php echo date('m/d/Y',strtotime($delivery_date)); ?></strong>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div><!-- /.col -->

                    </div><!-- /.row -->

                    <div class="space"></div>

                    <div>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="center">#</th>
                                    <th>Product</th>
                                    <th class="hidden-xs">Description</th>
                                    <th class="hidden-480">Quantity</th>
                                    <th class="hidden-480">Unit</th>
                                    <th class="hidden-480">Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php echo $items; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="hr hr8 hr-double hr-dotted"></div>

                    <div class="row">
                        <div class="col-sm-5 pull-right">
                            <h4 class="pull-right">
                                Total amount :
                                <span class="red">PHP <?php echo number_format($total)?></span>
                            </h4>
                        </div>
                        <!--<div class="col-sm-7 pull-left"> Extra Information </div>-->
                    </div>
                    <div class="row">
                        <h3 class="widget-title grey lighter">
                            <i class="ace-icon fa fa-comment orange"></i>
                            Message
                        </h3>
                    </div>
                    <div class="hr dotted"></div>
                    <div class="space-6"></div>
                    <div class="row">
                        <div class="well">
                            <?php echo $message; ?>
                        </div>    
                    </div>
                    <div class="attachement row">
                        <div class="col-md-12">
                            <div>
                                <h3><i class="fa fa-image"></i> Attachment</h3>
                                <div class="hr dotted"></div>
                                <ul class="ace-thumbnails clearfix">
                                    <?php echo $attachment; ?>
                                </ul>
                            </div><!-- PAGE CONTENT ENDS -->
                        </div>
                    </div>
                    <div class="hr dotted"></div>
                    <div class="row">
                        <div class="col-md-12" align="right">
                            <a href="<?php echo site_url('pages/index'); ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="invoiceModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Invoice Form</h5>
      </div>
      <div class="modal-body">
        <div class="invoice-message"></div>
        <form id="invoiceForm">
            <div class="form-group">
                <label>Invoice Number</label>
                <input type="text" onkeypress="return isNumber(event);" class="form-control" name="invoice_number" />
            </div>
            <div class="form-group">
                <label>Invoice Date</label>
                <input type="text" readonly class="form-control date-picker" placeholder="mm/dd/yyyy" name="invoice_date" />
            </div>
            <div class="form-group">
                <label>Upload Invoice Form</label>
                <input multiple="" name="files" class="upload-images" type="file" id="id-input-file-3" />
            </div>
            <div class="form-group">
                <i class="fa fa-comment"></i> <label>Comment</label>
                <textarea class="form-control" name="comment" placeholder="Write your comment here. . ."></textarea>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-invoice">Submit</button>
      </div>
    </div>
  </div>
</div>
<div id="deleteModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Notification Box</h4>
      </div>
      <div class="modal-body" align="center">
        <form id="removeRecord">
            <div class="message">
                <h4><strong>
                    Are you sure you want to delete this record?
                </strong>
            </div></h4>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger close-modal btn-close" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" data-value="<?php echo $segment; ?>" id="submit-remove">Submit</button>
      </div>
    </div>

  </div>
</div>
<script src="<?php echo site_url(); ?>public/js/invoice.js"></script>
<script>
    $(function(){
        var main = "gurlavi";
        var main_path =  window.location.protocol+"//"+window.location.hostname+"/"+main;
        
        $('.cancel-invoice').on('click',function(){
            var _modal = $('#deleteModal');
            $('.modal-body').attr('class','modal-body');
            var _html = '';
            _html +=  '<h4>';
            _html +=  '     <strong>';
            _html +=  '          Are you sure you want to delete this record?';
            _html +=  '     </strong>';
            _html +=  '</h4>';
            $('.message').html(_html);
            
            _modal.modal({
                    'backdrop' : 'static',
                    'keyboard' : false
                });
            _modal.modal('show');
        });
        
        $('#submit-remove').on('click',function(){
            var _modal_loading = $('#showLoadingModal');
            var _data_value = $(this).attr('data-value');
            var _html = '';
            
            _modal_loading.modal({
                    'backdrop' : 'static',
                    'keyboard' : false
                });
            _modal_loading.modal('show');
            
            $('#deleteModal').css('z-index','1');
            
            $.ajax({
                type:'POST',
                url:main_path+'/invoice/cancelSalesOrder',
                dataType:'json',
                data:{'so_id':_data_value},
                success:function(data){
                    
                    if (data.result=='fail') {
                        $('.modal-body').attr('class','modal-body alert-danger');
                    } else {
                        $('.modal-body').attr('class','modal-body alert-success');
                    }
                    
                    _html +=  '<h4>';
                    _html +=  '     <strong>';
                    _html +=            data.message;
                    _html +=  '     </strong>';
                    _html +=  '</h4>';
                    
                    $('.message').html(_html);
                    $('#deleteModal').css('z-index','1999');
                    _modal_loading.modal('hide');
                }
            });
            
        });
    });
</script>
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