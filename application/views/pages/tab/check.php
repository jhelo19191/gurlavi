<div class="row">
    <div class="col-md-5">
    </div>

<form id="formSearchSO" method="post" action="<?php echo site_url('pages/check'.(isset($segment)? '/'.$segment:'')); ?>">
    <div class="col-md-7">
      <div class="row">
        <div class="col-md-5">
        </div>
        <div class="col-md-7">
          <div class="input-group">
            <input class="form-control search-query" value="" placeholder="Type your query" name="search" type="text">
            <span class="input-group-btn">
              <button type="button" onclick="$('#formSearchSO').submit(); return false;" class="btn btn-inverse btn-white">
                <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                Search
              </button>
            </span>
          </div>
        </div>
      </div>
    </div>
</form>
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
              <th class="detail-col">Details</th>
              <th>Invoice No.</th>
              <th>PSR Name</th>
              <th class="hidden-480">Customers Name</th>

              <th>
                <i class="ace-icon fa fa-calendar bigger-110 hidden-480"></i>
                Invoice Date
              </th>
              <th>
                <i class="ace-icon fa fa-calendar bigger-110 hidden-480"></i>
                Delivery Date
              </th>
              <!--<th>-->
              <!--  <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>-->
              <!--  Approved Date-->
              <!--</th>-->
              <th>
                <i class="ace-icon fa fa-calendar bigger-110 hidden-480"></i>
                Created Date
              </th>
              <th class="hidden-480">Status</th>

              <th></th>
            </tr>
          </thead>

          <tbody>
                <?php echo $html; ?>
          </tbody>
        </table>
        
        <div class="row">
            <div class="col-md-6">
                <label>Check Count<?php echo ($check_counts>1?'s':''); ?>: <?php echo $check_counts; ?></label>
            </div>
            <div class="col-md-6">
                <div align="right">
                  <ul class="pagination pagination-sm">
                    <li><?php echo $links; ?></li>
                  </ul>
                </div>    
            </div>
        </div>
      </div>
</div>
