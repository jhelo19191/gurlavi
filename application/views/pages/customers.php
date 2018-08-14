<div class="row">
  <div class="col-md-5">
    <a href="<?php echo site_url('customer/index'); ?>" class="btn btn-primary" /><i class="fa fa-plus"></i> New Customer</a>
    <button id="upload-customers" class="btn btn-primary" /><i class="fa fa-upload"></i> Upload Customers</button>
  </div>
  <div class="col-md-7">
    <div class="row">
      <div class="col-md-5">
      </div>
      <div class="col-md-7">
        <form id="product-search" action="<?php echo site_url('pages/customers'); ?>" method="post">
            <div class="input-group">
                <input class="form-control search-query" name="search" placeholder="Type your query" type="text">
                <span class="input-group-btn">
                  <button type="button" class="btn btn-inverse btn-white" onclick="$('#product-search').submit(); return false;">
                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                    Search
                  </button>
                </span>
            </div>   
        </form>
        
      </div>
    </div>

  </div>
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
              <th>Customer Name</th>
              <th>Address</th>
              <th>Contact Number</th>
              <th>TIN Number</th>
              <th>Created Date</th>
              <th></th>
            </tr>
          </thead>

          <tbody>
            <?php echo $html; ?>
          </tbody>
        </table>
      </div>
</div>
<!-- Modal -->
<div id="removeProducts" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Notification Message</h4>
      </div>
      <div class="modal-body" align="center">
        <input type="hidden" class="remove-id" />
        <div class="loading" hidden>
            <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>
            <h4>Please wait. . .</h4>
        </div>
        <div class="message">
            <h4>Are your sure you want to delete this record?</h4>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default close-modal" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary remove-product">Submit</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="uploadCustomers" class="modal fade" role="dialog">
    <div class="modal-dialog">
  
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-upload"></i> Upload Customers</h4>
            </div>
            <div class="modal-body">
                <!--<div class="progress">
                    <div class="bar"></div >
                    <div class="percent">0%</div >
                </div>-->
                <form id="formUpload" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="product-data-upload" type="file" name="upload" id="id-input-file-3" />
                            </div>
                        </div>    
                    </div>    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submit-upload-products">Upload</button>
            </div>
        </div>
  
    </div>
</div>
<script>
    $(function(){
        var main = "gurlavi";
        var main_path =  window.location.protocol+"//"+window.location.hostname+"/"+main;
        
        $('#upload-customers').on('click',function(){
            var _modal_upload = $('#uploadCustomers');
            
            _modal_upload.modal({
                    'backdrop' : 'static',
                    'keyboard' : false
                });
            
            _modal_upload.modal('show');
        });
        
        $('.submit-upload-products').on('click',function(){
            
            var fdata = new FormData();
            
            
            for($i=0;$i<$('.product-data-upload')[0].files.length;$i++){
                fdata.append('files'+$i,$('.product-data-upload')[0].files[$i]);
            }
            
            $.ajax({
                url: '../upload/customers',
                type: "POST",
                data: fdata,
                dataType: "json",
                contentType: false,
                processData: false,
                cache: false,
                success: function(result) {
                  console.log(result);
                }
            });
        });
        
        $('.remove').on('click',function(){
            var _modal = $('#removeProducts');
            var _value = $(this).attr('data-value');
            _modal.modal({
                    'backdrop' : 'static',
                    'keyboard' : false
                });
            
            $('.remove-id').val(_value);
            
            $('.message').html('<h4>Are you sure you want to delete this record?</h4>');
            $(this).prop('hidden',false);
            
            _modal.modal('show');
        });
        
        $('.remove-product').on('click',function(){
            var _value = $('.remove-id').val();
            
            $('.loading').prop('hidden',false);
            $('.message').prop('hidden',true);
            
            $.ajax({
                type:'post',
                url:main_path+'/customer/remove',
                data:{'pdi':_value},
                success:function(data){
                    var _obj = JSON.parse(data);
                    
                    var html = '';
                    if (_obj.result==='success') {
                        $('#'+_value).remove();
                        html += '<div class="alert alert-success">';
                        html += '        <strong> '+_obj.message+' </strong>';
                        html += '</div>';
                        $('.remove-product').prop('hidden',true);
                    } else {
                        html += '<div class="alert alert-danger">';
                        html += '    <strong> '+_obj.message+' </strong>';
                        html += '</div>';
                    }
            
                    $('.loading').prop('hidden',true);
                    $('.message').prop('hidden',false);
                    $('.message').html(html);
                }
            });
        });
        
        $('.close-modal').on('click',function(){
            location.reload();
        });
    });
</script>