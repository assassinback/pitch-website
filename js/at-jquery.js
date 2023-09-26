document.documentElement.addEventListener('touchstart', function(event) {
    if (event.touches.length > 1) {
        event.preventDefault();
    }
}, false);

jQuery(document).ready(function ($) {
	$(".p_header .p_hdr_lft .p_menu a").click(function(){
		$(".mnv_menu_wrap").toggleClass("mnv_menu_wrap_op");
		$(".mnv_menu_wrap .mnv_menu_inn").toggleClass("mnv_menu_inn_op");
		$(".mnv_menu_wrap .mnv_menu_inn .mnv_nav li").each(function(i) {
         $(this).delay(100 * i).fadeToggle(2200);
        });
	});
	$(".a_close").click(function(){
		$(".mnv_menu_wrap").toggleClass("mnv_menu_wrap_op");
		$(".mnv_menu_wrap .mnv_menu_inn").toggleClass("mnv_menu_inn_op");
		$(".mnv_menu_wrap .mnv_menu_inn .mnv_nav li").each(function(i) {
         $(this).delay(100 * i).fadeToggle(2200);
        });
	});
});


$(window).load(function(){ 

			  $.fn.extend({
	equalHeights: function(){
		var top=0;
		var row=[];
		var classname=('equalHeights'+Math.random()).replace('.','');
		$(this).each(function(){
			var thistop=$(this).offset().top;
			if (thistop>top) {
				$('.'+classname).removeClass(classname); 
				top=thistop;
			}
			$(this).addClass(classname);
			$(this).height('auto');
			var h=(Math.max.apply(null, $('.'+classname).map(function(){ return $(this).outerHeight(); }).get()));
			$('.'+classname).height(h);
		}).removeClass(classname); 
	}	   
});

$(function(){
  $(window).resize(function(){
    
	$('.blog_dv ul li .blog_img').equalHeights();
	$('.blog_dv ul li .blog_con h3').equalHeights();
	$('.blog_dv ul li .blog_con p').equalHeights();
	$('.rate_test_inn').equalHeights();
		}).trigger('resize');
});
			  
});

 $(document).on('click', '.mnv_nav li .fa' ,function(){
	$(this).toggleClass('active');
    $(this).parent().find("ul").slideToggle();
});
 
