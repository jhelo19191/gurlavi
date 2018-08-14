<div class="space-6"></div>

<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <div class="widget-box transparent">
            <div class="widget-header widget-header-large">
                <?php if($stage_type=='request'): ?>
                    <button class="btn btn-primary request-delivery" data-value="<?php echo $sales_order_id; ?>"><i class="fa fa-plus"></i> Delivered Form</button>
                <?php endif; ?>
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
                                Invoice Amount :
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
                        <div align="right">
                            <a class="btn btn-info" href="<?php echo site_url('pages/delivery'); ?>"><i class="fa fa-angle-left"></i> Back</a>
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
        <h5 class="modal-title">Delivered Form</h5>
      </div>
      <div class="modal-body">
        <div class="delivery-message"></div>
        <form id="invoiceForm">
            <div class="form-group">
                <label>Actual Delivery Date</label>
                <input type="text" class="form-control date-picker" readonly name="actual_delivery_date" />
            </div>
            <div class="form-group">
                <label>Upload delivery form with sign</label>
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
        <button type="button" class="btn btn-primary" id="save-delivery">Submit</button>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo site_url(); ?>public/js/delivery.js"></script>

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