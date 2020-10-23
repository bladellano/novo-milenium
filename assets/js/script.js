
$(function () {

	/* Seleciona a forma de pagamento */
	$('#billingType').change(function (e) {
		$('.wrap-form-cc').hide();
		if ($(this).val() == 'CREDIT_CARD')
			$('.wrap-form-cc').fadeIn();
	});

	/* Pegando o valor do plano*/
	$('button[data-target="#openModalPlans"]').click(function (e) {
		$('#desvalueplan').val($(this).data('value'));
		console.log("$(this).data('value') ", $(this).data('value'));
	})

	/* Mascarando campos do cartão de crédito */
	$('#number').mask('0000-0000-0000-0000');
	$('#expiryMonth').mask('00');
	$('#expiryYear').mask('0000');
	$('#ccv').mask('000');

	$('#descpf').mask('000.000.000-00', { reverse: true });
	$('#nrphone').mask('00000000000');
	$('#desnumber').mask('0000');
	$('#deszipcode').mask('00000-000');

	/* Submetendo formulário de compra do plano */
	$('.btnBuyPlans').click(function () {

		if (typeof $('#desperson').val() == 'undefined')
			return swal("Alerta!", 'Você precisa está logado!', "error");

		if ($('#billingType').val().length == 0)
			return swal("Alerta!", 'Selecione a forma de pagamento!', "error");
		$('#form-buy-plans').submit();
	})

	$('#form-buy-plans').submit(function (e) {
		e.preventDefault();
		const data = $(this).serializeArray();

		$.ajax({
			type: "POST",
			url: "/payment-plans",
			data: data,
			dataType: "json",
			async: true,
			beforeSend: function () {
				load('open');
				$('.wrap-form-cc').find('input').attr('disabled', true);
			},
			success: function (r) {
				if (r.success == false) {
					$('.wrap-form-cc').find('input').attr('disabled', false);
					return swal("Alerta!", r.msg, "error");
				} else {
					$('.modal-body .alert').fadeIn().append(`<p>${r.msg}</p>`);
					if (r.billingType == 'BOLETO') {
						$('.modal-body .alert').show()
							.append(`<p class="text-center">
							<i class="far fa-file"></i> 
							<a target="_blank" href="${r.urlBoleto}">Visualizar boleto.</a></p>`);
					}
					return swal("Sucesso!", r.msg, "success");
				}
			},
			complete: () => {
				load('close');
			}
		});
	});

	/* Deslogando o usuário */
	$('#btnLogoutUser').click(function (e) {
		e.preventDefault();
		$.post("/logout-user", [], function () { });
		$('.show-data-user').empty();
		$('.box-login').show();
		$('.header-user').hide();
	});

	/*Logando usuário/cliente*/

	$('#btnLoginPlans').click(function (e) {
		e.preventDefault();
		var data = {
			deslogin: $('#deslogin').val(),
			despassword: $('#despassword').val()
		};
		$.ajax({
			type: "POST",
			url: "/logging-in",
			data: data,
			dataType: "json",
			beforeSend: function () {
				load('open');
			},
			success: function (r) {
				if (r.success == false)
					return swal("Alerta!", r.msg, "error");

				$('.wrap-form-cc').find('input').attr('disabled', false);
				var html = '<div class="row">';
				for (const property in r.data) {
					if (property == 'desperson' || property == 'desaddress' || property == 'desemail') {
						html += `<div class="form-group col-md-12">					
						<input type="text" readonly class="form-control form-control-sm" name='${property}' id='${property}' value="${r.data[property]}"></div>`;
					} else {
						html += `<input type="hidden" class="form-control form-control-sm" name='${property}' id='${property}' value="${r.data[property]}">`;
					}
				}

				html += "</div>";

				$('.show-data-user').append(html).show();
				$('.box-login').hide();
				$('.header-user').show();
				$('#show-user').html($('#desperson').val());
			},
			complete: () => {
				load('close');
			}
		});
	});

	/* Menu Carousel */

	$('.carousel-control-next').click(function (e) {
		$('.carousel').carousel('next');
	});

	$('.carousel-control-prev').click(function () {
		$('.carousel').carousel('prev');
	});

	/* Slick Blog */
	$('.wrap-blogs ul').slick({
		centerMode: true,
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
					arrows: false,
					dots: true
				}
			},
			{
				breakpoint: 868,
				settings: {
					centerPadding: '40px',
					slidesToShow: 1,
					arrows: false,
					dots: true
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
					arrows: false,
					dots: true
				}
			},
			{
				breakpoint: 868,
				settings: {
					centerPadding: '40px',
					slidesToShow: 1,
					arrows: false,
					dots: true
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

				if (!$(this.hash).length)/* Retorna ao index se não encontrar ids */
					return location.href = location.href.match(/^http.*?.\w\//m)[0];

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

