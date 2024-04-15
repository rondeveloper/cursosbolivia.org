//----MENU---//

$('.navbar .dropdown').hover(function() {

	$(this).addClass('extra-nav-class').find('.dropdown-menu').first().stop(true, true).delay(50).slideDown();

}, function() {

	var na = $(this)

	na.find('.dropdown-menu').first().stop(true, true).delay(100).slideUp('fast', function(){ na.removeClass('extra-nav-class') })

});



//MENU RESPONSIVE

 $(document).ready(function(){

/*$("#nav").tinyNav({

  active: 'selected', // String: Set the "active" class

  header: 'MENU +', // String: Specify text for "header" and show header instead of the active item

  label: '' // String: Sets the <label> text for the <select> (if not set, no label will be added)

});*/




});

/*
$(document).scroll(function(event) {
  var wintop = $(window).scrollTop(); // Winodw Scroll Positon
  var topArea = $('.top-area').outerHeight(); // Header Logo Div Height
  if (wintop > topArea) {
	$('#c-slide').addClass('correct_slide');
  } else {
	  $('#c-slide').removeClass('correct_slide');
  }
});
*/


/*
//----HEADER---//
jQuery(window).scroll(function () {
  if (jQuery(document).scrollTop() == 0) {
    jQuery('.wowmenu').removeClass('tiny');
  } else {
    jQuery('.wowmenu').addClass('tiny');
  }
});
*/





//----FOOTER TESTIMONIAL---//  

jQuery(document).ready(function ($) {

$('.textItem').quovolver();

  });









  

  

//----TO TOP---//

jQuery(document).ready(function($){

	// hide #back-top first

	$("#back-top").hide();	

	// fade in #back-top

	$(function () {

		$(window).scroll(function () {

			if ($(this).scrollTop() > 600) {

				$('#back-top').fadeIn();

			} else {

				$('#back-top').fadeOut();

			}

		});

		// scroll body to 0px on click

		$('#back-top a').click(function () {

			$('body,html').animate({

				scrollTop: 0

			}, 800);

			return false;

		});

	});

});







	//YUMMI LOADER

	var $body = $('body');

	$(window).load(function() {

		$body.toggleClass('on off');

		$('#trigger').click(function() {

			$body.toggleClass('on off');

			setTimeout(function() {

				$body.toggleClass('on off');

			}, 2000)

		});

	}); 

	

	  

//////----Placeholder for IE---////////

$(function() {

    // Invoke the plugin

    $('input, textarea').placeholder();

  });



//----ANIMATIONS---//

jQuery(document).ready(function($){



jQuery('.animated').appear();



    jQuery(document.body).on('appear', '.fade', function() {

        jQuery(this).each(function(){ jQuery(this).addClass('anim-fade') });

    });

    jQuery(document.body).on('appear', '.slidea', function() {

        jQuery(this).each(function(){ jQuery(this).addClass('anim-slide') });

    });

    jQuery(document.body).on('appear', '.hatch', function() {

        jQuery(this).each(function(){ jQuery(this).addClass('anim-hatch') });

    });

    jQuery(document.body).on('appear', '.entrance', function() {

        jQuery(this).each(function(){ jQuery(this).addClass('anim-entrance') });

    });

	jQuery(document.body).on('appear', '.fadeInUpNow', function() {

        jQuery(this).each(function(){ jQuery(this).addClass('fadeInUp') });

    });

	jQuery(document.body).on('appear', '.fadeInDownNow', function() {

        jQuery(this).each(function(){ jQuery(this).addClass('fadeInDown') });

    });

	jQuery(document.body).on('appear', '.fadeInLeftNow', function() {

        jQuery(this).each(function(){ jQuery(this).addClass('fadeInLeft') });

    });

	jQuery(document.body).on('appear', '.fadeInRightNow', function() {

        jQuery(this).each(function(){ jQuery(this).addClass('fadeInRight') });

    });

	

	

	jQuery(document.body).on('appear', '.fadeInUpBigNow', function() {

    jQuery(this).each(function(){ jQuery(this).addClass('fadeInUpBig') });

    });

	jQuery(document.body).on('appear', '.fadeInDownBigNow', function() {

        jQuery(this).each(function(){ jQuery(this).addClass('fadeInDownBig') });

    });

	jQuery(document.body).on('appear', '.fadeInLeftBigNow', function() {

        jQuery(this).each(function(){ jQuery(this).addClass('fadeInLeftBig') });

    });

	jQuery(document.body).on('appear', '.fadeInRightBigNow', function() {

        jQuery(this).each(function(){ jQuery(this).addClass('fadeInRightBig') });

    });

	

	jQuery(document.body).on('appear', '.fadeInNow', function() {

        jQuery(this).each(function(){ jQuery(this).addClass('fadeIn') });

    });

	jQuery(document.body).on('appear', '.flashNow', function() {

        jQuery(this).each(function(){ jQuery(this).addClass('flash') });

    });

	jQuery(document.body).on('appear', '.shakeNow', function() {

        jQuery(this).each(function(){ jQuery(this).addClass('shake') });

    });

	jQuery(document.body).on('appear', '.bounceNow', function() {

        jQuery(this).each(function(){ jQuery(this).addClass('bounce') });

    });

	jQuery(document.body).on('appear', '.tadaNow', function() {

        jQuery(this).each(function(){ jQuery(this).addClass('tada') });

    });

	jQuery(document.body).on('appear', '.swingNow', function() {

        jQuery(this).each(function(){ jQuery(this).addClass('swing') });

    });

});







jQuery(document).ready(function($){

var domain = $('#domain').html();

		$('#frmBusca').submit(function(e) {

			e.preventDefault();

			var cadena = $('#busca_curso').val();

			cadena = to_search(cadena);

		$('#frmBusca').attr('action',domain+'/busqueda/'+cadena+'/');

			 document.frmBusca.submit();

			

		});

	

});







function to_search(str) {

  str = str.replace(/^\s+|\s+$/g, ''); // trim

//  str = str.toLowerCase();

  

  // remove accents, swap ñ for n, etc

  var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";

  var to   = "aaaaeeeeiiiioooouuuunc------";

  for (var i=0, l=from.length ; i<l ; i++) {

    str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));

  }



  str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars

//    .replace(/\s+/g, '-') // collapse whitespace and replace by -

    .replace(/-+/g, '-'); // collapse dashes



  return str;

}

