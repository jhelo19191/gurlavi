$(function(){
    var main = "gurlavi";
    var main_path =  window.location.protocol+"//"+window.location.hostname+"/"+main;
    
    $('.request-invoice').on('click',function(){
        var _data = $(this).attr('data-value');
        var _modal = $('#invoiceModal');
        
        $('#save-invoice').attr('data-value',_data);
        
        _modal.modal({ 'backdrop' : 'static', 'keyboard' : false });
        
        _modal.modal('show');
    });
    
    $('#save-invoice').on('click',function(){
        var _serialize = $('#invoiceForm').serializeArray();
        var _data = $(this).attr('data-value');
        
        var _modal = $('#showLoadingModal');
        var _html = '';
        _modal.modal({
                'backdrop' : 'static',
                'keyboard' : false
            });
        _modal.modal('show');
        $('#invoiceModal').css('z-index','1');
        
        var fdata = new FormData();
        
        fdata.append('sales_order_id',_data);
        
        $.each(_serialize, function(i,fields){
          fdata.append(fields.name,fields.value);
        });
    
        for($i=0;$i<$('.upload-images')[0].files.length;$i++){
            fdata.append('files'+$i,$('.upload-images')[0].files[$i]);
        }
        
        $.ajax({
            type:'POST',
            url:main_path+'/invoice/insert',
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
                
                $('.invoice-message').html(_html);
                _modal.modal('hide');
                $('#invoiceModal').css('z-index','9999');
                $('#invoiceModal').css('overflow-y','auto');
            }
        });
        
        return false;
    });
});