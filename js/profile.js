jQuery(document).ready(function($){
    $('form#form-invite').on('submit', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var url = form.attr('action');
        var data = form.serialize();
        var submit = form.find('button[type="submit"]');
        form.find('.form-msg').remove();
        
        var invite = form.find('input[name="invite"]');
        if (invite.val() == "") {
            invite.after('<p class="form-msg text-danger">Please enter email address!</p>');
        } else {
            
            $.ajax({
                url: url,
                type: 'post',
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    submit.attr('disabled', 'disabled');
                },
                complete: function() {
                    submit.removeAttr('disabled');
                },
                success: function(json) {
                    if (json.redirect) {
                        window.location = json.redirect;
                    } else if (json.error) {
                        invite.after('<p class="form-msg text-danger">' + json.error + '</p>');
                    } else {
                        invite.val('');
                        invite.after('<p class="form-msg text-success">' + json.msg + '</p>');
                    } 
                }
            });
        }
    });
    
    $('form#form-validate-request').on('submit', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var type = $(this).data('type');
        var url = form.attr('action');
        var data = form.serialize();
        var submit = form.find('button[type="submit"]');
        form.find('.form-msg').remove();
        
        var error = false;
        
        if (type == 'multiple') {
            var coach = form.find('input[name="coach[]"]:checked');
            if (coach.length == 0) {
                error = true;
                form.find('ul').before('<p class="form-msg text-danger">Please select coach to send request!</p>');
            }
        }
        
        if (!error) {
            
            $.ajax({
                url: url,
                type: 'post',
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    submit.attr('disabled', 'disabled');
                },
                complete: function() {
                    if (type == 'multiple') {
                        submit.removeAttr('disabled');
                    }
                },
                success: function(json) {
                    
                    if (type == 'multiple') {
                        var container = form.find('ul');
                    } else {
                        var container = submit;
                    }
                    
                    if (json.redirect) {
                        //window.location = json.redirect;
                    } else if (json.error) {
                        container.before('<p class="form-msg text-danger">' + json.error + '</p>');
                    } else {
                        container.before('<p class="form-msg text-success">' + json.msg + '</p>');
                        if (type == 'multiple') {
                            coach.prop('checked', false);
                        } else {
                            container.remove();
                        }
                    } 
                }
            });
        }
    });
    
    $('form#form-validate-score').on('submit', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var url = form.attr('action');
        var data = form.serialize();
        var submit = form.find('button[type="submit"]');
        form.find('.form-msg').remove();
        
        $.ajax({
            url: url,
            type: 'post',
            data: data,
            dataType: 'json',
            beforeSend: function() {
                submit.attr('disabled', 'disabled');
            },
            complete: function() {
                //submit.removeAttr('disabled');
            },
            success: function(json) {
                
                if (json.redirect) {
                    window.location = json.redirect;
                } else if (json.error) {
                    submit.before('<p class="form-msg text-danger">' + json.error + '</p>');
                } else {
                    submit.before('<p class="form-msg text-success">' + json.msg + '</p>');
                    submit.remove();
                    
                    setInterval(function(){ window.location = window.location.href; }, 2000);
                } 
            }
        });
    });
    
    
    $('.player-rating img').on('mouseenter', function(){
        if ($(this).parent().hasClass('rate-applied')) {
            return false;
        }
        var index = $(this).index();
        setRatingClass(index, 'rate-hover');
    }).on('mouseleave', function(){
        if ($(this).parent().hasClass('rate-applied')) {
            return false;
        }
        $('.player-rating img').removeClass('rate-hover');
        var index = ($('.player-rating').find('input[name="rating"]').val() - 1);
        setRatingClass(index, 'rate-apply');
    }).on('click', function(){
        if ($(this).parent().hasClass('rate-applied')) {
            return false;
        }
        var index = $(this).index();
        $('.player-rating').find('input[name="rating"]').val((index+1));
        setRatingClass(index, 'rate-apply');
    });
    
    $('.rate-player').on('click', function(e){
        e.preventDefault();
        if ($(this).hasClass('rate-applied')) {
            alert("You have already rated this player");
            return false;
        }
        var value = $('.player-rating').find('input[name="rating"]').val();
        if (value == "") {
            alert("Please select the rating");
            return false;
        } else {
            $(this).closest('form').submit();
        }
    });
    
    function setRatingClass(index, className) {
        $('.player-rating img').each(function(){
            var img = $(this);
            if (img.index() <= index) {
                if (!img.hasClass(className)) {
                    img.addClass(className);
                }
            } else {
                img.removeClass(className);
            }
        });
    }
    
    $('form#form-rate-user').on('submit', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var url = form.attr('action');
        var data = form.serialize();
        var submit = form.find('a.rate-player');
        form.find('.form-msg').remove();
        
        var rating = form.find('input[name="rating"]');
        if (rating.val() == "") {
            submit.before('<p class="form-msg text-danger">Please select the rating!</p>');
        } else {
            
            $.ajax({
                url: url,
                type: 'post',
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    submit.attr('disabled', 'disabled');
                },
                complete: function() {
                    submit.removeAttr('disabled');
                },
                success: function(json) {
                    if (json.redirect) {
                        window.location = json.redirect;
                    } else if (json.error) {
                        submit.before('<p class="form-msg text-danger">' + json.error + '</p>');
                    } else {
                        submit.before('<p class="form-msg text-success">' + json.msg + '</p>');
                        submit.remove();
                    } 
                }
            });
        }
    });
    
    $('form#form-send-message').on('submit', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var url = form.attr('action');
        var data = form.serialize();
        var submit = form.find('button[type="submit"]');
        form.find('.form-msg').remove();
        
        var message = form.find('textarea[name="message"]');
        if (message.val() == "") {
            message.after('<p class="form-msg text-danger">Please enter message!</p>');
        } else {
            
            $.ajax({
                url: url,
                type: 'post',
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    submit.attr('disabled', 'disabled');
                },
                complete: function() {
                    submit.removeAttr('disabled');
                },
                success: function(json) {
                    if (json.redirect) {
                        window.location = json.redirect;
                    } else if (json.error) {
                        message.after('<p class="form-msg text-danger">' + json.error + '</p>');
                    } else {
                        message.val('');
                        message.after('<p class="form-msg text-success">' + json.msg + '</p>');
                    } 
                }
            });
        }
    });
    
    $('.book-session').on('click', function(e) {
        var id = $(this).attr('id');
        $('.book-session').removeClass('selected');
        $(this).addClass('selected');
        $('input[name="trial_session_id"]').val(id);
    });
    
    $('form#form-book-trial-session').on('submit', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var url = form.attr('action');
        var data = form.serialize();
        var submit = form.find('button[type="submit"]');
        form.find('.form-msg').remove();
        
        var trial_session_id = form.find('input[name="trial_session_id"]');
        if (trial_session_id.val() == "") {
            form.find('table').before('<p class="form-msg text-danger">Please select date!</p>');
        } else {
            $.ajax({
                url: url,
                type: 'post',
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    submit.attr('disabled', 'disabled');
                },
                complete: function() {
                    
                },
                success: function(json) {
                    if (json.redirect) {
                        window.location = json.redirect;
                    } else if (json.error) {
                        submit.removeAttr('disabled');
                        form.find('table').before('<p class="form-msg text-danger">' + json.error + '</p>');
                    } else {
                        form.find('table').before('<p class="form-msg text-success">' + json.msg + '</p>');
                    } 
                }
            });
        }
    });
    
    $('form#form-cancel-plan').on('submit', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var url = form.attr('action');
        var data = form.serialize();
        var submit = form.find('button[type="submit"]');
        form.find('.form-msg').remove();
        
        $.ajax({
            url: url,
            type: 'post',
            data: data,
            dataType: 'json',
            beforeSend: function() {
                form.find('.ajax-loading').show();
                submit.attr('disabled', 'disabled');
            },
            complete: function() {
                form.find('.ajax-loading').hide();
            },
            success: function(json) {
                if (json.redirect) {
                    window.location = json.redirect;
                } else {
                    form.find(".modal-body").prepend('<p class="form-msg">' + json.msg + '</p>');
                    
                    setTimeout(function () {
                       window.location.href= window.location.href; 
                    },3000);
                }
            }
        });
    });    
    
    $('form#form-delete-account').on('submit', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var url = form.attr('action');
        var data = form.serialize();
        var submit = form.find('button[type="submit"]');
        form.find('.form-msg').remove();
        
        $.ajax({
            url: url,
            type: 'post',
            data: data,
            dataType: 'json',
            beforeSend: function() {
                submit.attr('disabled', 'disabled');
            },
            complete: function() {
                
            },
            success: function(json) {
                window.location = json.redirect;
            }
        });
    });
    
});
