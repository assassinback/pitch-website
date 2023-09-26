
jQuery(document).ready(function($){
    $('form.form-amenities').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var url = form.attr('action');
        //var data = form.serialize();
        var submit = form.find('button[type=submit]');
        form.find('.alert').remove();
        
        var data = new FormData();
        if (form.find('input[type="file"]').length > 0) {
            var files = form.find('input[type="file"]')[0].files;
            for(var i = 0;i<files.length;i++){
                data.append("file_"+i, files[i]);
            }
        }
        
        var other_data = form.serializeArray();
        $.each(other_data,function(key,input){
            console.log(input.name, input.value);
            data.append(input.name,input.value);
        });
        
        $.ajax({
            url: url,
            type: 'post',
            data: data,
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function() {
                submit.attr('disabled', 'disabled');
                submit.html('Loading...');
                $('.loader').show();
            },
            complete: function() {
                $('.loader').hide();
                submit.html(submit.data('label'));
                submit.removeAttr('disabled');
            },
            success: function(json) {
                
                if(json.redirect) {
                    window.location.href = json.redirect;
                } else if(json.error) {
                    form.prepend = '<div class="alert alert-danger">' + json.error + '</div>'
                } else {
                    
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('form#form-send-invitaion').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var url = form.attr('action');
        var data = form.serialize();
        var submit = form.find('button[type=submit]');
        var submitLabel = submit.html();
        form.find('.alert').remove();
        
        var message = form.find('textarea[name="message"]');
        if (message.val() == "") {
            message.before('<p class="form-msg text-danger">Please enter message!</p>');
        } else {
            $.ajax({
                url: url,
                type: 'post',
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    submit.attr('disabled', 'disabled');
                    submit.html('Loading...');
                    $('.loader').show();
                },
                complete: function() {
                    $('.loader').hide();
                    submit.html(submitLabel);
                    submit.removeAttr('disabled');
                },
                success: function(json) {
                    
                    if (json.redirect) {
                        window.location = json.redirect;
                    } else if (json.error) {
                        message.before('<p class="form-msg text-danger">' + json.error + '</p>');
                    } else {
                        message.before('<p class="form-msg text-success">' + json.msg + '</p>');
                    } 
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    });
    
});
