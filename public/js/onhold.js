$(function(){
    var main = "gurlavi";
    var main_path =  window.location.protocol+"//"+window.location.hostname+"/"+main;
    
    $('.btn-delete').on('click',function(){
        var _modal = $('#showDeleteModal');
        var _data = $(this).attr('data-value');
        
        _modal.modal({
                'backdrop' : 'static',
                'keyboard' : false
            });
        
        $('.tid').val(_data);
        $('.activity').val('delete');
        _modal.modal('show');
    });
    
    $('#remove-onhold').on('click',function(){
        var _serialize = $('#remove-form').serialize();
        var _modal = $('#showLoadingModal');
        var _html = '';
        _modal.modal({
                'backdrop' : 'static',
                'keyboard' : false
            });
        _modal.modal('show');
        $('#showDeleteModal').css('z-index','1');
        
        $.ajax({
            type:'POST',
            url:main_path+'/onhold/notification',
            dataType:'json',
            data:_serialize,
            success:function(data)
            {
                if (data.result==='fail') {
                    _html += '<div class="alert alert-danger">';
                    _html += '        <strong> '+data.message+' </strong>';
                    _html += '</div>';
                } else {
                    _html += '<div class="alert alert-success">';
                    _html += '        <strong> '+data.message+' </strong>';
                    _html += '</div>';
                    $(this).prop('hidden',true);
                }
                
                _modal.modal('hide');
                $('#message-delete').html(_html);
                
                $('#showDeleteModal').css('z-index','1200');
            }
        });
    });
    
    $('#submit-onhold').on('click',function(){
        var _serialize = $('#onhold-form').serialize();
        var _modal = $('#showLoadingModal');
        var _html = '';
        _modal.modal({
                'backdrop' : 'static',
                'keyboard' : false
            });
        _modal.modal('show');
        $('#addPaymentModal').css('z-index','1');
        
        $.ajax({
            type:'POST',
            url:main_path+'/onhold/notification',
            dataType:'json',
            data:_serialize,
            success:function(data)
            {
                if (data.result==='fail') {
                    _html += '<div class="alert alert-danger">';
                    _html += '        <strong> '+data.message+' </strong>';
                    _html += '</div>';
                } else {
                    _html += '<div class="alert alert-success">';
                    _html += '        <strong> '+data.message+' </strong>';
                    _html += '</div>';
                }
                
                _modal.modal('hide');
                $('#message-onhold').html(_html);
                
                $('#addPaymentModal').css('z-index','1200');
            }
        });
        
    });
    
    $('.btn-modify').on('click',function(){
        var _modal_loading = $('#showLoadingModal');
        var _html = '';
        _modal_loading.modal({
                'backdrop' : 'static',
                'keyboard' : false
            });
        _modal_loading.modal('show');
        
        var _modal = $('#addPaymentModal');
        var _data = $(this).attr('data-value');
        _modal.modal({
                'backdrop' : 'static',
                'keyboard' : false
            });
        $('.tid').val(_data);
        $('.activity').val('modify');
        _modal.modal('show');
        
        $('#addPaymentModal').css('z-index','1');
        
        $.ajax({
                type:'post',
                url:main_path+'/onhold/notification',
                data:{'tid':_data,'activity':'get'},
                dataType:'json',
                success:function(data){
                    $('#form-field-select-3').val(data.receiver_id);
                    $('#payment_type').val(data.payment_type);
                    $('#amount').val(data.amount);
                    $('#created_by').val(data.created_by);
                    
                    _modal_loading.modal('hide');
                    $('#addPaymentModal').css('z-index','1200');
                }
            });
    });
    
    $('.add-payment').on('click',function(){
        var _modal = $('#addPaymentModal');
        _modal.modal({
                'backdrop' : 'static',
                'keyboard' : false
            });
        $('.activity').val('add');
        _modal.modal('show');
    });
});