$( window ).on('load', function() {
	if($('#login_count').val() == 1)
	{
		$('#loader').stop(true).delay(200).fadeTo(200, 0, function(){
			$(this).css('display', 'none');
		});
	}
	else
	{
		$('#loader').css('display', 'none');
	}
});

$(document).ready(function(){

	var transition_delay = 200;
	var fade_delay = 300;

	var formLogin = 0;
	var userMenu = 0;
	var preventiva = 0;
	var editarPerfil = 0;
	var alertAntecipado = 0;
	var stepRel = 0;
	var btnNotify = 0;
	var notify = 0;
	var nNotify = 3;
	var hoverSubiUsuarios = 0;
	var hoverItemUserMenu = 0;
	var formCadManChose = 0;
	var cadVlrTotal = 0;
	var notify_pendente = 0;
	var notify_naocadastrada = 0;
	var notify_cadastrada = 0;

	var nivel_sistema = $('#versao').val();
	var nivel_front = parseInt($('#nivel_front').val());
	var nivel_back = parseInt($('#nivel_back').val());
	var nivel_database = parseInt($('#nivel_database').val());

	var clock;

	var checkTodos = $("#marcartodos");

	var base_url = 'http://localhost/mechawork';

	$.ajax({
		url: base_url + '/sistema/geturlbase',
		dataType: 'json',
		success: function(data)
		{
			base_url = data.url_base;
		}
	});

	feather.replace();

	dataHoje = (function () {
	  var data = new Date(),
	    dia = data.getDate(),
	    mes = data.getMonth() + 1,
	    ano = data.getFullYear();
	  return [dia, mes, ano].join('/');
	});


	$('#logo_login').fadeIn(fade_delay);

	$('[data-toggle="tooltip"]').tooltip();

	$('[data-toggle2="tooltip"]').tooltip();

  	$('#versao-menu').tooltip({
		title: 'Verifique o nosso andamento aqui!',
		placement: 'bottom',
		trigger: 'hover'
	});

	$('#email').change(function(){
		if($(this).val() != "")
		{
			$('#form-login').addClass('was-validated');
			$(this).addClass('is-invalid');
			$('#senha').addClass('is-invalid');
		}
		else
		{
			$('#form-login').removeClass('was-validated');
			$(this).revomeClass('is-invalid');
			$('#senha').removeClass('is-invalid');
		}
	});

	$('#icon-form').click(function() {

		if(formLogin == 0)
		{
			$('.login_home .login_form')
				.animate({left: "+=25%"}, transition_delay);

			$('#icon-form')
				.animate({left: "+=25%"}, transition_delay);

			$('#powered')
				.animate({left: "+=12%"}, transition_delay);

			$('#logo_login')
				.animate({left: "+=12%"}, transition_delay);

			formLogin += 1;
		}

		else
		{
			$('.login_home .login_form')
				.animate({left: "0%"}, transition_delay);

			$('#icon-form')
				.animate({left: "0%"}, transition_delay);

			$('#powered')
				.animate({left: "0%"}, transition_delay);

			$('#logo_login')
				.animate({left: "0%"}, transition_delay);

			formLogin = 0;
		}
	});

	$('#btn-config-preventiva').click(function(){
		if(preventiva == 0)
		{
			$('#preventiva .box-preventiva').stop(true).fadeTo(fade_delay, 0);
			$('#form-preventiva').stop(true).fadeTo(fade_delay, 1);
			preventiva += 1;
		}
	});

	$('#form-preventiva-exit').click(function(){
		if(preventiva != 0)
		{
			$('#form-preventiva').stop(true).fadeTo(fade_delay, 0);
			$('#preventiva .box-preventiva').stop(true).fadeTo(fade_delay, 1);
			preventiva = 0;
		}
	});

	$('#gerarSenha').click(function(){

		var senha = Math.floor(Math.random() * 1000 + 9999);

		$('#user-senha').val(senha);
		$('#user-cSenha').val(senha);

	});

	$('#btn-editar-perfil').click(function(){

		$('#perfil-senhas').stop(true).fadeTo(fade_delay, 1);
		$('#form-profile div div input').prop("disabled", false);
		$('#btn-perfil-senhas-group div').addClass('btn-group').addClass('btn-block');
		$('#btn-cancel-ed-prf').stop(true).fadeTo(fade_delay, 1);

		$(this).fadeTo(fade_delay, 0).css('display', 'none');
		$('#btn-salvar-perfil').fadeTo(fade_delay, 1);
		editarPerfil += 1;

	});

	$('#btn-cancel-ed-prf').click(function(){
		if(editarPerfil > 0)
		{
			$('#perfil-senhas').stop(true).fadeTo(fade_delay, 0).css('display', 'none');
			$('#form-profile div div input').prop("disabled", true);
			$('#btn-perfil-senhas-group div').removeClass('btn-group').removeClass('btn-block');


			$('#btn-editar-perfil').fadeTo(fade_delay, 1);
			$(this).fadeTo(fade_delay, 0).css('display', 'none');
			$('#btn-salvar-perfil').fadeTo(fade_delay, 0).css('display', 'none');
			editarPerfil = 0;
		}
	});

	/* VERSÃO E DESENVOLVIMENTO DO SISTEMA */
	$('#versao-menu').click(function(){
		Swal.fire({
			title: 'Nossos Avanços',

			html: '<h4><span class="badge badge-pill badge-success">Desenvolvimento Concluído</span></h4></br>'+
			'Versão do Sistema: V'+parseInt((nivel_front+nivel_back+nivel_database)/3)+'.'+nivel_sistema+'</br>'+
			'<hr class="my-2">'+
			'<canvas id="nivel_chart"></canvas>',

			type: 'info',

			onBeforeOpen: () => {
				var ctx = document.getElementById('nivel_chart').getContext('2d');
				var chart = new Chart(ctx, {
					// The type of chart we want to create
					type: 'bar',

					// The data for our dataset
					data: {
						labels: ['Visual', 'Processo', 'Banco de dados'],
						datasets: [{
							label: 'Visual (%)',
							backgroundColor: 'blue',
							data: [nivel_front, ,]
						}, {
							label: 'Processo (%)',
							backgroundColor: 'green',
							data: [, nivel_back, ]
						}, {
							label: 'Banco de dados (%)',
							backgroundColor: 'red',
							data: [, , nivel_database]
						}]
					},

					// Configuration options go here
					options: {
						legend: {
							display: false
						},
						title: {
							display: true,
							text: 'NDpA - Nível de Desenvolvimento por Área'
						},
						scales: {
							xAxes: [{
								stacked: true
							}],
							yAxes: [{
								stacked: true
							}]
						}
					}
				});
			}
		});
	});

	checkTodos.click(function () {

		if ( $(this).is(':checked') )
		{

			$('input:checkbox').prop("checked", true);

		}

		else
		{

			$('input:checkbox').prop("checked", false);

		}

	});

	$('#btn-alerta-antecipado').click(function(){

		if(alertAntecipado == 0)
		{
			$('#alerta-antecipado').stop(true).fadeTo(fade_delay, 1).css('display', 'block');
			$(this).removeClass('btn-outline-primary').addClass('btn-outline-secondary').text('Cancelar Configuração');
			alertAntecipado++;
		}
		else
		{
			$('#alerta-antecipado').stop(true).fadeTo(fade_delay, 0).css('display', 'none');
			$(this).removeClass('btn-outline-secondary').addClass('btn-outline-primary').text('Configurar Alerta Antecipado');
			alertAntecipado = 0;
		}

	});

	$('#email').tooltip({
		trigger: 'manual',
		title: 'Informe o E-mail!',
		placement: 'left'
	});

	$('#btn-ajudaLogin').click(function(){

		var email = $('#email').val();

		if(email != "")
		{
			$('#email')
				.removeClass('is-invalid')
				.tooltip('hide');

			$('#email-contato').val(email);
			$('#modalAjuda').modal('show');
		}

		else
		{
			$('#email')
				.addClass('is-invalid')
				.tooltip('show');
		}

	});

	$('#user-icon').click(function(){

		if(userMenu == 0)
		{
			$('#user-menu').animate({right: "+=0%"}, transition_delay).addClass('shadow-lg');
			userMenu ++;
		}

		else
		{
			$('#user-menu').animate({right: "-=20%"}, transition_delay).removeClass('shadow-lg');
			userMenu = 0;
		}

	});

	$('#conteudo').click(function(){
		if(userMenu > 0)
		{
			$('#user-menu').animate({right: "-=20%"}, transition_delay).removeClass('shadow-lg');
			userMenu = 0;
		}
	});

	$('#btn-notify').click(function(){

		if(notify == 0)
		{
			$('#notify').animate({left: "+=0%"}, transition_delay);
			$(this).animate({left: "+=32%"}, transition_delay);
			$('#bg-notify').fadeTo(fade_delay, 1);
			$(this).rotate(180);
			notify ++;
			$('#btn-notify h5 span').css({display: 'none'});
		}

		else
		{
			$('#notify').animate({left: "-=30%"}, transition_delay);
			$(this).animate({left: "+=2%"}, transition_delay);
			$('#bg-notify').fadeTo(fade_delay, 0, function(){
				$(this).css('display', 'none');
			});
			$(this).rotate(0);
			notify = 0;
		}

	});

	$('#bg-notify').click(function(){
		$('#notify').animate({left: "-=30%"}, transition_delay);
		$('#btn-notify').animate({left: "+=2%"}, transition_delay);
		$(this).fadeTo(fade_delay, 0, function(){
			$(this).css('display', 'none');
		});
		$('#btn-notify').rotate(0);
		notify = 0;
	});

	jQuery.fn.rotate = function(degrees) {
	    $(this).css({'transform' : 'rotate('+ degrees +'deg)'});
	    return $(this);
	};


	$('#editar_usuario').click(function(){

		$('#verUsuarioModalTitle').text('Editar Usuario');

		$('#id_usuario').prop("disabled", false);
		$('#edit_nome').prop("disabled", false);
		$('#edit_sobrenome').prop("disabled", false);
		$('#edit_telefone').prop("disabled", false);
		$('#edit_nivel').prop("disabled", false);

		$('#id_usuario').attr({required: "true"});
		$('#edit_nome').attr({required: "true"});
		$('#edit_sobrenome').attr({required: "true"});
		$('#edit_telefone').attr({required: "true"});
		$('#edit_nivel').attr({required: "true"});

		$('#form-editUsuario').addClass('was-validated');

		$(this).addClass('d-none');
		$('#cancelar_edicao').removeClass('d-none');
		$('#salvar_alteracoes').removeClass('d-none');
	});

	$('#cancelar_edicao').click(function(){

		$('#verUsuarioModalTitle').text('Visualizar Usuário');

		$('#id_usuario').prop("disabled", true);
		$('#edit_nome').prop("disabled", true);
		$('#edit_sobrenome').prop("disabled", true);
		$('#edit_telefone').prop("disabled", true);
		$('#edit_nivel').prop("disabled", true);


		$('#id_usuario').attr({required: "false"});
		$('#edit_nome').attr({required: "false"});
		$('#edit_sobrenome').attr({required: "false"});
		$('#edit_telefone').attr({required: "false"});
		$('#edit_nivel').attr({required: "false"});

		$('#form-editUsuario').removeClass('was-validated');

		$(this).addClass('d-none');
		$('#salvar_alteracoes').addClass('d-none');
		$('#editar_usuario').removeClass('d-none');
	});

	const Toast = Swal.mixin({
		toast: true,
		position: 'bottom-end',
		showConfirmButton: false,
		timer: 3000
	});

	populaConsultaFrota = (function(){
		$.ajax({
			url: base_url + '/sistema/populaConsulta',
			dataType: 'json',
			async: true,
			beforeSend: function()
			{
				Swal.fire({
					title: 'Carregando...',
					timer: 15000,
					onBeforeOpen: () => {
						Swal.showLoading();
					},
					onClose: () => {
						Swal.fire({
							title: 'Tempo limite excedido!',
							timer: 1500,
							showConfirmButton: false,
							type: 'error',
							onClose: () => {
								window.location.reload();
							}
						})
					}
				});
			},
			success: function(data)
			{
				if(data.frotas.length != 0)
				{

					var htmlString = '';

					var count = 0;

					$.each(data.frotas, function(i, item){

						count ++;

						htmlString +=
						'<tr>'
							+'<td class="placa">'+item.placa_numero+'</td>'
							+'<td>'+item.descricao+'</td>'
						+'</tr>';
					});

					$('#tbl_consultaFrota tbody').html(htmlString);

					Toast.fire({
						title: 'Frota Carregada!',
						type: 'success'
					});

				}
				else
				{
					Swal.fire({
						title: 'Não há registros!',
						timer: 1500,
						showConfirmButton: false,
						type: 'warning',
						onClose: () =>
						{
							$('#consultaFrotaModal').modal('hide');
						}
					})
				}
			}
		});
	});

	populaConsultaProduto = (function(){
		$.ajax({
			url: base_url + '/sistema/populaConsulta',
			dataType: 'json',
			async: true,
			beforeSend: function()
			{
				Swal.fire({
					title: 'Carregando...',
					timer: 15000,
					onBeforeOpen: () => {
						Swal.showLoading();
					},
					onClose: () => {
						Swal.fire({
							title: 'Tempo limite excedido!',
							timer: 1500,
							showConfirmButton: false,
							type: 'error',
							onClose: () => {
								window.location.reload();
							}
						})
					}
				});
			},
			success: function(data)
			{
				if(data.produtos.length != 0)
				{

					var htmlString = '';

					var count = 0;

					$.each(data.produtos, function(i, item){

						count ++;

						htmlString +=
						'<tr>'
							+'<td>'+count+'</td>'
							+'<td class="codigo">'+item.codigo+'</td>'
							+'<td>'+item.descricao+'</td>'
						+'</tr>';
					});

					$('#tbl_consultaProdutoModal tbody').html(htmlString);

					Toast.fire({
					  type: 'success',
					  title: 'Produtos Carregados!'
					})

				}
				else
				{
					Swal.fire({
						title: 'Não há registros!',
						timer: 1500,
						showConfirmButton: false,
						type: 'warning',
						onClose: () =>
						{
							$('#consultaProdutoModal').modal('hide');
						}
					})
				}
			}
		});
	});

	$('#tbl_consultaProdutoModal tbody').on('click', 'tr', function(){

			var valor = $(this).find('.codigo').text();

			if (valor != "")
			{
				$('#btnUsarProduto')
					.prop('disabled', false)
					.text('Usar '+valor);

				$('#tbl_consultaProdutoModal input[name=valor]').val(valor);
				$('#btnResetUsarProduto').prop({disabled: false});

				$('#tbl_consultaProdutoModal tbody tr').tooltip('hide');
				thisHtml = '<tr class="linha-selecionada">' + $(this).html() + '</tr>';
				$('#tbl_consultaProdutoModal tbody').html(thisHtml);

				Toast.fire({
				  type: 'success',
				  title: 'Produto Selecionado!'
				})

			}

	});

	$('#btnUsarProduto').on('click', function() {

			var valor = $('#tbl_consultaProdutoModal input[name=valor]').val();

			$('#ape_codproduto').val(valor).focus();
			$('#ap_codproduto').val(valor).focus();

			Toast.fire({
				type: 'success',
				title: 'Produto pronto para adicionar!'
			});

	});

	$('#btnResetUsarProduto').on('click', function(){

		populaConsultaProduto();

		$(this).prop({disabled: true});
		$('#btnUsarProduto').text('Selecione uma linha').prop({disabled: true});
	});

	$('#tbl_consultaFrota tbody').on('click', 'tr', function(){

			var valor = $(this).find('.placa').text();

			if (valor != "")
			{

				$('#btnUsarFrota')
					.prop('disabled', false)
					.text('Usar '+valor);

				$('#tbl_consultaFrota input[name=valor]').val(valor);

				$('#btnResetUsarFrota').prop('disabled', false);

				$('#tbl_consultaFrota tbody tr').tooltip('hide');
				thisHtml = '<tr class="linha-selecionada">' + $(this).html() + '</tr>';
				$('#tbl_consultaFrota tbody').html(thisHtml);

				Toast.fire({
					type: 'success',
					title: 'Veículo/Equipamento selecionado!'
				});

			}

	});

	$('#btnUsarFrota').on('click', function() {

			var valor = $('#tbl_consultaFrota input[name=valor]').val();

			$('#placaManutencao').val(valor).focus();
			$('#form_prev_placa').val(valor).focus();

			Toast.fire({
				type: 'success',
				title: 'Veículo/Equipamento adicionado!'
			});

	});

	$('#btnResetUsarFrota').on('click', function(){

		populaConsultaFrota();

		$(this).prop({disabled: true});
		$('#btnUsarFrota').text('Selecione uma linha').prop({disabled: true});

	});

	$('#tipoManutencao').on('change', function(){

		var tipo = $(this).val();

		if(parseInt(tipo) == 1)
		{
			$('#form-cadMan input[name=tipo]').val(tipo);
			$('#form-cadMan_Interna').fadeTo(fade_delay, 1);
			$('#form-cadMan_Externa').fadeTo(fade_delay, 0).css('display', 'none');
			$('#form-cadMan_Interna input').prop('disabled', false);
			$('#form-cadMan_Interna select').prop('disabled', false);
			$('#form-cadMan_Interna textarea').prop('disabled', false);
			$('#form-cadMan_Externa input').prop('disabled', true);
			$('#form-cadMan_Externa select').prop('disabled', true);
			$('#form-cadMan_Externa textarea').prop('disabled', true);
			$('#form-cadMan-add-produto').prop('disabled', false);
			$('#form-cadMan-remove-produto').prop('disabled', false);
		}
		else
		{
			$('#form-cadMan input[name=tipo]').val(tipo);
			$('#form-cadMan_Interna').fadeTo(fade_delay, 0).css('display', 'none');
			$('#form-cadMan_Externa').fadeTo(fade_delay, 1);
			$('#form-cadMan_Interna input').prop('disabled', true);
			$('#form-cadMan_Interna select').prop('disabled', true);
			$('#form-cadMan_Interna textarea').prop('disabled', true);
			$('#form-cadMan_Externa input').prop('disabled', false);
			$('#form-cadMan_Externa select').prop('disabled', false);
			$('#form-cadMan_Externa textarea').prop('disabled', false);
			$('#form-cadMan-add-produto').prop('disabled', true);
			$('#form-cadMan-remove-produto').prop('disabled', true);
		}

	});

	$('#gerarRelatorio').on('change', 'select[name=tipo_relatorio]', function(){

		var tipo = $(this).val();

		var placeholder = '';

		if(parseInt(tipo) == 0)
		{
			$('#gerarRelatorio_filtrarSim').addClass('d-none').find('input[type=checkbox]').prop({checked: false});
			$('#gerarRelatorio_periodoSim').addClass('d-none').find('input[type=checkbox]').prop({checked: false});
			$('#gerarRelatorio_parametro').addClass('d-none');
			$('#gerarRelatorio_periodo').addClass('d-none');
			$('#gerarRelatorio_tipoFrota').addClass('d-none');
		}
		else if(parseInt(tipo) == 1)
		{
			$(this).tooltip('hide');
			$('#gerarRelatorio :submit').prop('disabled', false).text('Gerar Relatório');
			placeholder = 'Placa/Número';
			$('#gerarRelatorio_filtrarSim').removeClass('d-none');
			$('#gerarRelatorio_periodoSim').removeClass('d-none');
			$('#gerarRelatorio_tipoFrota').removeClass('d-none');
		}
		else if(parseInt(tipo) == 2)
		{
			$(this).tooltip('hide');
			$('#gerarRelatorio :submit').prop('disabled', false).text('Gerar Relatório');
			placeholder = 'Produto';
			$('#gerarRelatorio_filtrarSim').removeClass('d-none');
			$('#gerarRelatorio_periodoSim').removeClass('d-none');
		}
		else
		{
			$(this).tooltip('hide');
			$('#gerarRelatorio :submit').prop('disabled', false).text('Gerar Relatório');
			$('#gerarRelatorio_filtrarSim').removeClass('d-none');
			$('#gerarRelatorio_periodoSim').removeClass('d-none');
			$('#gerarRelatorio_tipoFrota').removeClass('d-none');
			placeholder = 'Código';
			$(this).tooltip('show');
			$('#gerarRelatorio :submit').prop('disabled', true).text('Selecione um tipo');
		}

		$('#relatorio_parametro').attr({placeholder: placeholder}).prop('required', true);

	});

	$('#gerarRelatorio select[name=tipo_relatorio]').tooltip({
		title: 'Escolha o tipo de relatório!',
		placement: 'bottom',
		trigger: 'manual'
	});

	$('#gerarRelatorio').on('change', 'input[name=filtrar_sim]', function(){

		var controle = $(this);

		var tipo = $('#gerarRelatorio select[name=tipo_relatorio]');


		if(controle.is(':checked'))
		{
			if($('#gerarRelatorio select[name=tipo_relatorio]').val() == 0)
			{
				$(this).prop('checked', false);
				$('#gerarRelatorio select[name=tipo_relatorio]').tooltip('show');
			}
			else
			{
				$('#gerarRelatorio_parametro').removeClass('d-none');
				$('#gerarRelatorio_parametro').find('input[name=parametro]').prop({required: true, disabled: false});
				$('#gerarRelatorio :submit').prop('disabled', true).text('Aguardando Validação');
			}
		}
		else
		{
			$('#gerarRelatorio_parametro').addClass('d-none');
			$('#gerarRelatorio_parametro').find('input[name=parametro]').val('').removeClass('is-valid').removeClass('is-invalid').prop({required: false, disabled: true});
			if($('#gerarRelatorio input[name=periodo_sim]').is(':checked'))
			{
				if(($('#gerarRelatorio_periodo').find('input[name=periodo-inicio]').val() != '')&&($('#gerarRelatorio_periodo').find('input[name=periodo-fim]').val() != ''))
				{
					$('#gerarRelatorio :submit').prop('disabled', false).text('Gerar Relatório');
				}
				else
				{
					$('#gerarRelatorio :submit').prop('disabled', true).text('Aguardando Período');
				}
			}
			else
			{
				$('#gerarRelatorio :submit').prop('disabled', false).text('Gerar Relatório');
			}
		}

	});

	$('#gerarRelatorio').on('change', 'input[name=periodo_sim]', function(){

		var controle = $(this);

		if(controle.is(':checked'))
		{
			if($('#gerarRelatorio select[name=tipo_relatorio]').val() == 0)
			{
				$(this).prop('checked', false);
				$('#gerarRelatorio select[name=tipo_relatorio]').tooltip('show');
			}
			else
			{
				$('#gerarRelatorio_periodo').removeClass('d-none');
				$('#gerarRelatorio_periodo').find('input[name=periodo-inicio]').val('').removeClass('is-invalid').removeClass('is-valid').prop('required', true);
				$('#gerarRelatorio_periodo').find('input[name=periodo-fim]').val('').removeClass('is-invalid').removeClass('is-valid').prop('required', true);
				$('#gerarRelatorio :submit').prop('disabled', true).text('Aguardando Período');
			}
		}
		else
		{
			$('#gerarRelatorio_periodo').addClass('d-none');
			$('#gerarRelatorio_periodo').find('input[name=periodo-inicio]').prop('required', false);
			$('#gerarRelatorio_periodo').find('input[name=periodo-fim]').prop('required', false);
			if($('#gerarRelatorio select[name=tipo]').val() != 0)
			{
				if($('#filtrarRelatorio').is(':checked'))
				{
					if($('#gerarReltorio input[name=parametro]').hasClass('is-valid'))
					{
						$('#gerarRelatorio :submit').prop('disabled', false).text('Gerar Relatório');
					}
					else
					{
						$('#gerarRelatorio :submit').prop('disabled', true).text('Aguardando Validação');
					}
				}
				else
				{
					$('#gerarRelatorio :submit').prop('disabled', false).text('Gerar Relatório');
				}
			}
			else
			{
				$('#gerarRelatorio :submit').prop('disabled', true).text('Aguardando tipo');
			}
		}

	});

	$('#gerarRelatorio').on('change', 'input[name=periodo-inicio]', function(){

		var fim = $('#gerarRelatorio input[name=periodo-fim]');
		var inicio = $(this);

		if(new Date(inicio.val()) < new Date(fim.val()))
		{
			inicio.removeClass('is-invalid').addClass('is-valid');
			fim.removeClass('is-invalid').addClass('is-valid');
			$('#gerarRelatorio :submit').prop('disabled', false).text('Gerar Relatório');
		}
		else
		{
			inicio.removeClass('is-valid').addClass('is-invalid');
			fim.removeClass('is-valid').addClass('is-invalid');
			$('#gerarRelatorio :submit').prop('disabled', true).text('Aguardando Período');
		}

	});

	$('#gerarRelatorio').on('change', 'input[name=periodo-fim]', function(){

		var inicio = $('#gerarRelatorio input[name=periodo-inicio]');
		var fim = $(this);

		if(new Date(inicio.val()) < new Date(fim.val()))
		{
			inicio.removeClass('is-invalid').addClass('is-valid');
			fim.removeClass('is-invalid').addClass('is-valid');
			$('#gerarRelatorio :submit').prop('disabled', false).text('Gerar Relatório');
		}
		else
		{
			$('#gerarRelatorio :submit').prop('disabled', true).text('Aguardando Período');
		}

	});

	$('input[type=number]').keyup(function(e) {
		var code = e.keyCode || e.which;
		if(code == 109 || code == 189) { //Enter keycode
			 //Do something
				var valor = $(this).val();
				$(this).val(valor.replace(/[-]/g, ''))
		}
	});

	$('input[type=number]').change(function(e) {
		 var valor = $(this).val();
		 $(this).val(valor.replace(/[-]/g, ''))
	});

	$('#form-editarAgendamento input[name=data-minima]').on('change', function(){

		var km = $('#form-prev').find('input[name=km]');
		var dt_minima = $(this);

		if((new Date(dt_minima.val()) != '')&&(dt_minima.length > 8))
		{
				alert(dt_minima.val());
		}

	});

	$('#pesquisa_notas ').on('change', 'select[name=campo]', function(){

		var campo = $(this).val();

		if((campo == 'emissao')||(campo == 'vencimento'))
		{
			$('#pesquisa_notas input[name=valor]').attr('type', 'date');
		}
		else
		{
			$('#pesquisa_notas input[name=valor]').attr('type', 'text');
		}

	});

	$('#pesquisa_fornecedores').on('change', 'select[name=campo]', function(){

		var valor = $(this).val();

		if ((valor == 'cnpj')||(valor == 'telefone'))
		{
			$('#pesquisa_fornecedores input[name=valor]')
			.prop('type', 'number')
			.prop('placeholder', 'Digite algo... (Somente números)')
			.prop('min', 1);
		}
		else
		{
			$('#pesquisa_fornecedores input[name=valor]')
			.prop('type', 'text')
			.prop('placeholder', 'Digite algo...')
			.tooltip('hide');
		}

	});

	$('#pesquisa_fornecedores').on('keypress', 'input[name=valor]', function(e){

		var select = $('#pesquisa_fornecedores select[name=campo]');
		var valor = $(this).val();

		if(select.val() == 'cnpj')
		{
			if(!valor.length > 0)
			{
				if(e.key == '0')
				{
					$(this).tooltip({
						title: 'Se o primeiro digito do CNPJ for "0", ignore-o e digite o próximo!',
						placement: 'right',
						trigger: 'manual'
					});

					$(this).tooltip('show');

					return false;
				}
			}
			else
			{
				$(this).tooltip('hide');
			}
		}

	});

	$('#tbl_nt_config_man tbody').on('click', 'tr', function(){

		thisHtml = $(this).html();

		$('#tbl_nt_config_man tbody').html('<tr class="bg-success text-light">'+thisHtml+'</tr>');

		Toast.fire({
			title: 'Manutenção Selecionada!',
			type: 'success'
		})

	});

	$('#pesquisa_estoque').on('change', 'select[name=campo]', function(){

		if($(this).val() != '')
		{
			$('#valor_pesquisa_estoque').removeClass('d-none');
		}
		else
		{
			$('#valor_pesquisa_estoque').addClass('d-none');
		}

	});

	$('#pesquisa_fornecedores').on('change', 'select[name=campo]', function(){

		if($(this).val() != '')
		{
			$('#valor_pesquisa_fornecedores').removeClass('d-none');
		}
		else
		{
			$('#valor_pesquisa_fornecedores').addClass('d-none');
		}

	});

	$('#pesquisa_frota').on('change', 'select[name=campo]', function(){

		if($(this).val() != '')
		{
			$('#valor_pesquisa_frota').removeClass('d-none');
		}
		else
		{
			$('#valor_pesquisa_frota').addClass('d-none');
		}

	});

	$('#pesquisa_manutencoes').on('change', 'select[name=campo]', function(){

		if($(this).val() != '')
		{
			$('#valor_pesquisa_manutencao').removeClass('d-none');
		}
		else
		{
			$('#valor_pesquisa_manutencao').addClass('d-none');
		}

	});

	$('#pesquisa_entradas').on('change', 'select[name=campo]', function(){

		if($(this).val() != '')
		{
			$('#valor_pesquisa_entrada').removeClass('d-none');
		}
		else
		{
			$('#valor_pesquisa_entrada').addClass('d-none');
		}

	});

	$('#pesquisa_saidas').on('change', 'select[name=campo]', function(){

		if($(this).val() != '')
		{
			$('#valor_pesquisa_saidas').removeClass('d-none');
		}
		else
		{
			$('#valor_pesquisa_saidas').addClass('d-none');
		}

	});

	$('#pesquisa_notas').on('change', 'select[name=campo]', function(){

		if($(this).val() != '')
		{
			$('#valor_pesquisa_notas').removeClass('d-none');
		}
		else
		{
			$('#valor_pesquisa_notas').addClass('d-none');
		}

	});

	$('#pesquisa_produto').on('change', 'select[name=campo]', function(){

		if($(this).val() != '')
		{
			$('#valor_pesquisa_produtos').removeClass('d-none');
		}
		else
		{
			$('#valor_pesquisa_produtos').addClass('d-none');
		}

	});

});
