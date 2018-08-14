<div class="space-6"></div>
<form action="<?php echo site_url('check/insert/'.$sales_order_id.'/request'); ?>" method="post" class="form-submit">
    <input type="hidden" value="<?php echo $sales_order_id; ?>" name="sales_order_id" />
</form>
<div class="row">
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
                                    <th>Check No./Approval Code</th>
                                    <th>Payment Type</th>
                                    <th class="hidden-xs">Amount</th>
                                    <!--<th class="hidden-480">Image</th>-->
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
                    <div class="hr dotted"></div>
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
                            <?php echo $comments; ?>
                        </div>    
                    </div>
                    <div class="hr dotted"></div>
<!--                    <div class="well">-->
<!--                        Thank you for choosing Ace Company products.-->
<!--We believe you will be satisfied by our services.-->
<!--                    </div>-->
                    <div class="row">
                        <div class="col-md-12" align="right">
                            <a href="<?php echo site_url('pages/check'); ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> Back</a>
                            <?php if($stage_type=='request'): ?>
                                <button onclick="$('.form-submit').submit(); return false;" class="btn btn-primary"><i class="fa fa-send"></i> Submit</button>
                            <?php endif; ?>
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
        <!--<button class="btn btn-primary" data-value="<?php echo $sales_order_id; ?>" id="submit-payment">Submit</button>-->
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
        <div class="comment-message"></div>
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
<script src="<?php echo site_url(); ?>public/js/check.js"></script>

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