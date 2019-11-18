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

  class Fornecedores extends CI_Controller
  {

    public $cfg;

		public function __construct()
		{
			parent::__construct();

			$this->load->model('Configuracoes_model', 'objcfg');
      $this->load->model('Fornecedores_model', 'objfrc');
			$this->load->model('Sistema_model', 'objsm');
			$this->load->model('Notas_model', 'objnotas');

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
			FUNÇÃO DEFAULT PARA UTILIZAÇÃO - NECESSITA ADAPTAÇÃO PARA ATENDER A NECESSIDADE
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

				$this->load->view('consultas/fornecedores/index', $this->cfg);

				$this->load->view('templates/footer.php', $this->cfg);
			}
    }

    public function cadastrar()
    {
			if ($this->session->userdata('email') == "")
	    {
	      redirect($this->cfg['url_logout'], 'refresh');
	    }
	    else
	    {
        $fornecedor['cnpj'] = $this->input->post('cnpj');
        $fornecedor['nome'] = strtoupper($this->input->post('razao-social'));
        $fornecedor['telefone'] = $this->input->post('telefone');

        $this->objfrc->cadastra_fornecedor($fornecedor);
			}
    }

		public function buscaFornecedor($cnpj)
		{
			if ($this->session->userdata('email') == "")
		    {
		      redirect($this->cfg['url_logout'], 'refresh');
		    }
		    else
		    {
				$result['success'] = false;
				$result['cnpjblock'] = false;
				$result['dados'] = array();

				$dados = array(
					'cnpj' => $cnpj
				);

				$stm = $this->objfrc->retorna_fornecedor($dados);

				if(count($stm) != 0)
				{

					foreach ($stm as $fornecedor)
					{

						if(intval($fornecedor->estatus) == 0)
						{
							$result['success'] = false;
							$result['cnpjblock'] = true;
							break;
						}

						$temp = array(
							'cnpj' => $fornecedor->cnpj,
							'razao_social' => $fornecedor->nome,
							'telefone' => $fornecedor->telefone
						);

						array_push($result['dados'], $temp);
						$result['success'] = true;
					}

				}

				echo json_encode($result);
			}
		}

		// PESQUISA FORNEC
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

				$stm = null;

				if(empty($this->input->post('valor')))
	      {
	        if($this->input->post('excluidos_sim') == 'on')
	        {
	          $where['estatus >='] = 0;
	        }
	        else
	        {
	          $where['estatus >='] = 1;
	        }
	      }
	      else
	      {
	        if($this->input->post('excluidos_sim') == 'on')
	        {
	          $where[$this->input->post('campo').' like '] = '%'.$this->input->post('valor').'%';
	        }
	        else
	        {
	          $where[$this->input->post('campo').' like '] = '%'.$this->input->post('valor').'%';
	          $where['estatus'] = 1;
	        }
	      }

				$stm = $this->objfrc->retorna_fornecedor($where);

				if(count($stm) != 0)
				{
					foreach ($stm as $fornecedor)
					{

						if(strlen($fornecedor->cnpj) < 14)
						{
							$cnpj = '0'.$fornecedor->cnpj;
						}

						$temp = array(
							'id' => $fornecedor->cnpj,
							'cnpj' => $this->formatCnpjCpf($cnpj),
							'razao_social' => $fornecedor->nome,
							'telefone' => $fornecedor->telefone,
							'estatus' => $fornecedor->estatus
						);

						array_push($result['dados'], $temp);
					}

					$result['success'] = true;
					$result['user_lv'] = $this->session->userdata('nivel');
				}

				echo json_encode($result);
			}
		}

		public function formatCnpjCpf($value)
		{
		  $cnpj_cpf = preg_replace("/\D/", '', $value);

		  if (strlen($cnpj_cpf) === 11) {
		    return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
		  }

		  return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
		}

		public function historico($cnpj)
		{
		  if ($this->session->userdata('email') == "")
		  {
		    redirect($this->cfg['url_logout'], 'refresh');
		  }
		  else
		  {

        $result['success'] = false;
        $result['dados'] = array();

				$retorno = $this->objnotas->por_fornecedor($cnpj);

				if($retorno != null)
	      {
	        $resultado = array();

	        foreach ($retorno as $value)
	        {

	          $temp = array(
	            'nota'		  => 	number_format($value->nota, 0, '', '.'),
	            'emissao'		=>	date('d/m/Y', strtotime($value->emissao)),
	            'vencimento'=>	date('d/m/Y', strtotime($value->vencimento)),
	            'valor'			=>	number_format($value->valor, 2, ',','.'),
	            'tabela'    =>  $value->tabela
	          );

	          array_push($resultado, $temp);

	        }


	        $result['success'] = true;
	        $result['dados'] = $resultado;

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
					'nome' => strtoupper($this->input->post('razao_social')),
					'telefone' => strtoupper($this->input->post('telefone'))
				);

				$where = array( 'cnpj' => $this->input->post('id_fornecedor'));

				if($this->objfrc->editar($dados, $where))
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

				$dados = array( 'cnpj' => $this->input->post('cnpj') );

				if($this->objfrc->excluir($dados))
				{
					$result['success'] = true;
				}

				echo json_encode($result);
			}
		}

		public function restaurar()
		{
			if ($this->session->userdata('email') == "")
			{
				redirect($this->cfg['url_logout'], 'refresh');
			}
			else
			{
				$result['success'] = false;

				$dados = array( 'cnpj' => $this->input->post('cnpj') );

				if($this->objfrc->restaurar($dados))
				{
					$result['success'] = true;
				}

				echo json_encode($result);
			}
		}
}
