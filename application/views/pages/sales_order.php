<div class="col-xs-12">
  <div class="row">
    <div class="col-xs-12">
      <div class="tabbable">
      	<ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
			
			<li<?php echo ($page_type=="index"? ' class="active"' : ''); ?>>
      			<a href="<?php echo site_url('pages/index'); ?>">Sales Order</a>
      		</li>
			
      		<li<?php echo ($page_type=="invoice"? ' class="active"' : ''); ?>>
      			<a  href="<?php echo site_url('pages/invoice'); ?>">Invoice</a>
      		</li>
			
			<li<?php echo ($page_type=="inventory"? ' class="active"' : ''); ?>>
      			<a href="<?php echo site_url('pages/inventory'); ?>">Inventory Transfer</a>
      		</li>

      		<li<?php echo ($page_type=="delivery"? ' class="active"' : ''); ?>>
      			<a href="<?php echo site_url('pages/delivery'); ?>">Delivery</a>
      		</li>
			
			<li<?php echo ($page_type=="collection"? ' class="active"' : ''); ?>>
      			<a href="<?php echo site_url('pages/collection'); ?>">Collection</a>
      		</li>
			
      		<li<?php echo ($page_type=="check"? ' class="active"' : ''); ?>>
      			<a href="<?php echo site_url('pages/check'); ?>">Validation</a>
      		</li>
			
      		<li<?php echo ($page_type=="remittance"? ' class="active"' : ''); ?>>
      			<a href="<?php echo site_url('pages/remittance'); ?>">Remittance</a>
      		</li>

      		<li<?php echo ($page_type=="validation"? ' class="active"' : ''); ?>>
      			<a href="<?php echo site_url('pages/validation'); ?>">Validation</a>
      		</li>
			
      	</ul>

      	<div class="tab-content">
			<?php if($page_type=='index'): ?>
      		<div id="sales_order" class="tab-pane active">
				<?php include_once('tab/sales_order.php'); ?>
      		</div>
			<?php endif; ?>
			
			<?php if($page_type=='invoice'): ?>
      		<div id="invoice" class="tab-pane active">
				<?php include_once('tab/invoice.php'); ?>
      		</div>
			<?php endif; ?>
			
			<?php if($page_type=='inventory'): ?>
      		<div id="inventory" class="tab-pane active">
				<?php include_once('tab/inventory.php'); ?>
      		</div>
			<?php endif; ?>
			
			<?php if($page_type=='delivery'): ?>
      		<div id="delivery" class="tab-pane active">
				<?php include_once('tab/delivery.php'); ?>
      		</div>
			<?php endif; ?>
			
			<?php if($page_type=='collection'): ?>
      		<div id="collection" class="tab-pane active">
				<?php include_once('tab/collection.php'); ?>
      		</div>
			<?php endif; ?>
			
			<?php if($page_type=='rcollection'): ?>
      		<div id="check" class="tab-pane active">
				<?php include_once('tab/rcollection.php'); ?>
      		</div>
			<?php endif; ?>
			
			<?php if($page_type=='check'): ?>
      		<div id="check" class="tab-pane active">
				<?php include_once('tab/check.php'); ?>
      		</div>
			<?php endif; ?>
			
			<?php if($page_type=='rcheck'): ?>
      		<div id="check" class="tab-pane active">
				<?php include_once('tab/rcollection.php'); ?>
      		</div>
			<?php endif; ?>
			
			<?php if($page_type=='remittance'): ?>
      		<div id="delivery" class="tab-pane active">
				<?php include_once('tab/remittance.php'); ?>
      		</div>
			<?php endif; ?>
			
			<?php if($page_type=='validation'): ?>
      		<div id="validation" class="tab-pane active">
				<?php include_once('tab/validation.php'); ?>
      		</div>
			<?php endif; ?>
      	</div>
      </div>
    </div>
  </div>
</div>
