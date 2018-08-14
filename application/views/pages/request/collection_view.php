<div class="space-6"></div>

<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <form id="collection-form" action="<?php echo site_url('collection/insert/'.$sales_order_id.'/'.$stage_type); ?>" method="post" enctype="multipart/form-data">
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
            
                <?php if($stage_type=='request'): ?>
            
                <div class="row">
                    <div class="col-md-6">
                        Collection Receipt:
                        <input type="text" value="<?php echo (isset($cr_no)? $cr_no : ''); ?>" class="form-control" onkeypress="return isNumber(event);" name="cr_no" />
                    </div>
                    <div class="col-md-6">
                        Collection Date:
                        <input type="text" value="<?php echo (isset($collect_date)? date('m/d/Y', strtotime($collect_date)) : ''); ?>" readonly class="form-control date-picker" name="collect_date" />
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
                
                <?php if($stage_type=='view'): ?>
                <div class="widget-toolbar no-border invoice-info" style="float: left;">
                    <span class="invoice-info-label">CR No:</span>
                    <span class="red">#<?php echo (isset($cr_no)? $cr_no : 'Not Available'); ?></span>

                    <br />
                    <span>Collection Date:</span>
                    <span class="blue"><?php echo (isset($collect_date)? date('m/d/Y', strtotime($collect_date)) : 'Not Available'); ?></span>
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
                    <div class="row">
                        <div class="col-md-12" align="right">
                        <div class="hr dotted"></div>
                            <?php if($stage_type=='request'): ?>
                                <button data-action="add" class="btn btn-primary add-payment" data-value="<?php echo $sales_order_id; ?>"><i class="fa fa-plus"></i> Add Payment</button>
                                <div class="hr dotted"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="space"></div>
                    <h3>Payment List</h3>
                    <div>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="center">#</th>
                                    <th>Cheque No./Approval Code</th>
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

                    <?php if($stage_type=='request'): ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <h4><i class="fa fa-comment"></i> Comments:</h4>
                                <textarea name="comments" placeholder="Write your comment here. . ." rows="5" class="form-control"><?php echo (isset($comments)?$comments:''); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="hr dotted"></div>
                    <div class="row">
                        <div class="col-md-12" align="right">
                            <a href="<?php echo site_url('pages/collection'); ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> Back</a>
                            <button onclick="$('#collection-form')[0].submit(); return false;" class="btn btn-primary submit-collection"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="row">
                        <h3><i class="fa fa-comment"></i> Message</h3>
                        <div class="well">
                            <?php echo (isset($comments)?$comments:'No Message'); ?>
                        </div>    
                    </div>
                    <div class="hr dotted"></div>
                    <div class="row">
                        <div class="col-md-12" align="right">
                            <a href="<?php echo site_url('pages/collection'); ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> Back</a>
                        </div>
                    </div>
                    
                    <?php endif; ?>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>


<div id="collectionModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Payment Form</h5>
      </div>
      <div class="modal-body">
        
        <div class="row">
            <div class="col-md-12">
                <div class="collection-message"></div>
            </div>
        </div>
        
        <div class="loading-notification" align="center" hidden>
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
                                <option value="4">Cash Deposit</option>
                                <option value="5">PDC Deposit</option>
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
                    <label>Upload the payment image</label>
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
        <?php if($stage_type!='view'):?>
        <button class="btn btn-primary" data-value="<?php echo $sales_order_id; ?>" id="submit-payment">Submit</button>
        <?php endif; ?>
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
            <div class="loading-notification" align="center" hidden>
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
<script src="<?php echo site_url(); ?>public/js/collection.js"></script>

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