
<div class="col-sm-10 col-sm-offset-1">
            <?php if (isset($result)) :?>
                <div style="  margin-left: -3px;" class="span12">
                        <?php if ($result == 'fail'): ?>
                                <div class="alert alert-danger">
                                        <strong> <?php echo $message; ?> </strong>
                                </div>
                        <?php else : ?>
                                <div class="alert alert-success">
                                <strong><?php echo $message; ?></strong>
                                </div>
                        <?php endif; ?>
                </div>
                <div class="hr dotted"></div>
            <?php endif; ?>
</div>
<div class="space-6"></div>
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <div class="widget-toolbar no-border invoice-info" style="float: left;">
            <span>Remittance No:</span>
                <span class="red">#<?php echo $remittance_no; ?></span>

            <br />
            <span>Remittance Date:</span>
                <span class="blue"><?php echo date('m/d/Y', strtotime($remittance_date)); ?></span>
        </div>
    </div>
</div>
    
<div class="hr dotted"></div>

<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <div class="widget-box transparent">
        
            <div class="widget-header widget-header-large">
                <div class="widget-toolbar no-border invoice-info">
                    <span class="invoice-info-label">Invoice No:</span>
                    <span class="red">#<?php echo $invoice_number; ?></span>

                    <br />
                    <span class="invoice-info-label">Date:</span>
                    <span class="blue"><?php echo date('m/d/Y', strtotime($invoice_date)); ?></span>
                </div>

                <div class="widget-toolbar hidden-480">
                    <a href="#">
                        <i class="ace-icon fa fa-print"></i>
                    </a>
                </div>
                
                <div class="widget-toolbar no-border invoice-info" style="float: left;">
                    <span class="invoice-info-label">CR No:</span>
                    <span class="red">#<?php echo $cr_no; ?></span>

                    <br />
                    <span>Collection Date:</span>
                    <span class="blue"><?php echo date('m/d/Y', strtotime($collect_date)); ?></span>
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

                                    <li>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <i class="ace-icon fa fa-caret-right blue"></i> 
                                                Actual Delivery Date:
                                            </div>
                                            <div class="col-md-7">
                                                <strong><?php echo date('m/d/Y',strtotime($actual_delivery_date)); ?></strong>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <i class="ace-icon fa fa-caret-right blue"></i> 
                                                Status:
                                            </div>
                                            <div class="col-md-7">
                                                <strong><?php echo $status; ?></strong>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div><!-- /.col -->

                    </div><!-- /.row -->
                    
                    <div class="space"></div>
                    <h3>Payment List</h3>
                    <div>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="center">#</th>
                                    <th>Payment Type</th>
                                    <th class="hidden-xs">Amount</th>
                                    <th class="hidden-480">Image</th>
                                    <th class="hidden-480">Payment Date</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                                
                            <tbody>
                                <?php echo $payment_list; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="hr dotted"></div>
                    <h3>Product List</h3>

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
                        <div class="col-md-4">
                            <h5>
                                Payment Amount :
                                <span class="red">PHP <?php echo number_format($total_amount)?></span>
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <h5>
                                Balance :
                                <span class="red">PHP <?php echo number_format(abs($total_amount - $total))?></span>
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <h5>
                                Invoice Amount :
                                <span class="red">PHP <?php echo number_format($total)?></span>
                            </h5>
                        </div>
                    </div>
                    <div class="hr dotted"></div>
                    <div class="row">
                        <div class="col-md-4">
                            <h5>
                                Pending Amount :
                                <span class="red">PHP <?php echo number_format($total_amount)?></span>
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <h5>
                                Remitted Amount :
                                <span class="red">PHP <?php echo number_format($total_amount)?></span>
                            </h5>
                        </div>
                    </div>
                    <!--<div class="hr dotted"></div>-->
                    <!---->
                    <!--<div class="row">-->
                    <!--    <h3><i class="fa fa-comment"></i> Message</h3>-->
                    <!--    <div class="well">-->
                    <!--        -->
                    <!--    </div>    -->
                    <!--</div>-->
                    <div class="hr dotted"></div>
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="row">
                                    <div class="col-md-6">
                                        <select class="form-control" name="report" aria-describedby="basic-addon2">
                                            <option class="pdf">PDF</option>
                                            <option class="excel">Excel</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i> Generate R3</button>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="collectionModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Payment Form</h5>
      </div>
      <div class="modal-body">
            
        <div class="loading" align="center" hidden>
            <i class="fa fa-spinner fa-spin" style="font-size:34px"></i>
        </div>
            <div class="row view-type" hidden></div>
                
                <div class="row" id="payment-generate">
                    
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        <div class="div-terms" hidden>
                            <input type="text" placeholder="Terms" class="form-control terms" name="terms" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control" name="payment_type" id="payment-type">
                                <option value="1">Cash</option>
                                <option value="2">PDC</option>
                                <option value="3">Credit Card</option>
                            </select>
                            <!--<input type="text" class="form-control search-query" placeholder="Type your query">-->
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-sm btn-primary generate">
                                    <!--<span class="ace-icon fa fa-search icon-on-right bigger-110"></span>-->
                                    Generate
                                </button>
                            </span>
                        </div>
                    </div>
                </div>    
        <form id="invoiceForm">
            <input type="hidden" name="activity" class="activity" />
            <div class="hr dotted"></div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="fields"></div>
                    </div>
                </div>
                
            <div class="uploads" id="image-container" hidden>
                <div class="form-group">
                    <label>Upload delivery form with sign</label>
                    <input multiple="" name="files" class="upload-images" type="file" id="id-input-file-3" />
                </div>
                <div class="form-group">
                    <i class="fa fa-comment"></i> <label>Comment</label>
                    <textarea class="form-control" name="comment" placeholder="Write your comment here. . ."></textarea>
                </div>
            </div>
            
            
            <div class="image-preview"></div>
            
            <div class="reject-comments-response"></div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
        <button class="btn btn-primary" data-value="<?php echo $sales_order_id; ?>" id="submit-payment">Submit</button>
        </div>
    </div>
  </div>
</div>
<div id="rejectModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-comment"></i> Comment Box</h4>
      </div>
      <div class="modal-body">
        <form id="statusRecord">
            <input type="hidden" class="soid" name="soid" />
            <input type="hidden" class="payment_type" name="payment_type" />
            <input type="hidden" class="action_type" name="action_type" />
            <input type="hidden" class="pid" name="pid" />
            <div class="loading" align="center" hidden>
                <i class="fa fa-spinner fa-spin" style="font-size:34px"></i>
            </div>
            <div class="container-message">
                <div class="form-group">
                   <textarea name="comments" rows="5" placeholder="Write your comments here. . ." class="form-control"></textarea>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger close-modal" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" data-value="<?php echo $sales_order_id; ?>" id="submit-comments">Submit</button>
      </div>
    </div>

  </div>
</div>
<div id="acceptModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <!--<div class="modal-content">-->
<!--      <div class="modal-header">
        <h4 class="modal-title">Comment Box</h4>
      </div>-->
      <!--<div class="modal-body">-->
        <form id="statusRecord">
            <input type="hidden" class="soid" name="soid" />
            <input type="hidden" class="payment_type" name="payment_type" />
            <input type="hidden" class="action_type" name="action_type" />
            <input type="hidden" class="pid" name="pid" />
            <div class="loading" align="center">
                <i class="fa fa-spinner fa-spin" style="font-size:34px"></i>
            </div>
        </form>
      <!--</div>-->
<!--      <div class="modal-footer">
        <button type="button" class="btn btn-danger close-modal" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" data-value="<?php echo $sales_order_id; ?>" id="submit-comments">Submit</button>
      </div>-->
    <!--</div>-->

  </div>
</div>
<script>
    $(function(){
        var main = "gurlavi";
        var main_path =  window.location.protocol+"//"+window.location.hostname+"/"+main;
        
        $('.close-modal').on('click',function(){
            location.reload();
        });
        
        $('#submit-comments').on('click',function(){
            var _serialize = $('#statusRecord').serialize();
            
            $.ajax({
                type : 'post',
                url : main_path+'/validation/rejectStatus',
                dataType:'json',
                data:_serialize,
                success:function(data){
                    var _color = 'success';
                    $(this).prop('hidden',false);
                    $('.loading').prop('hidden',true);
                    if (data.result==='fail') {
                        _color = 'danger';
                    }else{
                        setTimeout(function(){ location.reload(); },5000);
                    }
                    
                    bnotify(data,_color);
                }
            });
            
        });
        
        $('.show-reject').on('click',function(){
            var _soid = $(this).attr('data-target');
            var _payment_type = $(this).attr('data-access');
            var _pid = $(this).attr('data-value');
            var _action_type = $(this).attr('data-button');
            var _modal = $('#rejectModal');
            
            $('.soid').val(_soid);
            $('.payment_type').val(_payment_type);
            $('.pid').val(_pid);
            $('.action_type').val(_action_type);
            
            _modal.modal('show');
            
            
            return false;
        });
        
        $('.show-accept').on('click',function(){
            var _soid = $(this).attr('data-target');
            var _payment_type = $(this).attr('data-access');
            var _pid = $(this).attr('data-value');
            var _action_type = $(this).attr('data-button');
            
            var _modal_loading = $('#showLoadingModal');
            //var _html = '';
            _modal_loading.modal({
                    'backdrop' : 'static',
                    'keyboard' : false
                });
            _modal_loading.modal('show');
            
            $('.soid').val(_soid);
            $('.payment_type').val(_payment_type);
            $('.pid').val(_pid);
            $('.action_type').val(_action_type);
            
            var _serialize = $('#statusRecord').serialize();
            
            $.ajax({
                type : 'post',
                url : main_path+'/validation/acceptStatus',
                dataType:'json',
                data:_serialize,
                success:function(data){
                    var _color = 'success';
                    $(this).prop('hidden',false);
                    $('.loading').prop('hidden',true);
                    
                    if (data.result==='fail') {
                        _color = 'danger';
                    } else {
                        setTimeout(function(){ location.reload(); },5000);
                    }
                    
                    bnotify(data,_color);
                    
                    _modal_loading.modal('hide');
                }
            });
            
            return false;
        });
        
        $('.show-view').on('click',function(){
            var _modal = $('#collectionModal');
            var _data = $(this).attr('data-value');
            var _pdi = $(this).attr('data-holder');
            
            _split = _data.split("_");
            
            var _payment_type = _split[0];
            var _dtc = _split[1];
            var _tid = _split[2];
            
            $('#payment-generate').prop('hidden',true);
            $('.modal-title').html('Payment Information');
            $('#image-container').prop('hidden',true);
            
            $('.view-type').prop('hidden',false);
            
            $('.loading').prop('hidden',false);
            
            _modal.modal({
                            'backdrop' : 'static',
                            'keyboard' : false
                        });
            _modal.modal('show');
            
            _activity(_payment_type,_dtc,_tid,'view',_pdi);
            
            return false;
        });
        
        
        var _activity = function activity(_payment_type,_dtc,$tid,$activity_type,$pdi=''){
            $.ajax({
                type:'POST',
                url:main_path+'/generate/'+$activity_type,
                dataType:'json',
                data:{'payment_type':_payment_type,'dtc':_dtc,'tid':$tid,'activity':$activity_type,'pid':$pdi},
                success:function(data){
                    $('.fields').html(data.fields);
                    $('.image-preview').html(data.images);
                    
                    $('.reject-comments-response').html("<hr>"+data.comments);
                    $('.view-type').html('<div class="col-md-6">Payment Type:<p><strong>'+data.payment_type+'</strong></p></div><div class="col-md-6">Created Date:<p><strong>'+data.created_date+'</strong></p></div>').prop('hidden',false);
                    $('.date-picker').datepicker();
                    
                    restrictNumber($('.cash-amount'));
                    
                    restrictNumber($('.account_number'));
                    restrictNumber($('.check_number'));
                    restrictNumber($('.pdc_amount'));
                    
                    restrictNumber($('.card_no'));
                    restrictNumber($('.approval_code'));
                    restrictNumber($('.batch_no'));
                    restrictNumber($('.credit_card_amount'));
                    if ($activity_type=="view") {
                        $('#image-container').prop('hidden',true);
                    } else {
                        $('#image-container').prop('hidden',false);
                    }
                    
                    $('#payment-form').prop('hidden',false);
                    $('.loading').prop('hidden',true);
                }
            });
        }
        
        var restrictNumber = function($class){
            $($class).keydown(function(e){
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                     // Allow: Ctrl+A, Command+A
                    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
                     // Allow: home, end, left, right, down, up
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                         // let it happen, don't do anything
                         return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        }
        
        var bnotify = function notification($data,$alert) {
            $.notify('<strong>'+$data.message+'</strong>. In 5 seconds the page will be refreshed.', {
                allow_dismiss: true,
                type:$alert,
                z_index: 5000,
                placement: {
                    from: "top",
                    align: "center"
                },
            }); 
        }
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