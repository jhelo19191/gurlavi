<div class="space-6"></div>

<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <?php if($stage_type=='request'):?>
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
            
                <?php if(!isset($cr_no)): ?>
            <form class="collection-form" action="<?php echo site_url('rcollection/insert/'.$sales_order_id.'/'.$stage_type); ?>" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        Collection Reciept:
                        <input type="text" class="form-control" onkeypress="return isNumber(event);" name="cr_no" />
                    </div>
                    <div class="col-md-6">
                        Collection Date:
                        <input type="text" readonly class="form-control date-picker" name="collect_date" />
                    </div>
                </div>
            <?php endif; ?>
            <div class="hr dottded"></div>
        <?php endif; ?>
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
                
                <?php if(isset($cr_no)): ?>
                <div class="widget-toolbar no-border invoice-info" style="float: left;">
                    <span class="invoice-info-label">CR No:</span>
                    <span class="red">#<?php echo $cr_no; ?></span>

                    <br />
                    <span>Collection Date:</span>
                    <span class="blue"><?php echo date('m/d/Y', strtotime($collect_date)); ?></span>
                </div>
                <?php endif; ?>
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
                <div class="row">
                    <div class="col-md-12" align="right">
                    <div class="hr dotted"></div>
                        <?php if($stage_type=='request' && $status_no=='3'): ?>
                            <button data-action="add" class="btn btn-primary add-payment" data-value="<?php echo $sales_order_id; ?>"><i class="fa fa-plus"></i> Add Payment</button>
                        <?php endif; ?>
                    <div class="hr dotted"></div>
                    </div>
                </div>
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
                    </div>

                    <div class="hr dotted"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <h4><i class="fa fa-comment"></i> Comments:</h4>
                                <textarea name="comments" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    
            </form>
                    <div class="hr dotted"></div>
<!--                    <div class="well">-->
<!--                        Thank you for choosing Ace Company products.-->
<!--We believe you will be satisfied by our services.-->
<!--                    </div>-->
                    <div class="row">
                        <div class="col-md-12" align="right">
                            <a href="<?php echo site_url('pages/rcollection'); ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> Back</a>
                            <!--<button onclick="$('.collection-form').submit(); return false;" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>-->
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
                    <input name="files" class="upload-images" type="file" id="id-input-file-3" />
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
<div id="deleteModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Notification Box</h4>
      </div>
      <div class="modal-body" align="center">
        <form id="removeRecord">
            <input type="hidden" class="tid" name="tid" />
            <input type="hidden" class="dtc" name="dtc" />
            <input type="hidden" class="pyt" name="pty" />
            <div class="loading" align="center" hidden>
                <i class="fa fa-spinner fa-spin" style="font-size:34px"></i>
            </div>
            <div class="message">
                <h3><strong>
                    Are you sure you want to delete this record?
                </strong>
            </div></h3>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger close-modal" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" data-value="<?php echo $sales_order_id; ?>" id="submit-remove">Submit</button>
      </div>
    </div>

  </div>
</div>
<script>
    $(function(){
        var main = "gurlavi";
        var main_path =  window.location.protocol+"//"+window.location.hostname+"/"+main;
        
        $('.show-delete').on('click',function(e){
            e.preventDefault();
            
            var _modal = $('#deleteModal');
            var _data = $(this).attr('data-value');
            _split = _data.split("_");
            var _payment_type = _split[0];
            var _dtc = _split[1];
            var _tid = _split[2];
            
            $('.tid').val(_tid);
            $('.dtc').val(_dtc);
            $('.pyt').val(_payment_type);
            
            _modal.modal({
                            'backdrop' : 'static',
                            'keyboard' : false
                        });
            _modal.modal('show');
            
        });
        
        $('.add-payment').on('click',function(){
            $('.modal-title').html('Payment Form');
            $('#payment-generate').prop('hidden',false);
            $('.view-type').prop('hidden',true);
            $('.image-container').prop('hidden',false);
            $('.image-preview').html('');
            $('.reject-comments-response').html('');
            $('.fields').html('');
            $('.activity').val($(this).attr('data-action'));
            
            return false;
        });
        
        $('.show-update').on('click',function(e){
            e.preventDefault();
            
            var _modal = $('#collectionModal');
            var _data = $(this).attr('data-value');
            _split = _data.split("_");
            var _payment_type = _split[0];
            var _dtc = _split[1];
            var _tid = _split[2];
            
            $('#payment-form').prop('hidden',true);
            $('.loading').prop('hidden',false);
            
            $('.modal-title').html('Modify Payment Information');
            $('#submit-payment').prop('hidden',true);
            $('#submit-update').prop('hidden',false);
            $('.activity').val($(this).attr('data-action'));
            
            $('#payment-generate').prop('hidden',true);
            _modal.modal({
                            'backdrop' : 'static',
                            'keyboard' : false
                        });
            _modal.modal('show');
            
            _activity(_payment_type,_dtc,_tid,'modify');
        });
        
        $('.close-modal').on('click',function(){
            location.reload();
        });
        
        $('.show-view').on('click',function(){
            var _modal = $('#collectionModal');
            var _data = $(this).attr('data-value');
            var _pid = $(this).attr('data-holder');
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
            
            _activity(_payment_type,_dtc,_tid,'view',_pid);
            
            return false;
        });
        
        
        var _activity = function activity(_payment_type,_dtc,$tid,$activity_type,$pid=''){
            $.ajax({
                type:'POST',
                url:main_path+'/generate/'+$activity_type,
                dataType:'json',
                data:{'payment_type':_payment_type,'dtc':_dtc,'tid':$tid,'activity':$activity_type,'pid':$pid},
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
        
        //$('#submit-payment').on('click',function(){
        //    var _serialize = $('#invoiceForm').serializeArray();
        //    
        //});
        
        $('.generate').on('click',function(){
            var _payment_type = $("#payment-type").val();
            $('#invoiceForm').prop('hidden',true);
            $('.loading').prop('hidden',false);
            $('#payment-generate').prop('hidden',true);
            $('#image-container').prop('hidden',false);
            
            if (_payment_type==='1') {
                $('.div-terms').prop('hidden',true);
            } else {
                $('.div-terms').prop('hidden',false);
            }
            
            fields(_payment_type);
        });
        
        var fields = function(_payment_type){
            
            $.ajax({
                type:'POST',
                url:main_path+'/generate/fields',
                dataType:'json',
                data:{'payment_type':_payment_type},
                success:function(data){
                    $('.fields').html(data.fields);
                    
                    restrictNumber($('.cash-amount'));
                    
                    restrictNumber($('.account_number'));
                    restrictNumber($('.check_number'));
                    restrictNumber($('.pdc_amount'));
                    
                    restrictNumber($('.card_no'));
                    restrictNumber($('.approval_code'));
                    restrictNumber($('.batch_no'));
                    restrictNumber($('.credit_card_amount'));
                    
                    $('#payment-generate').prop('hidden',false);
                    $('.loading').prop('hidden',true);
                    $('#invoiceForm').prop('hidden',false);
                    //$('#image-container').prop('hidden',false);
                    $('.date-picker').datepicker();
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
        
        $('.add-payment').on('click',function(){
            var _data = $(this).attr('data-value');
            var _modal = $('#collectionModal');
            
            $('#save-delivery').attr('data-value',_data);
            
            _modal.modal({ 'backdrop' : 'static', 'keyboard' : false });
            
            _modal.modal('show');
        });
        
        $('#submit-payment').on('click',function(){
            var _serialize = $('#invoiceForm').serializeArray();
            var _data = $(this).attr('data-value');
            var _payment_type = $('#payment-type').val();
            var _terms = $('.terms').val();
            
            var fdata = new FormData();
            
            fdata.append('sales_order_id',_data);
            fdata.append('payment_type',_payment_type);
            fdata.append('terms',_terms);
            
            $.each(_serialize, function(i,fields){
              fdata.append(fields.name,fields.value);
            });
        
            for($i=0;$i<$('.upload-images')[0].files.length;$i++){
                fdata.append('files'+$i,$('.upload-images')[0].files[$i]);
            }
            
            console.log(_serialize);
            
            if ($('.activity').val()==="update") {
                
                $.ajax({
                    type:'POST',
                    url:main_path+'/rcollection/update',
                    data:fdata,
                    contentType: false,
                    processData: false,
                    cache: false,
                    dataType:'json',
                    success:function(data){
                        var _color = 'danger';
            
                        if (data.result=='success') {
                            _color='success';
                        }
                        bnotify(data,_color);
                    }
                });
                
            } else {
                $.ajax({
                    type:'POST',
                    url:main_path+'/rcollection/payment',
                    data:fdata,
                    contentType: false,
                    processData: false,
                    cache: false,
                    dataType:'json',
                    success:function(data){
                        var _color = 'danger';
            
                        if (data.result=='success') {
                            _color='success';
                        }
                        bnotify(data,_color);
                    }
                });    
            }
            
            
            
            return false;
        });
        
        $('#submit-remove').on('click',function(){
            $(this).prop('hidden',true);
            $('.loading').prop('hidden',false);
            $('.close-modal').prop('hidden',true);
            $('.message').prop('hidden',true);
            
            var _serialize = $('#removeRecord').serialize();
            
            $.ajax({
                type : 'post',
                url : main_path+'/rcollection/remove',
                dataType:'json',
                data:_serialize,
                success:function(data){
                    var _color = 'success';
                    $(this).prop('hidden',false);
                    $('.loading').prop('hidden',true);
                    $('.close-modal').prop('hidden',false);
                    $('.message').prop('hidden',false);
                    if (data.result==='fail') {
                        _color = 'danger';
                    }else{
                        $('#deleteModal').modal('hide');
                    }
                    
                    bnotify(data,_color);
                }
            });
            
        });
        
        var bnotify = function notification($data,$alert) {
            $.notify('<strong>'+$data.message+'</strong>', {
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