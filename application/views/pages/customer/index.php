<div class="row">
    <form id="regProductForm" action="<?php echo (isset($activity) && $activity=="update"? site_url('customer/update/'.$segment): site_url('customer/insert')); ?>" method="POST">
        <div class="col-md-4"></div>
        <div class="col-md-4">
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
            <?php endif; ?>
            <?php if(isset($activity) && $activity=="update"): ?>
            <input type="hidden" value="<?php echo $segment; ?>" name="customer_id"/>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Customer Name</label>
                        <input type="text" class="form-control" <?php echo (isset($customer_name)? "value='".$customer_name."'":"") ?> name="customer_name" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Address</label>
                        <textarea class="form-control" name="address" rows="5"><?php echo (isset($address)? $address:"") ?></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" onkeypress="return isNumber(event)" class="form-control" <?php echo (isset($contact_no)? "value='".$contact_no."'":"") ?> name="contact_no" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>TIN Number</label>
                        <input type="text" onkeypress="return isNumber(event)" class="form-control" <?php echo (isset($tin)? "value='".$tin."'":"") ?> name="tin" />
                    </div>
                 </div>
            </div>
            <div class="hr dotted"></div>
            <div class="row">
                <div class="col-md-12">
                    <div align="right">
                        <a href="<?php echo site_url('pages/customers'); ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> Back</a>
                        <button class="btn btn-primary" onclick="$('#regProductForm').submit(); return false;"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4"></div>
        </div>    
    </form>
    
</div>
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