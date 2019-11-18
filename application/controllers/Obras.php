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

class Obras extends CI_Controller
{

    // VARIÁVEL DE CONFIGURAÇÕES
    public $cfg;

    // VARIÁVEL DE DADOS
    public $dados;

    // CARREGA AS DEPENDÊNCIAS
    public function __construct()
    {

      parent::__construct();

      $this->load->model('Configuracoes_model', 'objcfg');
      $this->load->model('Obras_model', 'objobr');
  		$this->load->model('Sistema_model', 'objsm');

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

    //  MÉTODO PRINCIPAL
    public function index()
    {
	    if ($this->session->userdata('email') == "")
	    {
	      redirect($this->cfg['url_logout'], 'refresh');
	    }
	    else
	    {
	      redirect($this->cfg['url_dashboard'], 'refresh');
      }
    }

    // CADASTRA AS OBRAS
    public function cadastrar()
  	{
	    if ($this->session->userdata('email') == "")
	    {
	      redirect($this->cfg['url_logout'], 'refresh');
	    }
	    else
	    {
        $result['success'] = false;

      	$dados['descricao'] = strtoupper($this->input->post('descricao'));

        if($this->objobr->cadastra_obra($dados))
        {
          $result['success'] = true;
        }

        echo json_encode($result);
      }
    }

    // LISTA OBRA
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

        $dados = array('id_obra' => $id);

        $retornos = $this->objobr->retorna_obras($dados);

        if(count($retornos) != 0)
        {
          $result['dados'] = $retornos;
          $result['success'] = true;
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
          'descricao' => strtoupper($this->input->post('descricao'))
        );

        $where = array(
          'id_obra' => $this->input->post('id_obra')
        );

        if($this->objobr->editar($dados, $where))
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

        $dados = array('id_obra' => $this->input->post('id_obra'));

        if($this->objobr->excluir($dados))
        {
          $result['success'] = true;
        }

        echo json_encode($result);
      }
    }

  }
