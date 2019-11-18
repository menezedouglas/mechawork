<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	/*

		CENTRO PAULA SOUZA
		ESCOLA TÉCNICA ESTADUAL DE EMBU - Embu das Artes - SP
		CURSO TÉCNICO DE NÍVEL MÉDIO EM INFORMÁTICA

		SISTEMA: MECHAWORK - Gerênciamento de Oficina Mecânica
		EQUIPE: IWG WEB SOFTWARE

		INTEGRANTES:
			Douglas Menezes Evangelista da Silva
			Lucas Eduardo Rodrigues Cardoso
			Lucas Henrique de Moraes
			Matheus Basilio Cintra
			Renata Araujo Nascimento
			Vinicius Arruda

		EMBU DAS ARTES, XX DE JUNHO DE 2019.

	*/


class Frota extends CI_Controller
{

    public $cfg;

		public function __construct()
		{
		  parent::__construct();

			$this->load->model('Configuracoes_model', 'objcfg');
		  $this->load->model('Frota_model', 'objfr');
		  $this->load->model('Sistema_model', 'objsm');
		  $this->load->model('Manutencoes_model', 'objmn');

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


		//
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
				$this->load->view('consultas/frota/index', $this->cfg);
				$this->load->view('templates/footer.php', $this->cfg);
			}
		}

    //
    public function cadastrar()
    {
	    if ($this->session->userdata('email') == "")
	    {
	      redirect($this->cfg['url_logout'], 'refresh');
	    }
	    else
	    {
				$result['success'] = false;

				$dados['placa_numero'] = strtoupper($this->input->post('placa'));
				$dados['fabricante'] = strtoupper($this->input->post('fabricante'));
				$dados['ano'] = strtoupper($this->input->post('ano'));
				$dados['descricao'] = strtoupper($this->input->post('descricao'));
				$dados['tipo'] = strtoupper($this->input->post('tipo'));

				$this->objfr->cadastra_frota($dados);
				{
					$result['success'] = true;
				}

				echo json_encode($result);
			}
    }

    public function listar($id)
    {
	    if ($this->session->userdata('email') == "")
	    {
	      redirect($this->cfg['url_logout'], 'refresh');
	    }
	    else
	    {
	    	$result['success'] = False;
	    	$result['dados'] = array();

	     	$return = $this->objfr->retornaFrota(array('id_veiculo'=>$id));

	      if($return != null)
	      {
	        foreach ($return as $frota) {
	        	$temp = array(
							'id_veiculo' => $frota['id'],
							'placa' => strtoupper($frota['placa']),
							'descricao' => strtoupper($frota['descricao']),
							'fabricante' => strtoupper($frota['fabricante']),
							'ano' => strtoupper($frota['ano']),
							'tipo' => strtoupper($frota['tipo'])
						);

						array_push($result['dados'], $temp);
	        }
	        $result['success'] = True;
	      }

	      echo json_encode($result);
			}
    }

		public function validar()
		{
			$result['success'] = false;

			$dados = array(
				'placa_numero' => $this->input->post('placa')
			);

			if($this->objfr->validar($dados))
			{
				$result['success'] = true;
			}

			echo json_encode($result);
		}

		public function pesquisar()
		{

			$result['success'] = false;
			$result['dados'] = array();

			$frotas = null;

			if(empty($this->input->post('valor')))
			{
				$frotas = $this->objfr->retornaFrota(null);
			}
			else
			{
				$where = array(
					$this->input->post('campo').' like ' => '%'.$this->input->post('valor').'%',
					'estatus' => 1
				);

				$frotas = $this->objfr->retornaFrota($where);
			}

			if($frotas != null)
			{
				$retorno = array();

				foreach ($frotas as $frota)
				{

					$ult_man = '';

					if($frota['ult_man'] != ''){ $ult_man = date('d/m/Y h:i', strtotime($frota['ult_man'])); } else { $ult_man = 'Não Existe'; }

					$temp = array(

						'id_veiculo' => $frota['id'],
						'placa' => $frota['placa'],
						'descricao' => $frota['descricao'],
						'ano' => $frota['ano'],
						'fabricante' => $frota['fabricante'],
						'tipo' => $frota['tipo'],
						'ult_man' => $ult_man

					);

					array_push($retorno, $temp);

				}

				$result['success'] = true;
				$result['dados'] = $retorno;

			}

			echo json_encode($result);

		}

		public function editar()
		{
	    if ($this->session->userdata('email') == "")
	    {
	      redirect($this->cfg['url_logout'], 'refresh');
	    }
	    else
	    {
				$result['success'] = false;

				$dados = array(
					'fabricante' => strtoupper($this->input->post('fabricante')),
					'ano' => strtoupper($this->input->post('ano')),
					'descricao' => strtoupper($this->input->post('descricao')),
					'tipo' => strtoupper($this->input->post('tipo'))
				);

				$where = array( 'id_veiculo' => $this->input->post('id_veiculo'));

				if($this->objfr->editar($dados, $where))
				{
					$result['success'] = true;
				}

				echo json_encode($result);
			}
		}

		public function excluir()
		{
			if ($this->session->userdata('email') == "")
			{
				redirect($this->cfg['url_logout'], 'refresh');
			}
			else
			{
				$result['success'] = false;

				$dados = array('id_veiculo' => $this->input->post('id_veiculo'));

				if($this->objfr->excluir($dados))
				{
					$result['success'] = true;
				}

				echo json_encode($result);
			}
		}

		public function historico($placa)
		{
			if ($this->session->userdata('email') == "")
			{
				redirect($this->cfg['url_logout'], 'refresh');
			}
			else
			{

				$result['success'] = false;

				$where = array('cf.placa_numero' => $placa);

				$stm = $this->objfr->historico($where);

				if(count($stm) != 0)
				{
					$result['dados'] = array();

					$resultado = array();

					foreach ($stm as $valor)
					{

						$datatime1 = new DateTime($valor->hora_iniciada);
						$datatime2 = new DateTime($valor->hora_finalizada);

						$diff = $datatime1->diff($datatime2);
						$horas = $diff->h + ($diff->days * 24);


						$temp = array(
							'id' 				=> $valor->id_manutencao,
							'placa' 		=> $valor->placa_numero,
							'nome'			=> $valor->nome,
							'ano'				=> $valor->ano,
							'hr_inicio' => date('d/m/Y h:i:s', strtotime($valor->hora_iniciada)),
							'hr_fim' 		=> date('d/m/Y h:i:s', strtotime($valor->hora_finalizada)),
							'ht'				=> $horas,
							'descricao' => $valor->descricao,
							'nota' 			=> $valor->nota_servico,
							'custo_i' 	=> number_format($valor->custo_interno, 2, ',', '.'),
							'obra'			=> $valor->obra,
							'email' 		=> $valor->email
						);

						array_push($resultado, $temp);
					}

					$result['success'] = true;
					$result['dados'] = $resultado;
				}

				echo json_encode($result);

			}
		}
}
