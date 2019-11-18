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

  class Movimentacoes extends CI_Controller
  {

    public $cfg;

		public function __construct()
		{
		  parent::__construct();

			$this->load->model('Configuracoes_model', 'objcfg');
		  $this->load->model('Sistema_model', 'objsm');
			$this->load->model('Movimentacoes_model', 'objmovi');

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

		/*
			MÉTODO PRIMÁRIO
		*/
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
				$this->load->view('consultas/estoque/movimentacoes', $this->cfg);
				$this->load->view('templates/footer.php', $this->cfg);
			}
    }

		/*
			ENTRADAS REALIZADAS
		*/
    public function entradas()
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
				$this->load->view('consultas/movimentacoes/entradas', $this->cfg);
				$this->load->view('templates/footer.php', $this->cfg);
			}
    }

		/*
			SAÍDAS REALIZADAS
		*/
    public function saidas()
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
				$this->load->view('consultas/movimentacoes/saidas', $this->cfg);
				$this->load->view('templates/footer.php', $this->cfg);
			}
    }

		public function pesquisa_entrada()
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

				$select = 'id_entrada, data_entrada, codigo, e.numero_nota, quantia_comprada, valor_unitario, descricao, e.estatus';

				if(empty($this->input->post('valor')))
				{
					if($this->input->post('estornados_sim') == 'on')
					{
						$stm = $this->objmovi->retorna_entrada(array('e.estatus >=' => 0), $select);
					}
					else
					{
						$stm = $this->objmovi->retorna_entrada(array('e.estatus >=' => 1), $select);
					}
				}
				else
				{

					if($this->input->post('estornados_sim') == 'on')
					{
						$where = array(
							$this->input->post('campo').' like ' => '%'.$this->input->post('valor').'%'
						);
					}
					else
					{
						$where = array(
							$this->input->post('campo').' like ' => '%'.$this->input->post('valor').'%',
							'e.estatus' => 1
						);
					}

					$stm = $this->objmovi->retorna_entrada($where, $select);
				}

				if($stm != null)
				{

					$resultado = array();

					foreach ($stm as $value)
					{

						$temp = array(
							'id_entrada'		=> 	$value->id_entrada,
							'data'					=>	date('d/m/Y h:i:s', strtotime($value->data_entrada)),
							'cod'						=>	$value->codigo,
							'descricao'			=>	$value->descricao,
							'nota'					=>	$value->numero_nota,
							'qty'						=>	$value->quantia_comprada,
							'vlr_unitario'	=>	number_format($value->valor_unitario, 2, ',','.'),
							'vlr_total'			=>	number_format($value->quantia_comprada * $value->valor_unitario, 2, ',','.'),
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

		public function pesquisa_saida()
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

				$select = 'id_saida, data_saida, codigo, quantia_usada, valor_unitario_medio, descricao, e.estatus';

				if(empty($this->input->post('valor')))
				{
					if($this->input->post('estornados_sim') == 'on')
					{
						$stm = $this->objmovi->retorna_saida(array('e.estatus >=' => 0), $select);
					}
					else
					{
						$stm = $this->objmovi->retorna_saida(array('e.estatus >=' => 1), $select);
					}
				}
				else
				{

					if($this->input->post('estornados_sim') == 'on')
					{
						$where = array(
							$this->input->post('campo').' like ' => '%'.$this->input->post('valor').'%'
						);
					}
					else
					{
						$where = array(
							$this->input->post('campo').' like ' => '%'.$this->input->post('valor').'%',
							'e.estatus' => 1
						);
					}

					$stm = $this->objmovi->retorna_saida($where, $select);
				}

				if($stm != null)
				{

					$resultado = array();

					foreach ($stm as $value)
					{

						$temp = array(
							'id_saida'			=> 	$value->id_saida,
							'data'					=>	date('d/m/Y h:i:s', strtotime($value->data_saida)),
							'cod'						=>	$value->codigo,
							'descricao'			=>	$value->descricao,
							'qty'						=>	$value->quantia_usada,
							'vlr_unitario'	=>	number_format($value->valor_unitario_medio, 2, ',','.'),
							'vlr_total'			=>	number_format($value->quantia_usada * $value->valor_unitario_medio, 2, ',','.'),
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

		public function lista_entrada($id)
		{

	    if ($this->session->userdata('email') == "")
	    {
	      redirect($this->cfg['url_logout'], 'refresh');
	    }
	    else
			{

				$result['success'] = false;
				$result['dados'] = array();

				$where = array(
					'id_entrada' => $id,
					'e.estatus' => 1,
					'p.estatus' => 1
				);

				$retorno = $this->objmovi->retorna_entrada($where, '*');

				if(count($retorno) != 0)
				{

					$resultado = array();

					foreach ($retorno as $value)
					{

						$temp = array(
							'id_entrada'		=> 	$value->id_entrada,
							'data'					=>	date('d/m/Y h:i:s', strtotime($value->data_entrada)),
							'cod'						=>	$value->codigo,
							'descricao'			=>	$value->descricao,
							'vlr_total'			=>	number_format($value->quantia_comprada * $value->valor_unitario, 2, ',','.'),
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

		public function estorna_entrada()
		{
	    if ($this->session->userdata('email') == "")
	    {
	      redirect($this->cfg['url_logout'], 'refresh');
	    }
	    else
	    {

				$result['success'] = false;

				$dados = array( 'id' => $this->input->post('id_entrada') );

				if($this->objmovi->estorna_movimento(1, $dados))
				{

					$result['success'] = true;

				}

				echo json_encode($result);

			}
		}

  }
