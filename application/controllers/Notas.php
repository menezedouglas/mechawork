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

class Notas extends CI_Controller
{

  /*
    CONFIGURAÇÕES
  */
  public $cfg;

  /*
    DADOS PARA O FRONT-END
  */
  public $dados;

  /*
    CARREGA AS DEPENDÊNCIAS
  */
  public function __construct()
  {
    parent::__construct();

    $this->load->model('Configuracoes_model', 'objcfg');
    $this->load->model('Notas_model', 'objnm');
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


  /*
      CARREGA A INDEX
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

      $this->load->view('consultas/notas/index', $this->dados, $this->cfg);

      $this->load->view('templates/footer.php', $this->cfg);
    }
  }

  public function pesquisar()
  {
    if ($this->session->userdata('email') == "")
    {
      redirect($this->cfg['url_logout'], 'refresh');
    }
    else
    {
      $result['success'] = False;
      $result['dados'] = array();

      $stm = null;
      $where = array();

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

      $stm = $this->objnm->retorna($where);

      if($stm != null)
      {
        $resultado = array();

        foreach ($stm as $value)
        {

          $temp = array(
            'id_nota'   => $value->nota,
            'nota'		  => 	number_format($value->nota, 0, '', '.'),
            'emissao'		=>	date('d/m/Y', strtotime($value->emissao)),
            'vencimento'=>	date('d/m/Y', strtotime($value->vencimento)),
            'cnpj'			=>	$this->formatarCnpj($value->cnpj),
            'valor'			=>	number_format($value->valor, 2, ',','.'),
            'estatus'		=>	$value->estatus,
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

  public function formatarCnpj($cnpj_cpf)
  {
    if(strlen(preg_replace("/\D/", '', $cnpj_cpf)) === 11)
    {
      $response = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
    }
    else
    {
      if(strlen(preg_replace("/\D/", '', $cnpj_cpf)) === 13)
      {
        $temp = '0'.$cnpj_cpf;
      }
      $response = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $temp);
    }

    return $response;
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

      $tabela = $this->input->post('tabela');
      $dados = array();

      if($tabela == 'tbl_nota_compra')
      {
        $dados = array(
          'nota_compra' => $this->input->post('id_nota')
        );
      }
      else
      {
        $dados = array(
          'nota_servico' => $this->input->post('id_nota')
        );
      }

      if($this->objnm->estornar($dados, $tabela))
      {
        $result['success'] = true;
      }

      echo json_encode($result);
    }
  }

}
