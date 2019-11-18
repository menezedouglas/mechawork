<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	/*

		CENTRO PAULA SOUZA
		ESCOLA TÃ‰CNICA ESTADUAL DE EMBU - Embu das Artes - SP
		CURSO TÃ‰CNICO DE NÃ�VEL MÃ‰DIO EM INFORMÃ�TICA

		SISTEMA: MECHAWORK - Gerenciamento de Oficina MecÃ¢nica
		EQUIPE: IWG WEB SOFTWARE

		INTEGRANTES:
			Douglas Menezes Evangelista da Silva
			Lucas Eduardo Rodrigues Cardoso
			Lucas Henrique de Moraes
			Matheus Basilio Cintra
			Renata Araujo Nascimento
			Vinicius Arruda

		EMBU DAS ARTES, 29 DE JUNHO DE 2019.

	*/

  class Manutencoes extends CI_Controller
  {

    public $cfg;

		public $dados;

		public function __construct()
		{
			parent::__construct();

			$this->load->model('Configuracoes_model', 'objcfg');
			$this->load->model('Manutencoes_model', 'objmm');
			$this->load->model('Frota_model', 'objfrt');
			$this->load->model('Produtos_model', 'objprod');
			$this->load->model('Sistema_model', 'objsm');
			$this->load->model('Estoque_model', 'objestq');

      //  CARREGA ARQUIVO DE CONFIGURAÇÕES
      $this->config->load('mw_conf');

      //  CARREGA AS URL'S'
      $this->cfg = $this->objcfg->setConfigs();

			$now = now('America/Sao_Paulo');
			$retornoNotify = $this->objsm->notificacoes();
			$this->dados['notificacoes'] = array();

			foreach ($retornoNotify as $notificacao)
			{
				$tempo = '';
				$notify = strtotime($notificacao->data_notifica);

				if(($now - $notify) < 60)
				{

					if(number_format(($now - $notify), 0) == 1)
					{

						$tempo =  number_format(($now - $notify), 0) . ' Segundo Atrás....' ;

					}
					else
					{

						$tempo =  number_format(($now - $notify), 0) . ' Segundos Atrás....' ;

					}

				}

				else if(((($now - $notify)/60) >= 1)&&((($now - $notify)/60) <= 60))
				{

					if(number_format((($now - $notify) / 60), 0) == 1)
					{

						$tempo =  number_format((($now - $notify) / 60), 0) . ' Minuto Atrás....' ;

					}
					else
					{

						$tempo =  number_format((($now - $notify) / 60), 0) . ' Minutos Atrás....' ;

					}

				}
				else if((((($now - $notify) / 60 ) / 60) >= 1)&&(((($now - $notify) / 60 ) / 60) <= 60))
				{

					if(number_format(((($now - $notify) / 60 ) / 60), 0) == 1)
					{

						$tempo =  number_format(((($now - $notify) / 60 ) / 60), 0) . ' Hora Atrás....' ;

					}
					else
					{

						$tempo =  number_format(((($now - $notify) / 60 ) / 60), 0) . ' Horas Atrás....' ;

					}

				}
				else if((((($now - $notify) / 60 ) / 60) / 24) >= 1)
				{

					if(number_format((((($now - $notify) / 60 ) / 60) / 24) , 0) == 1)
					{

						$tempo =  number_format((((($now - $notify) / 60 ) / 60) / 24) , 0) . ' Dia Atrás....' ;

					}
					else
					{

						$tempo =  number_format((((($now - $notify) / 60 ) / 60) / 24) , 0) . ' Dias Atrás....' ;

					}

				}

				$temp = array(
					'id_notificacao' => $notificacao->id_notificacao,
					'id_futura' => $notificacao->id_futura,
					'placa' => $notificacao->placa,
					'km' => $notificacao->quilometragem,
					'dt_man' => $notificacao->data_cadastrada,
					'descricao' => $notificacao->descricao,
					'situacao' => $notificacao->situacao,
					'tempo' => $tempo,
          'dt_notify' => $notificacao->data_notifica
				);

				array_push($this->dados['notificacoes'], $temp);
			}
		}

		// Método principal
		public function index()
		{
	    if ($this->session->userdata('email') == "")
	    {
	      redirect($this->cfg['url_logout'], 'refresh');
	    }
	    else
	    {
				$this->load->view('templates/header.php', $this->cfg);
				$this->load->view('templates/loader.php', $this->cfg);
				$this->load->view('templates/menu.php', $this->cfg);
				$this->load->view('templates/notify.php', $this->dados, $this->cfg);
				$this->load->view('consultas/manutencoes/index', $this->dados, $this->cfg);
				$this->load->view('templates/footer.php', $this->cfg);
			}
		}

		// Tela de consulta das Manutenções Agendadas
		public function Agendadas()
		{
		    if ($this->session->userdata('email') == "")
		    {
		      redirect($this->cfg['url_logout'], 'refresh');
		    }
		    else
		    {
				$this->load->view('templates/header.php', $this->cfg);
				$this->load->view('templates/loader.php', $this->cfg);
				$this->load->view('templates/menu.php', $this->cfg);
				$this->load->view('templates/notify.php', $this->dados, $this->cfg);
				$this->load->view('consultas/manutencoes/preventivas', $this->dados, $this->cfg);
				$this->load->view('templates/footer.php', $this->cfg);
			}
		}

		// Adiciona produtos no formulário de cadastro das Manutenções Realizadas
		public function AdicionaProduto()
		{

		    if ($this->session->userdata('email') == "")
		    {
		      redirect($this->cfg['url_logout'], 'refresh');
		    }
		    else
		    {
				$result['success'] = false;
				$result['semsaldo'] = false;
				$result['saldoins'] = false;
				$result['produtos'] = array();
				$result['total'] = 0;

				if($this->session->estq_cart_control == '1')
				{
					$this->session->set_userdata('estq_cart_control', '0');
					$this->cart->destroy();
				}

				$dados = array('codigo' => $this->input->post('cod_produto'));

				$produtos = $this->objprod->retorna_produto_c_estoque($dados);

				if(count($produtos) != 0)
				{

					$cart_data = array();

					foreach ($produtos as $produto)
					{
						foreach ($this->cart->contents() as $item) {
							if(($item['id'] == $produto->codigo)&&(intval($produto->quantia) < ( intval($item['qty']) + intval($this->input->post('qntd')) ) ) )
							{
								$result['saldoins'] = true;
								break;
							}
						}

						if($result['saldoins'] == false)
						{
							if(intval($produto->quantia) < intval($this->input->post('qntd')))
							{
								$result['saldoins'] = true;
							}

							if($result['saldoins'] == false)
							{
								$temp = array(
									'rowid'   => $produto->codigo,
									'id'      => $produto->codigo,
									'qty'     => $this->input->post('qntd'),
									'price'   => $produto->valor_unitario_medio,
									'name'    => $produto->descricao
								);

								array_push($cart_data, $temp);

								if($this->cart->insert($cart_data))
								{
									$this->session->set_userdata('man_cart_control', '1');

									 if($this->cart->total_items() > 0)
									 {
										 $total = 0;

											foreach($this->cart->contents() as $prod)
											{

												$total += $prod['price']*$prod['qty'];

												$temp = array(
													'codigo' => $prod['id'],
													'descricao' => $prod['name'],
													'unidade' => $produto->unidade,
													'qntd' => $prod['qty'],
													'vlr_unitario' => number_format($prod['price'], 2, ',', '.'),
													'vlr_total' => number_format($prod['price']*$prod['qty'], 2, ',', '.')
												);

												array_push($result['produtos'], $temp);

											}

											$result['total'] = number_format($total, 2, ',', '.');

									 }

									$result['success'] = true;
								}
							}
						}
					}
				}
				else
				{
					$result['semsaldo'] = true;
				}

				echo json_encode($result);
			}
		}

		// LISTA AS MANUTENÇÕES DE UM VEÍCULO/EQUIPAMENTO
		public function listaRealizadas($placa, $data)
		{
		    if ($this->session->userdata('email') == "")
		    {
		      redirect($this->cfg['url_logout'], 'refresh');
		    }
		    else
		    {
					$result['success'] = false;
					$result['dados'] = array();

					$where = array('placa_numero' => $placa, 'c.estatus' => 1, 'm.data_realizada >=' => date('Y-m-d', strtotime($data)));
					$select = 'id_manutencao, hora_iniciada, hora_finalizada, m.descricao, nota_servico, custo_interno, email';

					$dados = $this->objmm->retornaRealizada($where, $select);

					foreach ($dados as $retorno)
					{
						$temp = array(
							'id'				=> $retorno->id_manutencao,
							'hr_inicio'	=> date('d/m/Y h:i', strtotime($retorno->hora_iniciada)),
							'hr_fim'		=> date('d/m/Y h:i', strtotime($retorno->hora_finalizada)),
							'descricao'	=> $retorno->descricao,
							'nota'			=> number_format($retorno->nota_servico, 0, '', '.'),
							'custo'			=> number_format($retorno->custo_interno, 2, ',', '.'),
							'email'			=> $retorno->email
						);

						array_push($result['dados'], $temp);
					}

					if(count($result['dados']) != 0)
					{
						$result['success'] = true;
						$result['veiculo'] = $this->objfrt->retornaFrota(array('placa_numero' => $placa));
					}

					echo json_encode($result);

				}
		}

		// REMOVE UM OU MAIS PRODUTOS DA LISTA
		public function removerProduto()
		{
			foreach($this->input->post() as $produto)
			{
				$this->cart->remove($produto);
			}
		}

		public function cadastra()
		{
		    if ($this->session->userdata('email') == "")
		    {
		      redirect($this->cfg['url_logout'], 'refresh');
		    }
		    else
		    {

			    $result['success'] = false;

					$dados_externa = array(
						'hora_inicio'		=> $this->input->post('data-inicio'),
						'hora_fim'			=> $this->input->post('data-fim'),
						'descricao'			=> strtoupper($this->input->post('descricao')),
						'usuario'			=> $this->session->userdata('id'),
						'placa'				=> strtoupper($this->input->post('placa')),
						'nota'				=> $this->input->post('numero-nota'),
						'obra'				=> strtoupper($this->input->post('obra')),
						'valor' 			=> $this->input->post('valor-nota'),
						'data_nota'			=> $this->input->post('data-nota'),
						'data_vencimento'	=> $this->input->post('data-venc'),
						'fornecedor'		=> $this->input->post('fornecedor'),
						'razao_social'		=> strtoupper($this->input->post('razao-social')),
						'telefone' 			=> $this->input->post('telefone')
					);

					$dados_interna = array(
						'hora_inicio' => $this->input->post('data-inicio'),
						'hora_fim'	  => $this->input->post('data-fim'),
						'usuario'	  => $this->session->userdata('id'),
						'descricao'	  => strtoupper($this->input->post('descricao')),
						'placa'		  => strtoupper($this->input->post('placa')),
						'obra'		  => $this->input->post('obra'),
						'custo'		  => $this->input->post('mao-obra')
					);

					$tipo = $this->input->post('tipo');

					if($tipo == 1)
					{
						if($this->objmm->cadastra($tipo, $dados_externa, $dados_interna))
						{

							$where = array(
								'hora_iniciada' => $this->input->post('data-inicio'),
								'hora_finalizada' => $this->input->post('data-fim'),
								'placa_numero' => $this->input->post('placa')
							);

							$man = $this->objmm->retornaRealizada($where, 'max(id_manutencao) id');

							$success = false;

							foreach ($this->cart->contents() as $produto)
							{

								$prod = $this->objprod->retorna_produto(array('codigo' => $produto['id']));

								$temp = array(
									'quantia_usada' => $produto['qty'],
									'id_estoque' => $prod[0]->id_produto,
									'id_manutencao' => $man[0]->id
								);

								if($this->objestq->saida($temp))
								{
									$success = true;
								}
								else
								{
									$success = false;
									break;
								}

							}

							if($success)
							{
								$this->cart->destroy();
								$result['success'] = true;
							}

						}
					}
					else
					{

						if($this->objmm->cadastra($tipo, $dados_externa, $dados_interna))
						{
							$result['success'] = true;
						}

					}

					echo json_encode($result);

				}
   		}

    	public function listaManutencao()
			{
		    	if ($this->session->userdata('email') == "")
		    	{
		      		redirect($this->cfg['url_logout'], 'refresh');
		    	}
		    	else
		    	{
						$result['success'] = False;

						echo json_encode($result);
				}
    	}

			public function cadprev()
    	{
	    	if ($this->session->userdata('email') == "")
	    	{
	      		redirect($this->cfg['url_logout'], 'refresh');
	    	}
	    	else
	    	{
				$result['success'] = false;

				$dados['data_prev'] = $this->input->post('data-prevista');
				$dados['tipo'] = $this->input->post('descricao');
				$dados['placa'] = $this->input->post('placa');
				$dados['usuario'] = $this->session->userdata('id');

				if(($this->input->post('data-minima') != null)||($this->input->post('quilometragem') != null))
				{
					$dados['data_min'] = $this->input->post('data-minima');
					$dados['quilometro'] = $this->input->post('quilometragem');
				}
				else
				{
					$dados['data_min'] = 0;
					$dados['quilometro'] = 0;
				}

				if($this->objmm->agendar($dados))
				{

					$result['success'] = true;
				}

				echo json_encode($result);
			}
    	}

		public function lista_agendamento($id)
		{
		    if ($this->session->userdata('email') == "")
		    {
		      redirect($this->cfg['url_logout'], 'refresh');
		    }
		    else
		    {
					$result['success'] = false;
					$result['dados'] = array();

					$dados = array(
						'id_futura' => $id,
						'cf.estatus' => 1,
						'mf.estatus' => 1
					);

					$stm = $this->objmm->retornaFuturas($dados);

					if(count($stm)!= 0)
					{
						$result['success'] = true;
						$result['dados'] = $stm;
					}

					echo json_encode($result);
				}
		}

		public function edita_agendamento()
		{
			if ($this->session->userdata('email') == "")
			{
				redirect($this->cfg['url_logout'], 'refresh');
			}
			else
			{
				$result['success'] = false;

				$dados = array(
					'data_minima' => $this->input->post('data-minima'),
					'data_prevista' => $this->input->post('data-prevista'),
					'descricao' => $this->input->post('descricao'),
					'quilometragem' => $this->input->post('km')
				);

				$where = array('id_futura' => $this->input->post('id_futura'));

				if($this->objmm->edita_agendamento($dados, $where))
				{
					$result['success'] = true;
				}

				echo json_encode($result);
			}
		}

		public function excluir_agendada()
		{
			 if ($this->session->userdata('email') == "")
			 {
				 redirect($this->cfg['url_logout'], 'refresh');
			 }
			 else
			 {
				 $result['success'] = false;

				 $where = array(
					 'id_futura' => $this->input->post('id_futura')
				 );

				 if($this->objmm->excluir_agendada($where))
				 {
					 $result['success'] = true;
				 }

				 echo json_encode($result);

			 }

		}

		public function pesquisa_realizadas()
		{
			if ($this->session->userdata('email') == "")
			{
				redirect($this->cfg['url_logout'], 'refresh');
			}
			else
			{
				$result['success'] = false;
				$result['dados'] = array();

				$stm = null;

				$select = 'id_manutencao, hora_iniciada, hora_finalizada, c.placa_numero, m.descricao, nota_servico, custo_interno, email, m.estatus';

				if(empty($this->input->post('valor')))
				{
					if($this->input->post('excluidos_sim') == 'on')
					{
						$stm = $this->objmm->retornaRealizada(array('m.estatus >=' => 0), $select);
					}
					else
					{
						$stm = $this->objmm->retornaRealizada(array('m.estatus >=' => 1), $select);
					}
				}
				else
				{

					if($this->input->post('excluidos_sim') == 'on')
					{
						$where = array(
							$this->input->post('campo').' like ' => '%'.$this->input->post('valor').'%'
						);
					}
					else
					{
						$where = array(
							$this->input->post('campo').' like ' => '%'.$this->input->post('valor').'%',
							'm.estatus' => 1
						);
					}

					$stm = $this->objmm->retornaRealizada($where, $select);
				}

				if($stm != null)
				{
					$resultado = array();

					foreach ($stm as $value)
					{

						$temp = array(
							'id_manutencao'		=> 	$value->id_manutencao,
							'inicio'				=>	date('d/m/Y h:i:s', strtotime($value->hora_iniciada)),
							'termino'				=>	date('d/m/Y h:i:s', strtotime($value->hora_finalizada)),
							'placa'					=>	$value->placa_numero,
							'descricao'			=>	$value->descricao,
							'nota'					=>	$value->nota_servico,
							'custo_i'				=>	number_format($value->custo_interno, 2, ',','.'),
							'usuario' 			=> 	$value->email,
							'situacao'			=> 	$value->estatus
						);

						array_push($resultado, $temp);

					}


					$result['success'] = true;
					$result['dados'] = $resultado;

				}

				echo json_encode($result);

			}

		}

		public function estornar()
		{
			 if ($this->session->userdata('email') == "")
			 {
				 redirect($this->cfg['url_logout'], 'refresh');
			 }
			 else
			 {
				 $result['success'] = false;

				 $where = array(
					 'id_manutencao' => $this->input->post('id_manutencao')
				 );

				 if($this->objmm->estornar($where))
				 {
					 $result['success'] = true;
				 }

				 echo json_encode($result);

			 }

		}

  }
