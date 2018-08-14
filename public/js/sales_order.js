$(function(){
    var ctr = 1;
    var total_net_price = 0;
    var main = "gurlavi";
    var main_path =  window.location.protocol+"//"+window.location.hostname+"/"+main;
  
    $('.register-product').on('click',function(e){
      var _modal = $('#addProducts');
  
      _modal.modal({'backdrop':'static','keyboard':false});
  
      _modal.modal('show');
  
      return false;
    });
  
          
      var setNumber = function($amount)
      {
          var number_set = $amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
          
          return number_set;
      }
      
    $('.create-product').on('click',function(){
      var _product_name = $('#product-id option:selected').text();
      var _quantity = $('.quantity').val();
      var _unit = $('.unit').val();
      var _price = $('.price').val();
      var _product_id = $('#product-id').val();
      var total = (_quantity * _price);
      
      var _modal_loading = $('#showLoadingModal');
      var _html = '';
      _modal_loading.modal({
              'backdrop' : 'static',
              'keyboard' : false
          });
      _modal_loading.modal('show');
      
      
      var _modal = $('#addProducts');
      
      $('#addProducts').css('z-index','1');
      var total_s = 0;
      
      $.ajax({
          type:'POST',
          url:main_path+'/products/checkProducts',
          dataType:'json',
          data:{'pid':_product_id},
          success:function(data){
              var _html = '';
              
              if (data.result==='fail') {
                  _html += '<div class="alert alert-danger">';
                  _html += '        <strong> '+data.message+' </strong>';
                  _html += '</div>';
              } else {
                  _html += '<div class="alert alert-success">';
                  _html += '        <strong> '+data.message+' </strong>';
                  _html += '</div>';
                  total_net_price = parseInt(total_net_price) + parseInt(total);
                  
                  $('.total-net-price').html("<strong class='total_net_price' data-value='"+total_net_price+"'>TOTAL NET PRICE: PHP "+ setNumber(total_net_price) + "</strong>");
                  if(ctr==1){
                    $('#simple-table > tbody:first').children().remove();
                  }
              
                  var html = tableFormat(_product_name,_quantity,_unit,_price,ctr,_product_id);
                  $('#simple-table tbody').append(html);
                  ctr++;
                  total_s = total_net_price;
                  $('.button-delete').on('click',function(){
                      var _data = $(this).attr('data-value');
                      
                      $('#'+_data).fadeOut('slow');
                          
                      var total_ft = $('.'+_data+'total').text();
                      total_s = total_s - total_ft;
                      $('.total-net-price').html("<strong class='total_net_price' data-value='"+total_s+"'>TOTAL NET PRICE: PHP " + setNumber(total_s) + "</strong>");
                      
                      total_net_price = total_s;
                      return false;
                  });			
              }
              
              $('.message-order').html(_html);
              
              _modal_loading.modal('hide');
              $('#addProducts').css('z-index','1200');
          }
      });
      
      
      
      return false;
    });
    
    
  
    $('#submit-sales-order').on('click',function(e){
      e.preventDefault();
  
      var _serialize = $('#SalesOrderForm').serializeArray()
  
      var fdata = new FormData();
      var _modal_loading = $('#showLoadingModal');
      var _html = '';
      _modal_loading.modal({
              'backdrop' : 'static',
              'keyboard' : false
          });
      _modal_loading.modal('show');
      
      $.each(_serialize, function(i,fields){
        fdata.append(fields.name,fields.value);
      });
  
      for($i=0;$i<$('.upload-images')[0].files.length;$i++){
          fdata.append('files'+$i,$('.upload-images')[0].files[$i]);
      }
  
      $.ajax({
          type:'POST',
          url:main_path+'/salesOrder/insert',
          data:fdata,
          contentType: false,
          processData: false,
          cache: false,
          dataType:'json',
          success:function(data){
              var _html = '';
              
              if (data.result==='fail') {
                  _html += '<div class="alert alert-danger">';
                  _html += '        <strong> '+data.message+' </strong>';
                  _html += '</div>';
              } else {
                  _html += '<div class="alert alert-success">';
                  _html += '        <strong> '+data.message+' </strong>';
                  _html += '</div>';
              }
            
              $('.message-sales-order').html(_html);
              
              _modal_loading.modal('hide');
          }
      });
      
      return false;
  
    });
  
    function tableFormat($product_name,$quantity,$unit,$price,$ctr,$product_id)
    {
      var html = '';
      
      html +='<tr id="'+$ctr+'">';
      html +='<td><p class="'+$ctr+'product_name">'+$product_name+'</p><input type="text" class="ft-product-'+$ctr+'" hidden name="pdc_name[]" value="'+$product_name+'"/><input type="hidden" class="ft-id-'+$ctr+'" name="pdc_id[]" value="'+$product_id+'"/></td>';
      html +='<td><p class="'+$ctr+'quantity">'+$quantity+'</p><input type="text" class="ft-quantity-'+$ctr+'" hidden name="pdc_quantity[]" value="'+$quantity+'"/></td>';
      html +='<td><p class="'+$ctr+'unit">'+$unit+'</p><input type="text" class="ft-unit-'+$ctr+'" hidden name="pdc_unit[]" value="'+$unit+'"/></td>';
      html +='<td><p class="'+$ctr+'price">'+$price+'</p><input type="text" class="ft-price-'+$ctr+'" hidden name="pdc_price[]" value="'+$price+'"/></td>';
      html +='<td><p class="'+$ctr+'total">'+($quantity * $price)+'</td>';
      html +='<td align="center"><button data-value="'+$ctr+'" class="btn btn-xs btn-danger button-delete">';
      html +='		<i class="ace-icon fa fa-trash-o bigger-120"></i>';
      html +='</button></td>';
      html +='</tr>';
  
      return html;
    }
    
  
});