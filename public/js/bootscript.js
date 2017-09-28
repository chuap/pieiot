$(document).ready(function() {

    var myVar;
    $('a.back').click(function() {
        parent.history.back();
        return false;
    });
    //myVar = setTimeout(load_sctop, 1000);
    load_sctop();

    

});

function load_sctop(){
   $(window).scroll(function() {
       //alert('44'); 

        if ($(this).scrollTop() >= 100) {
            $('#gtotop').stop().animate({
                top: '70%'
            }, 300);
            $('#fixtop').addClass('fixtop');
            //$('#divmain').addClass('addpad');
//            $('#fixtop').fadeIn(400, function() {
//                $('#fixtop').addClass('fixtop');
//            });
        }
        else {
            $('#gtotop').stop().animate({
                top: '-100px'
            }, 300);
            $('#fixtop').removeClass('fixtop')
            //$('#divmain').removeClass('addpad');
        }
    });
    $('#gtotop').click(function() {
        $('html, body').stop().animate({
            scrollTop: 0
        }, 500, function() {
            $('#gtotop').stop().animate({
                top: '-100px'
            }, 300);
        });
    }); 
}

function scto(t) {
    if ($(this).scrollTop() >= 100) {
        var x = $(t).offset().top - $('#fixtop').height() - 4;
    }
    else {
        var x = $(t).offset().top - $('#fixtop').height() - 140;
    }
    $('html,body').animate({scrollTop: x}, 300);
}
function scrollGo() {
    var x = $(this).offset().top - 100; // 100 provides buffer in viewport
    $('html,body').animate({scrollTop: x}, 300);
}
$.fn.scrollTo = function(target, options, callback) {
    if (typeof options == 'function' && arguments.length == 2) {
        callback = options;
        options = target;
    }
    var settings = $.extend({
        scrollTarget: target,
        offsetTop: 50,
        duration: 100,
        easing: 'swing'
    }, options);
    return this.each(function() {
        var scrollPane = $(this);
        var scrollTarget = (typeof settings.scrollTarget == "number") ? settings.scrollTarget : $(settings.scrollTarget);
        var scrollY = (typeof scrollTarget == "number") ? scrollTarget : scrollTarget.offset().top + scrollPane.scrollTop() - parseInt(settings.offsetTop);
        scrollPane.animate({scrollTop: scrollY}, parseInt(settings.duration), settings.easing, function() {
            if (typeof callback == 'function') {
                callback.call(this);
            }
        });
    });
}