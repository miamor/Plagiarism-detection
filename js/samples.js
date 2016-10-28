function FormatWithOption (oldformat) {
	var newformat = "";
	indent_size = $('#index').val();
	if (!indent_size) indent_size = 0;
	newformat = js_beautify(oldformat,{
		"indent_size": indent_size,
		"indent_char": " ",
		"other": " ",
		"indent_level": 0,
		"indent_with_tabs": false,
		"preserve_newlines": true,
		"max_preserve_newlines": 2,
		"jslint_happy": true,
		"indent_handlebars": true
	});
	return newformat
}

function htmlEntities (str) {
	return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function toggle (a) {
	$(a).find('.toggle').each(function () {
		$(this).hide();
		$(this).prev('.toggle-open').prepend('<span class="fa fa-caret-right"></span> ').click(function () {
			$to = $(this);
			if ($(this).next('.toggle').is(':hidden')) {
				$to.children('.fa').removeClass('fa-caret-right').addClass('fa-caret-down');
				$to.next('.toggle').slideDown(200);
			} else {
				$to.children('.fa').removeClass('fa-caret-down').addClass('fa-caret-right');
				$to.next('.toggle').slideUp(200);
			}
		})
	});
}

$(function () {
	
/*	$('#toc').toc({
		selector: 'h2',
		elementClass: 'toc',
		ulClass: 'nav'
	});
	$('body').scrollspy({
		target: '#toc',
		offset: 70
	});
*/
	$('.pair-choose-one a').click(function () {
		$('.pair-choose-one').removeClass('active');
		$(this).parent('.pair-choose-one').addClass('active');
		$('html, body').animate({
			scrollTop: $('#result-eg').offset().top - 10
		}, 1000);
		$('#result-eg').html('<div class="center">Loading...</div>');
		$.ajax({
			url: '?p='+$(this).attr('data-p'),
			type: 'GET',
			success: function (data) {
				$('#result-eg').html(data);
				toggle('#result-eg')
			}
		});
		return false
	});
	
	$('.submit').submit(function () {
		$('#result-input').html('<div class="center">Loading...</div>');
		cont1 = $('textarea[name="cont1"]').val();
		cont2 = $('textarea[name="cont2"]').val();
		// remove #include<lib>
		var cont1 = cont1.replace(/\#include(\s)*\<(.*?)\>/g, '');
		var cont2 = cont2.replace(/\#include(\s)*\<(.*?)\>/g, '');
		cont1 = encodeURIComponent(FormatWithOption(cont1));
		cont2 = encodeURIComponent(FormatWithOption(cont2));
		$.ajax({
			url: '?do=compare',
			type: 'POST',
			data: 'cont1='+cont1+'&cont2='+cont2,
			success: function (data) {
				$('#result-input').html(data);
				toggle('#result-input')
			}
		});
		return false
	})
})
