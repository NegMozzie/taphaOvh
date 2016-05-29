$(function() {
	$('.field, textarea').focus(function() {
        if(this.title==this.value) {
            this.value = '';
        }
    }).blur(function(){
        if(this.value=='') {
            this.value = this.title;
        }
    });
    $(".flexslider").flexslider({
        animation: 'fade',
        slideshow: true,
        slideshowSpeed: 3000,         
        animationDuration: 700,     
        animationLoop: true 
    });   
    $("#project-slider").jcarousel({
        scroll: 1,
        auto: 3,
        wrap: 'both'     
    });
    if ($.browser.msie && $.browser.version.substr(0,1)<7) {
        DD_belatedPNG.fix('#wrapper-middle, #footer, .flexslider, .details,.btn-main,.flex-control-nav li a, img, .jcarousel-prev, .jcarousel-next');
    }  
});

