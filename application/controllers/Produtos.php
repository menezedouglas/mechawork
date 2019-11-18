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

class Produtos extends CI_Controller
{

	public $cfg;

	public $dados;

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Configuracoes_model', 'objcfg');
		$this->load->model('Produtos_model', 'objprod');
		$this->load->model('Sistema_model', 'objsm');

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

	// MÉTODO PRINCIPAL
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
			$this->load->view('consultas/produtos/index', $this->dados, $this->cfg);
			$this->load->view('templates/footer.php', $this->cfg);
		}
	}


  //  CADASTRA PRODUTO
  public function cadastra()
  {
    if ($this->session->userdata('email') == "")
    {
      redirect($this->cfg['url_logout'], 'refresh');
    }
    else
    {
      $result['success'] = false;

      $dados['codigo'] = strtoupper($this->input->post('codigo'));
      $dados['descricao'] = strtoupper($this->input->post('descricao'));
      $dados['unidade'] = strtoupper($this->input->post('unidade'));

      if($this->objprod->cadastra_produto($dados))
      {
        $result['success'] = true;
      }

      echo json_encode($result);
    }
  }

  // VALIDA PRODUTO
  public function validar($cod)
  {
    if ($this->session->userdata('email') == "")
    {
      redirect($this->cfg['url_logout'], 'refresh');
    }
    else
    {
      $result['success'] = false;

      $dados = array(
        'codigo' => $cod
      );

      $retorno = $this->objprod->valida_produtos($dados);

      if($retorno)
      {
        $result['success'] = true;
      }

      echo json_encode($result);
    }
  }

	// PESQUISA PRODUTOS
	public function pesquisar()
	{
	  if ($this->session->userdata('email') == "")
	  {
	    redirect($this->cfg['url_logout'], 'refresh');
	  }
	  else
	  {
			$result['success'] = false;
			$result['dados'] = array();

			$dados = null;

			if(!empty($this->input->post('valor')))
			{
				$dados = array(
					$this->input->post('campo').' like' => '%'.$this->input->post('valor').'%'
				);

			}
			else
			{
				$dados = array(
					'estatus' => 1
				);

			}

			$stm = $this->objprod->retorna_produto($dados);

			if($dados != null)
			{
				$result['success'] = true;
				$result['dados'] = $stm;
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
			$result['success'] = false;
			$result['dados'] = array();

			$dados = array('id_produto' => $id, 'estatus' => 1);

			$retorno = $this->objprod->retorna_produto($dados);

			if(count($retorno) != 0)
			{
				$result['success'] = true;
				$result['dados'] = $retorno;
			}

			echo json_encode($result);
		}
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
				'descricao' => strtoupper($this->input->post('descricao')),
				'unidade' => strtoupper($this->input->post('unidade'))
			);

			$where = array( 'id_produto' => $this->input->post('id_produto') );

			if($this->objprod->editar($dados, $where))
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

			$dados = array('id_produto' => $this->input->post('id_produto'));

			if($this->objprod->excluir($dados))
			{
				$result['success'] = true;
			}

			echo json_encode($result);
		}
	}
}
