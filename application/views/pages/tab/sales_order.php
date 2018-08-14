<div class="row">
    <div class="col-md-3">
    <?php if($this->session->userdata('user_level')==2 || $this->session->userdata('user_level')==4 || $this->session->userdata('user_level')==0): ?>
      <a href="<?php echo site_url('salesOrder/register'); ?>" class="btn btn-primary" /><i class="fa fa-plus"></i> Create Sales Order</a>
    <?php endif; ?>
    </div>

<form id="formSearchSO" method="post" action="<?php echo site_url('pages/index'.(isset($segment)? '/'.$segment:'')); ?>">
    <div class="col-md-9">
      <div class="row">
        <div class="col-md-3">
            <select name="level" class="form-control">
                <option value="0" <?php echo ($this->session->userdata('level')==0?'selected':''); ?>>Sales Order</option>
                <option value="1" <?php echo ($this->session->userdata('level')==1?'selected':''); ?>>Invoice</option>
                <option value="2" <?php echo ($this->session->userdata('level')==2?'selected':''); ?>>Delivery</option>
                <option value="4" <?php echo ($this->session->userdata('level')==4?'selected':''); ?>>Collection</option>
                <option value="6" <?php echo ($this->session->userdata('level')==6?'selected':''); ?>>Remittance</option>
                <option value="9" <?php echo ($this->session->userdata('level')==9?'selected':''); ?>>Cleared</option>
            </select>
        </div>
        <div class="col-md-4">
          <div class="input-group">
            <span class="input-group-addon">
              <i class="fa fa-calendar bigger-110"></i>
            </span>
  
            <input class="form-control" autocomplete="off" name="date-range-picker" <?php echo 'value="'.($this->session->userdata('daterange')?$this->session->userdata('daterange'): $start.' - '.$end).'"'; ?> name="daterange" id="id-date-range-picker-1" type="text">
          </div>
        </div>
        <div class="col-md-5">
          <div class="input-group">
            <input class="form-control search-query" value="<?php echo $this->session->userdata('search'); ?>" placeholder="Type your query" name="search" type="text">
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
              <th>Sales Order No.</th>
              <th>PSR Name</th>
              <th class="hidden-480">Customers Name</th>

              <th>
                <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
                SO Date
              </th>
              <th>
                <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
                Delivery Date
              </th>
              <!--<th>-->
              <!--  <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>-->
              <!--  Approved Date-->
              <!--</th>-->
              <th>
                <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
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
                <label>Sales Order Count<?php echo ($so_num_rows>1?'s':''); ?>: <?php echo $so_num_rows; ?></label>
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
<div id="right-menu" class="modal aside aside-right aside-vc aside-fixed navbar-offset no-backdrop out" data-body-scroll="false" data-offset="true" data-placement="right" data-fixed="true" data-backdrop="false" tabindex="-1" style="position: fixed; padding-right: 17px;">
    <div class="modal-dialog" style="width: 400px;">
        <div class="modal-content ace-scroll"><div class="scroll-track scroll-dark no-track idle-hide scroll-active" style="display: block; height: 617px;"><div class="scroll-bar" style="height: 353px; top: 0px;"></div></div><div class="scroll-content" style="max-height: 617px;">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">×</span>
                    </button>
                    <i class="fa fa-comments"></i> Comments
                </div>
            </div>

            <div id="comment-body" class="modal-body scroll-content" style="overflow-y: auto; max-height: 570px;">
                    
            </div>
        </div></div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script>
    $(function(){
        var main = "gurlavi";
        var main_path =  window.location.protocol+"//"+window.location.hostname+"/"+main;
        
        $('.show-comments').on('click',function(){
            $('#comment-body').html('');
            var _modal = $('#right-menu');
            var _data_value = $(this).attr('data-value');
            _modal.modal('show');
            
            $.ajax({
                type:'POST',
                url:main_path+'/salesOrder/comments',
                data:{'so_id':_data_value},
                success:function(data)
                {
                    $('#comment-body').html(data);
                }
            });
        });
    });
</script>