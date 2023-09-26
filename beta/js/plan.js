jQuery(document).ready(function($){
    $('form#form-purchase-plan').on('submit', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var url = form.attr('action');
        var data = form.serialize();
        var submit = form.find('button[type="submit"]');
        form.find('.form-msg').remove();
        form.find('.error-msg').remove();
        
        $.ajax({
            url: url,
            type: 'post',
            data: data,
            dataType: 'json',
            beforeSend: function() {
                form.find('.ajax-loading').show();
                form.find('button').attr('disabled', 'disabled');
            },
            complete: function() {
                form.find('button').removeAttr('disabled');
                form.find('.ajax-loading').hide();
            },
            success: function(json) {
                if (json.redirect) {
                    window.location = json.redirect;
                } else if (json.error) {
                    var error = json.error;
                    if (error.msg) {
                        form.find("#card-details h3").after('<p class="error-msg">' + error.msg + '</p>');
                    }
                    
                    if (error.field) {
                        $.each(error.field, function(value,label) {
                            var input = form.find('input[name=' + value + ']');
                            var select = form.find('select[name=' + value + ']');
                            if(select.length > 0) {
                                select.parent().parent().after('<p class="error-msg">' + label + '</p>');
                            } else {
                                input.after('<p class="error-msg">' + label + '</p>');
                            }
                            
                        });
                    }
                } else {
                    if (json.msg) {
                        form.find("#card-details h3").after('<p class="form-msg">' + json.msg + '</p>');
                        
                        setTimeout(function () {
                           window.location.href= window.location.href; 
                        },3000);
                    }
                    /* invite.val('');
                    invite.after('<p class="form-msg text-success">' + json.msg + '</p>'); */
                }
            }
        });
    });
});