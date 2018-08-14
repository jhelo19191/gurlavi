<div class="row">
    <form id="regProductForm" action="<?php echo (isset($activity) && $activity=="update"? site_url('products/update/'.$segment): site_url('products/insert')); ?>" method="POST">
        <div class="col-md-3"></div>
        <div class="col-md-6">
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
            <input type="hidden" value="<?php echo $segment; ?>" name="product_id"/>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-12">
                    <label>Product Name</label>
                    <input type="text" class="form-control" <?php echo (isset($product_name)? "value='".$product_name."'":"") ?> name="product_name" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="5"><?php echo (isset($description)? $description:"") ?></textarea>
                </div>
            </div>
            <div class="hr dotted"></div>
            <div class="row">
                <div class="col-md-12">
                    <div align="right">
                        <a href="<?php echo site_url('pages/products'); ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> Back</a>
                        <button class="btn btn-primary" onclick="$('#regProductForm').submit(); return false;"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
        </div>    
    </form>
    
</div>