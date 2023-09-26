var page_id = 2;
var loading = false;;
function searchPlayers(appendData) {
    
    var form = $('#form-filter-players');
    var url = form.attr('action');
    var data = form.serialize() + '&page_id='+page_id;
    var submit = form.find('button[type=submit]');
    
    if (loading)
        return;
    
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        dataType: 'json',
        beforeSend: function() {
            loading = true;
            $('.loader').show();
        },
        complete: function() {
            $('.loader').hide();
        },
        success: function(json) {
            page_id = page_id+1;
            
            if(!json.finish) {
                loading = false;
                $('.load-more').show();
            } else {
                $('.load-more').hide();
            }
            
            if(json.success) {
                
            } else if(json.error) {
                
            } else {
                if (appendData) {
                    $('#players-list').append(json.list);
                } else {
                    $('#players-list').html(json.list);
                }
                
                resizePlayer();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

function resizePlayer() {
    var width = $('#players-list > li:first').width();
    var height = width/0.79;
    $('#players-list > li').each(function() {
        $(this).find('.p_car_plyr').css('height', height+'px');
    });
}

jQuery(document).ready(function($){
    $(window).scroll(function() {
        if($(window).scrollTop() == $(document).height() - $(window).height()) {
            //searchPlayers(true);
        }
    });

    $('.load-more').on('click', function(e) {
        searchPlayers(true);
    });
    
    $('form#form-filter-players').on('submit', function(e) {
        e.preventDefault();
        page_id = 1;
        loading = false;
        searchPlayers(false);
    });
    
    $('#sort_order').on('change', function(e) {
        var value = $(this).val();
        $('#filter_order').val(value);
        $('form#form-filter-players').submit();
    });
    
    $(window).on('load', function() {
        resizePlayer();
    }).on('resize', function() {
        resizePlayer();
    });
});

var DELAY = 300, clicks = 0, timer = null;
$('html').on('click', '#players-list a', function(e) {
    
    var width = $( window ).width();
    if (width >= 1024) {
        return;
    }
    
    e.preventDefault();
    clicks++;
    var element = $(this);
    if(clicks === 1) {
        timer = setTimeout(function() {
            clicks = 0;
            var hover = element.find('.pcp_con_hvr');
            console.log(hover.css('left'));
            if (hover.css('left') == '0px') {
                element.find('.pcp_con_hvr').animate({"left":"-100%"}, "100");
            } else {
                element.find('.pcp_con_hvr').animate({"left":"0%"}, "100");
            }
        }, DELAY);
    } else {
        clearTimeout(timer);
        clicks = 0;
        //element.find('.pcp_con_hvr').animate({"left":"-100%"}, "100");
        window.location.href = element.attr('href');
    }
})
.on("dblclick", function(e){
    e.preventDefault();
});
