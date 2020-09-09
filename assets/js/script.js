
$(function () {

	/* Effect Reveal */
	window.sr = ScrollReveal({ reset: true });

    sr.reveal('.reveal-video,.reveal-about,#services .reveal-first,#services .reveal-second,.list-cards-plans,.wrap-convenios,.wrap-blogs,.slick-depositions,.slick-parceiros,#accordion,#form-contact,footer .container', {
        delay: 400,
        scale: 0
	});	

	/* Menu Carousel */

	$('.carousel-control-next').click(function () {
		$('.carousel').carousel('next');
	});

	$('.carousel-control-prev').click(function () {
		$('.carousel').carousel('prev');
	});


	/* Slick Blog */
	$('.wrap-blogs ul').slick({
		infinite: true,
		slidesToShow: 3,
		slidesToScroll: 3,
		responsive: [
		{
			breakpoint: 1048,
			settings: {
				centerMode: true,
				centerPadding: '40px',
				slidesToShow: 2,
				arrows:false,
				dots:true
			}
		},
		{
			breakpoint: 868,
			settings: {
				centerPadding: '40px',
				slidesToShow: 1,
				arrows:false,
				dots:true
			}
		}
		]
	});
	/* Slick parceiros */
	$('.list-cards-plans').slick({
		infinite: true,
		slidesToShow: 3,
		slidesToScroll: 3,
		responsive: [
		{
			breakpoint: 1048,
			settings: {
				centerMode: true,
				centerPadding: '40px',
				slidesToShow: 2,
				arrows:false,
				dots:true
			}
		},
		{
			breakpoint: 868,
			settings: {
				centerPadding: '40px',
				slidesToShow: 1,
				arrows:false,
				dots:true
			}
		}
		]
	});

	/* Slick parceiros */
	$('.slick-depositions').slick();

	/* Slick parceiros */
	$('.slick-parceiros').slick({
		infinite: true,
		slidesToShow: 5,
		slidesToScroll: 3,
		/*centerMode: true,*/
		responsive: [
		{
			breakpoint: 768,
			settings: {
				arrows: false,
				centerMode: true,
				centerPadding: '40px',
				slidesToShow: 3
			}
		},
		{
			breakpoint: 480,
			settings: {
				arrows: false,
				centerMode: true,
				centerPadding: '40px',
				slidesToShow: 1
			}
		}
		]
	});

	/* Formulário de envio de tentatica de contato */
	$('#form-contact').submit(function (e) {
		e.preventDefault();

		const data = $(this).serializeArray();
		let val_btn = $(this).find('button').html();

		$.ajax({
			url: '/email-sent',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: () => {
				$(this).find(':input,select').prop('disabled', true);
				$(this).find('button').html('Enviando').append(' <i class="fas fa-spinner fa-spin"></i>');
			},
			success: (r) => {

				if (r.success == true) {
					$(this)[0].reset();
					return alertify.success(r.msg + ' Logo entraremos em contato.');
				} else {
					return alertify.error(r.msg + ' Tente entrar em contato através do Whatsapp (91) 9 8265-0277');
				}
			}
		})
		.always(() => {
			$(this).find(':input,select').prop('disabled', false);
			$(this).find('button').html(val_btn);
		});

	});


	/* Botão que surge no rodapé p/ levar até o topo.*/
	$(window).scroll(function (e) {
		if ($(this).scrollTop() > 4500) {
			$('.topo').fadeIn();
		} else {
			$('.topo').fadeOut();
		}
	});

	$('.topo').click(function (e) {
		e.preventDefault();
		$('html, body').animate({
			scrollTop: 0
		}, 500)
	});

	/* Fixed navbar */
	$(window).scroll(function () {
		if ($(document).scrollTop() > 150) {
			$('.navbar').addClass('navbar-shrink');
			$('.navbar-brand > img').addClass('reduce-logo');
		}
		else {
			$('.navbar').removeClass('navbar-shrink');
			$('.navbar-brand > img').removeClass('reduce-logo');
		}
	});

	$(function () {
		$('a[href*="#"]:not([href="#"])').click(function () {
			$('.nav-item').removeClass('active');
			$(this).parent().addClass('active');

			if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
				var target = $(this.hash);
				var height = $('.navbar').height();
				target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
				if (target.length) {
					$('html,body').animate({
						scrollTop: target.offset().top - height
					}, 1000);
					return false;
				}
			}
		});
	});

});//Fim