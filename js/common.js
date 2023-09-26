;(function($){
    $(document).ready(function(){
        $('.is-player').on('change', function(){
            window.location.href = $(this).val();
        });
    });
    
    function resizeiFrame() {
        var ratio = 1.75;
        if ($("iframe").length) {
            $("iframe").each(function() { 
                var iframe = $('iframe');
                var containerWidth = iframe.parent().width();
                var width = parseInt(containerWidth);
                var height = parseInt(width/ratio);
                iframe.attr('width', width).attr('height', height);
            });
        }
    }
    
    $(window).load(function() {
        resizeiFrame()
    }).resize(function() {
        resizeiFrame()
    });
})(jQuery);


$('html').on('click', '.compare', function(e){
    e.preventDefault();
    var id = $(this).attr('data-player');
    var compare = getCookie('compare');
    var remove = false;
    if (compare) {
        /* if (compare.indexOf('-') > -1) {
            compare = compare.split('-');
            if (compare[0] == id) {
                remove = true;
                compare = compare[1];
            } else if (compare[1] == id) {
                remove = true;
                compare = compare[0];
            } else {
                alert('Already 2 player added in compare list.');
                return false;
            }
        } else {
            if(compare == id) {
                remove = true;
                compare = '';
            } else {
                compare = compare + '-' + id;
            }
        } */
        
        if (compare.indexOf('-') > -1) {
            compare = compare.split('-');
            if (compare[0] == id || compare[1] == id) {
                var message = 'You have already added this player in compare list.';
                displayAlertMsg('danger', message);
                return false;
            } else {
                var message = 'Already 2 player added in compare list.';
                displayAlertMsg('danger', message);
                return false;
            }
        } else {
            if(compare == id) {
                var message = 'You have already added this player in compare list.';
                displayAlertMsg('danger', message);
                return false;
            } else {
                compare = compare + '-' + id;
            }
        }
    } else {
        compare = id;
    }
    
    setCookie('compare', compare, 1);
    if (remove) {
        $(this).removeClass('added');
        var message = 'You have already added this player in compare list.';
    } else {
        $(this).addClass('added');
        var message = 'You have added a player to compare list.';
    }    
    displayAlertMsg('success', message);
});

$('html').on('click', '.remove-compare', function(e){
    var remove = $(this).attr('data-player');
    var compare = getCookie('compare');
    if (compare) {
        if (compare.indexOf('-') > -1) {
            compare = compare.split('-');
            if (compare[0] == remove) {
                var id = compare[1];
                setCookie('compare', id, 1);
            } else if (compare[1] == remove) {
                var id = compare[0];
                setCookie('compare', id, 1);
            }
        } else {
            if(compare == remove) {
                deleteCookie('compare');
                location.reload();
            }
        } 
    }
    
    $("#compare-" + remove).fadeOut();
});

function displayAlertMsg(type, message) {
    var alertNotification = $('#alert-notification');
    if (alertNotification.length) {
        alertNotification.find('.alert-msg').html(message);
        alertNotification.removeClass('alert-danger').removeClass('alert-success').addClass('alert-' + type);
        alertNotification.fadeIn();
        setTimeout(function() {
            alertNotification.fadeOut();
        }, 3000);
    }
}

function setCookie(cname, cvalue, exdays) {
    if (cvalue == '') {
        deleteCookie(cname);
        return false;
    }
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function checkCookie() {
    var user = getCookie("username");
    if (user != "") {
        alert("Welcome again " + user);
    } else {
        user = prompt("Please enter your name:", "");
        if (user != "" && user != null) {
            setCookie("username", user, 365);
        }
    }
}

function deleteCookie(cname) {
    document.cookie = cname + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
}

