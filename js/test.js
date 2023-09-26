jQuery(document).ready(function($){
    
    var form = $('#testScoreForm');
    form.find('[type="submit"]').on('click', function(e){
        e.preventDefault();
        var error = false;
        var focus = false;
        form.find('.form-error').remove();
        form.find('input[type="text"]').each(function() {
            var element = $(this);
            if (element.val() == '') {
                error = true;
                element.parent().append('<p class="form-error text-danger">Please enter value!</p>');
            }
        });
        
        form.find('input[type="hidden"]').each(function() {
            var element = $(this);
            if (element.val() == '') {
                if (!focus) {
                    focus = element.data('question');
                }
                error = true;
                element.parent().append('<p class="form-error text-danger">Please select value!</p>');
            }
        });
        
        if (!error) {
            form.submit();
        } else {
            if (focus) {
                $('html, body').animate({
                    scrollTop: $("#question-" + focus).offset().top
                }, 2000);
            }
        }
    });
    
    form.find('[type="text"]').on('keypress', function(e){
        var data_type = $(this).data('type');
        if (data_type  == 'float') {
            if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57) && (e.which != 0) && (e.which != 8)) {
                e.preventDefault();
            }
        } else {
            if ((e.which < 48 || e.which > 57) && (e.which != 0) && (e.which != 8)) {
                e.preventDefault();
            }
        }
    });
    
    $( ".slider" ).each(function(){
        var slider = $(this);
        var min_value = parseInt(slider.attr('data-min'));
        var max_value = parseInt(slider.attr('data-max'));
        var default_value = parseInt(slider.attr('data-default'));
        slider.slider({
            min: min_value,
            max: max_value,
            value: default_value,
            create: function() {
                var value = $( this ).slider( "value" );
                var handle = $( this ).find('.custom-handle');
                var input = $( this ).find('input[type="hidden"]');
                input.val( value );
                handle.text( value );
            },
            slide: function( event, ui ) {
                var handle = $( this ).find('.custom-handle');
                var input = $( this ).find('input[type="hidden"]');
                input.val( ui.value );
                handle.text( ui.value );
                //$( "#amount" ).val( ui.value );
            }
        });
    });
    
    $('.psychology-que span').on('click', function(){
        var rColor = 12;
        var gColor = 99;
        var bColor = 66;
        var value = $(this).data('value');
        rColor = rColor+(2.7 * (value - 1));
        gColor = gColor+(2.7 * (value - 1));
        bColor = bColor+(2.7 * (value - 1));
        $(this).addClass('active').siblings().removeClass('active');
        $(this).parent().find('input[type="hidden"]').val(value);
    });
    
    function resizeLabel() {
        var maxHeight = 0;
        $('.psychology-test h2').each(function(){
            var height = $(this).height();
            if (height > maxHeight) {
                maxHeight = height;
            }
        });
        $('.psychology-test h2').css('height', maxHeight+'px');
    }
    
    $(window).load(function() {
        resizeLabel();
    }).resize(function() {
        resizeLabel();
    });
});