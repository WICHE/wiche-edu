/**
 * Utilities JS file originally written by Deborah Jang and Pmitchell 8/8/11 for WICHE
 */

(function($) { 
Drupal.behaviors.WICHE = function (context) {
	
	$('div.toggle_area').find('div.toggle_content').end().find('div.toggle_label').click(function() {
      $(this).next().slideToggle();
    });
	
}

/*toggle-view is implemented on the Staff Directory to show the BIO field for staff who have completed bio information and on w-sara 2nd sidebar nav and on knocking-8th*/

$(document).ready(function () {
     
    $('.toggle-view li').click(function () {
 
        var text = $(this).children('p');
 
        if (text.is(':hidden')) {
            text.slideDown('200');
            $(this).children('span').html('[&#150;]');    
        } else {
            text.slideUp('200');
            $(this).children('span').html('[+]');    
        }
         
    });
 
});
}(jQuery));

// swap layer on select
(function($) { 
$(document).ready(function () {
    $('.group').hide();
    $('#option1').show();
    $('#selectMe').change(function () {
        $('.group').hide();
        $('#'+$(this).val()).show();
    })
});
}(jQuery));

// **** remove Opacity-Filter in ie ****

function removeFilter(element) {
	if(element.style.removeAttribute){
		element.style.removeAttribute('filter');
	}
}



