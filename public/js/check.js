$(function(){
    var main = "gurlavi";
    var main_path =  window.location.protocol+"//"+window.location.hostname+"/"+main;
    
    $('.close-modal').on('click',function(){
        location.reload();
    });
    
    $('#submit-comments').on('click',function(){
        $('.comment-message').prop('hidden',true);
        var _serialize = $('#statusRecord').serialize();
        
        var _modal_loading = $('#showLoadingModal');
        _modal_loading.modal({
                'backdrop' : 'static',
                'keyboard' : false
            });
        _modal_loading.modal('show');
        
        $('#rejectModal').css('z-index',1);
        
        $.ajax({
            type : 'post',
            url : main_path+'/check/rejectStatus',
            dataType:'json',
            data:_serialize,
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
                
                $('.comment-message').prop('hidden',false);
                $('.comment-message').html(_html);
                
                $('#rejectModal').css('z-index',1999);
                _modal_loading.modal('hide');
            }
        });
        
    });
    
    $('.show-reject').on('click',function(){
        var _soid = $(this).attr('data-target');
        var _payment_type = $(this).attr('data-access');
        var _pid = $(this).attr('data-value');
        var _action_type = $(this).attr('data-button');
        var _modal = $('#rejectModal');
        
        $('.soid').val(_soid);
        $('.payment_type').val(_payment_type);
        $('.pid').val(_pid);
        $('.action_type').val(_action_type);
        
        _modal.modal('show');
        
        
        return false;
    });
    
    $('.show-accept').on('click',function(){
        var _soid = $(this).attr('data-target');
        var _payment_type = $(this).attr('data-access');
        var _pid = $(this).attr('data-value');
        var _action_type = $(this).attr('data-button');
        
        
        var _modal_loading = $('#showLoadingModal');
        var _html = '';
        _modal_loading.modal({
                'backdrop' : 'static',
                'keyboard' : false
            });
        _modal_loading.modal('show');
        
        $('.soid').val(_soid);
        $('.payment_type').val(_payment_type);
        $('.pid').val(_pid);
        $('.action_type').val(_action_type);
        
        var _serialize = $('#statusRecord').serialize();
        
        $.ajax({
            type : 'post',
            url : main_path+'/check/acceptStatus',
            dataType:'json',
            data:_serialize,
            success:function(data){
                var _color = 'success';
                $(this).prop('hidden',false);
                $('.loading').prop('hidden',true);
                if (data.result==='fail') {
                    _color = 'danger';
                }
                setTimeout(function(){ location.reload(); },1000);
                _modal_loading.modal('hide');
                
                bnotify(data,_color);
            }
        });
        
        return false;
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
        
        $('.loading').prop('hidden',false);
        
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
                
                restrictNumber($('.account_number'));
                restrictNumber($('.check_number'));
                restrictNumber($('.pdc_amount'));
                
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
                $('.loading').prop('hidden',true);
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
    
    var bnotify = function notification($data,$alert) {
        $.notify('<strong>'+$data.message+'</strong>.', {
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