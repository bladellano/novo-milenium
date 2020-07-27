
$(function(){

	$('.carousel-control-next').click(function(){
		$('.carousel').carousel('next');
	});

	$('.carousel-control-prev').click(function(){
		$('.carousel').carousel('prev');
	});

	/* Expande e recolhe o conteudo da trajetória */
	$('.btn-read-more').click(function() {

		$('.row.about.trajectory').toggleClass("about-collapse");  

		if($('.row.about.trajectory').hasClass("about-collapse")){
			$('.btn-read-more').html('Leia mais').prepend('<i class="fas fa-chevron-down"></i> ');
		} else {
			$('.btn-read-more').html('Guardar').prepend('<i class="fas fa-chevron-up"></i> ');
		}
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
	$('#form-contact').submit(function(e) {
		e.preventDefault();

		const data = $(this).serializeArray();
		let val_btn = $(this).find('button').html();

		$.ajax({
			url: 'send-contact.php',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend:()=>{
				$(this).find(':input,select').prop('disabled',true);				
				$(this).find('button').html('Enviando').append(' <i class="fas fa-spinner fa-spin"></i>');
			},
			success:(r)=>{

				if(r.status == true){
					$(this)[0].reset();
					return alertify.success(r.message + ' Logo entraremos em contato.');
				} else {
					return alertify.error(r.message + ' Tente entrar em contato através do Whatsapp (91) 9 82650277');
				}
			}
		})
		.always(()=> {
			$(this).find(':input,select').prop('disabled',false);	
			$(this).find('button').html(val_btn);
		});

	});


	/* Botão que surge no rodapé p/ levar até o topo.*/
	$(window).scroll(function(e) {
		if ($(this).scrollTop() > window.innerHeight) {
			$('.topo').fadeIn();
		} else {
			$('.topo').fadeOut();
		}
	});

	$('.topo').click(function(e) {
		e.preventDefault();
		$('html, body').animate({
			scrollTop: 0
		}, 500)
	});

	/* Fixed navbar */
	$(window).scroll(function() {
		if ($(document).scrollTop() > 150) {
			$('.navbar').addClass('navbar-shrink');
			$('.navbar-brand > img').addClass('reduce-logo');
		}
		else {
			$('.navbar').removeClass('navbar-shrink');
			$('.navbar-brand > img').removeClass('reduce-logo');
		}
	});

	$(function() {
		$('a[href*="#"]:not([href="#"])').click(function() {
			$('.nav-item').removeClass('active');
			$(this).parent().addClass('active');

			if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
				var target = $(this.hash);
				var height = $('.navbar').height();
				target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
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