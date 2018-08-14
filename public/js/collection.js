$(function(){
    var main = "gurlavi";
    var main_path =  window.location.protocol+"//"+window.location.hostname+"/"+main;
    
    $('.show-delete').on('click',function(e){
        e.preventDefault();
        
        var _modal = $('#deleteModal');
        var _data = $(this).attr('data-value');
        _split = _data.split("_");
        var _payment_type = _split[0];
        var _dtc = _split[1];
        var _tid = _split[2];
        
        $('.tid').val(_tid);
        $('.dtc').val(_dtc);
        $('.pyt').val(_payment_type);
        
        _modal.modal({
                        'backdrop' : 'static',
                        'keyboard' : false
                    });
        _modal.modal('show');
        
    });
    
    $('.add-payment').on('click',function(){
        $('.modal-title').html('Payment Form');
        $('#payment-generate').prop('hidden',false);
        $('.view-type').prop('hidden',true);
        $('.image-container').prop('hidden',false);
        $('.image-preview').html('');
        $('.reject-comments-response').html('');
        $('.fields').html('');
        $('.activity').val($(this).attr('data-action'));
        
        return false;
    });
    
    $('.show-update').on('click',function(e){
        e.preventDefault();
        
        var _modal = $('#collectionModal');
        var _data = $(this).attr('data-value');
        var _pid = $(this).attr('data-holder');
        
        _split = _data.split("_");
        
        var _payment_type = _split[0];
        var _dtc = _split[1];
        var _tid = _split[2];
        
        $('#payment-form').prop('hidden',true);
        $('.loading-notification').prop('hidden',false);
        
        $('.modal-title').html('Modify Payment Information');
        $('#submit-payment').attr('data-holder',_pid);
        $('#submit-payment').prop('hidden',true);
        $('#submit-update').prop('hidden',false);
        $('.activity').val($(this).attr('data-action'));
        
        $('#payment-generate').prop('hidden',true);
        _modal.modal({
                        'backdrop' : 'static',
                        'keyboard' : false
                    });
        _modal.modal('show');
        
        _activity(_payment_type,_dtc,_tid,'modify',_pid);
    });
    
    $('.close-modal').on('click',function(){
        location.reload();
    });
    
    $('.show-view').on('click',function(){
        var _modal = $('#collectionModal');
        var _data = $(this).attr('data-value');
        var _pid = $(this).attr('data-holder');
        _split = _data.split("_");
        var _payment_type = _split[0];
        var _dtc = _split[1];
        var _tid = _split[2];
        
        $('#payment-generate').prop('hidden',true);
        $('.modal-title').html('Payment Information');
        $('#image-container').prop('hidden',true);
        
        $('.view-type').prop('hidden',false);
        
        $('.loading-notification').prop('hidden',false);
        
        _modal.modal({
                        'backdrop' : 'static',
                        'keyboard' : false
                    });
        _modal.modal('show');
        
        _activity(_payment_type,_dtc,_tid,'view',_pid);
        
        return false;
    });
    
    
    var _activity = function activity(_payment_type,_dtc,$tid,$activity_type,$pid=''){
        $.ajax({
            type:'POST',
            url:main_path+'/generate/'+$activity_type,
            dataType:'json',
            data:{'payment_type':_payment_type,'dtc':_dtc,'tid':$tid,'activity':$activity_type,'pid':$pid},
            success:function(data){
                $('.fields').html(data.fields);
                $('.image-preview').html(data.images);
                
                $('.reject-comments-response').html("<hr>"+data.comments);
                $('.view-type').html('<div class="col-md-6">Payment Type:<p><strong>'+data.payment_type+'</strong></p></div><div class="col-md-6">Created Date:<p><strong>'+data.created_date+'</strong></p></div>').prop('hidden',false);
                $('.date-picker').datepicker();
                
                restrictNumber($('.cash-amount'));
                
                restrictNumber($('.receipt_no'));
                restrictNumber($('.account_receiver'));
                
                restrictNumber($('.account_number'));
                restrictNumber($('.check_number'));
                restrictNumber($('.pdc_amount'));
                
                restrictNumber($('.bt_amount'));
                
                restrictNumber($('.card_no'));
                restrictNumber($('.approval_code'));
                restrictNumber($('.batch_no'));
                restrictNumber($('.credit_card_amount'));
                if ($activity_type=="view") {
                    $('#image-container').prop('hidden',true);
                } else {
                    $('#image-container').prop('hidden',false);
                }
                
                $('#payment-form').prop('hidden',false);
                $('.loading-notification').prop('hidden',true);
            }
        });
    }
    
    //$('#submit-payment').on('click',function(){
    //    var _serialize = $('#invoiceForm').serializeArray();
    //    
    //});
    
    $('.generate').on('click',function(){
        var _payment_type = $("#payment-type").val();
        $('#invoiceForm').prop('hidden',true);
        $('.loading-notification').prop('hidden',false);
        $('#payment-generate').prop('hidden',true);
        $('#image-container').prop('hidden',false);
        
        if (_payment_type==='1' || _payment_type==='4') {
            $('.div-terms').prop('hidden',true);
        } else {
            $('.div-terms').prop('hidden',false);
        }
        
        fields(_payment_type);
    });
    
    var fields = function(_payment_type){
        
        $.ajax({
            type:'POST',
            url:main_path+'/generate/fields',
            dataType:'json',
            data:{'payment_type':_payment_type},
            success:function(data){
                $('.fields').html(data.fields);
                
                restrictNumber($('.cash-amount'));
                
                restrictNumber($('.account_number'));
                restrictNumber($('.check_number'));
                restrictNumber($('.pdc_amount'));
                
                restrictNumber($('.card_no'));
                restrictNumber($('.approval_code'));
                restrictNumber($('.batch_no'));
                restrictNumber($('.credit_card_amount'));
                
                
                restrictNumber($('.receipt_no'));
                restrictNumber($('.account_receiver'));
                
                restrictNumber($('.bt_amount'));
                
                $('#payment-generate').prop('hidden',false);
                $('.loading-notification').prop('hidden',true);
                $('#invoiceForm').prop('hidden',false);
                //$('#image-container').prop('hidden',false);
                $('.date-picker').datepicker();
            }
        });
    }
    
    
    var restrictNumber = function($class){
        $($class).keydown(function(e){
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                 // Allow: Ctrl+A, Command+A
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
                 // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                     // let it happen, don't do anything
                     return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    }
    
    $('.add-payment').on('click',function(){
        var _data = $(this).attr('data-value');
        var _modal = $('#collectionModal');
        
        $('#save-delivery').attr('data-value',_data);
        
        _modal.modal({ 'backdrop' : 'static', 'keyboard' : false });
        
        _modal.modal('show');
    });
    
    $('#submit-payment').on('click',function(){
        var _serialize = $('#invoiceForm').serializeArray();
        var _data = $(this).attr('data-value');
        var _payment_type = $('#payment-type').val();
        var _terms = $('.terms').val();
        var _pid = $(this).attr('data-holder');
        
        var _modal_loading = $('#showLoadingModal');
        var _html = '';
        _modal_loading.modal({
                'backdrop' : 'static',
                'keyboard' : false
            });
        _modal_loading.modal('show');
        
        $('#collectionModal').css('z-index','1');
        
        var fdata = new FormData();
        
        fdata.append('sales_order_id',_data);
        fdata.append('payment_type',_payment_type);
        fdata.append('terms',_terms);
        fdata.append('pid',_pid);
        
        $.each(_serialize, function(i,fields){
          fdata.append(fields.name,fields.value);
        });
    
        for($i=0;$i<$('.upload-images')[0].files.length;$i++){
            fdata.append('files'+$i,$('.upload-images')[0].files[$i]);
        }
        
        if ($('.activity').val()==="update") {
            
            $.ajax({
                type:'POST',
                url:main_path+'/collection/update',
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
                    _html += '<div class="hr dotted"></div>';
                    
                    _modal_loading.modal('hide');
                    $('.collection-message').html(_html);
                    
                    $('#collectionModal').css('z-index','1299');
                    $('#collectionModal').css('overflow-y','scroll');
                }
            });
            
        } else {
            $.ajax({
                type:'POST',
                url:main_path+'/collection/payment',
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
                    _html += '<div class="hr dotted"></div>';
                    
                    _modal_loading.modal('hide');
                    $('.collection-message').html(_html);
                    
                    $('#collectionModal').css('z-index','1299');
                    $('#collectionModal').css('overflow-y','scroll');
                }
            });    
        }
        
        
        
        return false;
    });
    
    $('.submit-collection').on('click',function(){
        var _modal_loading = $('#showLoadingModal');
        var _html = '';
        _modal_loading.modal({
                'backdrop' : 'static',
                'keyboard' : false
            });
        _modal_loading.modal('show');
    });
    
    $('#submit-remove').on('click',function(){
        $(this).prop('hidden',true);
        $('.loading-notification').prop('hidden',false);
        $('.close-modal').prop('hidden',true);
        $('.message').prop('hidden',true);
        $('#submit-remove').prop('hidden',true);
        
        var _serialize = $('#removeRecord').serialize();
        
        $.ajax({
            type : 'post',
            url : main_path+'/collection/remove',
            dataType:'json',
            data:_serialize,
            success:function(data){
                var _color = 'success';
                $(this).prop('hidden',false);
                $('.loading-notification').prop('hidden',true);
                $('.close-modal').prop('hidden',false);
                $('.message').prop('hidden',false);
                if (data.result==='fail') {
                    _color = 'danger';
                    
                    $('#submit-remove').prop('hidden',false);
                } 
                
                bnotify(data,_color);
            }
        });
        
    });
    
    var bnotify = function notification($data,$alert) {
        $.notify('<strong>'+$data.message+'</strong>', {
            allow_dismiss: true,
            type:$alert,
            z_index: 5000,
            placement: {
                from: "top",
                align: "center"
            },
        }); 
    }
});