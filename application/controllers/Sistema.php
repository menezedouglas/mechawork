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

class Sistema extends CI_Controller
{

    public $cfg;

    public $dados;

    public function __construct()
    {

      parent::__construct();

      //  CARREGA AS MODELS UTILIZADAS
      $this->load->model('Configuracoes_model', 'objcfg');
      $this->load->model('Produtos_model', 'objprod');
      $this->load->model('Usuarios_model', 'objum');
      $this->load->model('Sistema_model', 'objsm');
      $this->load->model('Obras_model', 'objobra');
      $this->load->model('Frota_model', 'objfrt');
      $this->load->model('Manutencoes_model', 'objman');

      //  CARREGA ARQUIVO DE CONFIGURAÇÕES
      $this->config->load('mw_conf');

      //  CARREGA AS URL'S'
      $this->cfg = $this->objcfg->setConfigs();

      $now = now('America/Sao_Paulo');
      $retornoNotify = $this->objsm->notificacoes();
      $this->dados['notificacoes'] = array();

      $this->dados['mes'] = array('janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro');

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

    //  CARREGA A INDEX
    public function index()
    {
        $mobile = FALSE;
        $user_agents = array("iPhone","iPad","Android","webOS","BlackBerry","iPod","Symbian","IsGeneric");

        foreach($user_agents as $user_agent){
            if (strpos($_SERVER['HTTP_USER_AGENT'], $user_agent) !== FALSE) {
                $mobile = TRUE;
    	        $modelo	= $user_agent;
    	        break;
            }
        }
        if($mobile)
        {
            echo '<strong><h3>Seu dispositivo não é compatível!</h3></strong>';
        }
        else
        {

          if ($this->session->userdata('email') == "")
          {
            $this->load->view('templates/header', $this->cfg);
            $this->load->view('templates/loader', $this->cfg);
            $this->load->view('index', $this->cfg);
            $this->load->view('templates/footer', $this->cfg);
          }
          else
          {
            header('location:'.$this->cfg['url_dashboard']);
          }
        }
    }

    //  CARREGA A DASHBOARD
    public function inicio()
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
            $this->load->view('dashboard/index', $this->cfg);
            $this->load->view('templates/rodape.php', $this->cfg);
            $this->load->view('templates/footer.php', $this->cfg);
        }
    }

    //  CARREGA A CONFIGURAÇÕES
    public function configuracoes()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {
        $this->dados['configs'] = $this->objcfg->getConfigs();
        $this->load->view('templates/header.php', $this->cfg);
        $this->load->view('templates/loader.php', $this->cfg);
        $this->load->view('templates/menu.php', $this->cfg);
        $this->load->view('templates/notify.php', $this->dados, $this->cfg);
        $this->load->view('user/admin/configuracoes/index', $this->dados, $this->cfg);
        $this->load->view('templates/footer.php', $this->cfg);
      }
    }

    //  CARREGA USUARIOS
    public function usuarios()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {
        $this->dados['usuarios'] = $this->objum->retorna_usuarios();
        $this->dados['excluidos'] = $this->objum->retornaExcluidos();
        $this->load->view('templates/header.php', $this->cfg);
        $this->load->view('templates/loader.php', $this->cfg);
        $this->load->view('templates/menu.php', $this->cfg);
        $this->load->view('templates/notify.php', $this->dados, $this->cfg);
        $this->load->view('user/admin/usuarios/index', $this->dados, $this->cfg);
        $this->load->view('templates/footer.php', $this->cfg);
      }
    }

    //  CARREGA A DE RELATÓRIOS
    public function Relatorio()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {
        if(!empty($this->input->post()))
        {
          if($this->input->post('tipo_relatorio') == '1')
          {
            if(empty($this->input->post('parametro')))
            {
              if(empty($this->input->post('tipo_frota')))
              {
                if($this->input->post('periodo_sim') == 'on')
                {
                  $this->dados['relatorio_titulo'] = 'Relatório de Manutenção Geral';
                  $this->dados['relatorio_periodo'] = '(De '.date('d/m/Y', strtotime($this->input->post('periodo-inicio'))).' até '.date('d/m/Y', strtotime($this->input->post('periodo-fim'))).')';
                  $where = array(
                    'hora_iniciada >=' => $this->input->post('periodo-inicio'),
                    'hora_iniciada <=' => $this->input->post('periodo-fim')
                  );
                }
                else
                {
                  $this->dados['relatorio_titulo'] = 'Relatório de Manutenção Geral';
                  $where = null;
                }
              }
              else
              {
                if($this->input->post('periodo_sim') == 'on')
                {
                  $this->dados['relatorio_titulo'] = 'Relatório de Manutenção Geral';
                  $this->dados['tipo_frota_titulo'] = $this->input->post('tipo_frota');
                  $this->dados['relatorio_periodo'] = '(De '.date('d/m/Y', strtotime($this->input->post('periodo-inicio'))).' até '.date('d/m/Y', strtotime($this->input->post('periodo-fim'))).')';
                  $where = array(
                    'hora_iniciada >=' => $this->input->post('periodo-inicio'),
                    'hora_iniciada <=' => $this->input->post('periodo-fim'),
                    'tipo' => $this->input->post('tipo_frota')
                  );
                }
                else
                {
                  $this->dados['relatorio_titulo'] = 'Relatório de Manutenção Geral';
                  $this->dados['tipo_frota_titulo'] = $this->input->post('tipo_frota');
                  $where = array('tipo' => $this->input->post('tipo_frota'));
                }
              }
            }
            else
            {
              if(empty($this->input->post('tipo_frota')))
              {
                if($this->input->post('periodo_sim') == 'on')
                {
                  $this->dados['relatorio_titulo'] = 'Relatório de Manutenção Individual';
                  $this->dados['relatorio_periodo'] = '(De '.date('d/m/Y', strtotime($this->input->post('periodo-inicio'))).' até '.date('d/m/Y', strtotime($this->input->post('periodo-fim'))).')';
                  $where = array(
                    'placa_numero' => $this->input->post('parametro'),
                    'hora_iniciada >=' => $this->input->post('periodo-inicio'),
                    'hora_iniciada <=' => $this->input->post('periodo-fim')
                  );
                }
                else
                {
                  $this->dados['relatorio_titulo'] = 'Relatório de Manutenção Individual';
                  $where = array(
                    'placa_numero' => $this->input->post('parametro')
                  );
                }
              }
              else
              {
                if($this->input->post('periodo_sim') == 'on')
                {
                  $this->dados['relatorio_titulo'] = 'Relatório de Manutenção Individual';
                  $this->dados['tipo_frota_titulo'] = $this->input->post('tipo_frota');
                  $this->dados['relatorio_periodo'] = '(De '.date('d/m/Y', strtotime($this->input->post('periodo-inicio'))).' até '.date('d/m/Y', strtotime($this->input->post('periodo-fim'))).')';
                  $where = array(
                    'placa_numero' => $this->input->post('parametro'),
                    'hora_iniciada >=' => $this->input->post('periodo-inicio'),
                    'hora_iniciada <=' => $this->input->post('periodo-fim'),
                    'tipo' => $this->input->post('tipo_frota')
                  );
                }
                else
                {
                  $this->dados['relatorio_titulo'] = 'Relatório de Manutenção Individual';
                  $this->dados['tipo_frota_titulo'] = $this->input->post('tipo_frota');
                  $where = array(
                    'placa_numero' => $this->input->post('parametro'),
                    'tipo' => $this->input->post('tipo_frota')
                  );
                }
              }
            }

            if($where != null)
            {

              $this->dados['frotas'] = $this->objfrt->retornaFrota(array('placa_numero' => $this->input->post('parametro')));

            }

            $interna = $this->objsm->gerarelatorio_maninterna($where, array('tipo' => $this->input->post('tipo_frota')));
            $externa = $this->objsm->gerarelatorio_manexterna($where, array('tipo' => $this->input->post('tipo_frota')));

            $resultado_interna = array();
            $resultado_externa = array();
            $total_interna = 0;
            $total_externa = 0;

            foreach ($interna as $value)
            {
              $total_interna += $value->custo_interno;
              $temp = array(
                'id_man'        => $value->id_manutencao,
                'dt_realizada'  => date('d/m/Y h:i', strtotime($value->hora_iniciada)),
                'dt_finalizada' => date('d/m/Y h:i', strtotime($value->hora_finalizada)),
                'placa'         => $value->placa_numero,
                'custo'         => number_format($value->custo_interno, 2, '.', ','),
                'descricao'     => $value->descricao,
                'armazem'       => $value->armazem
              );

              array_push($resultado_interna, $temp);
            }

            foreach ($externa as $value)
            {
              $total_externa += $value->valor;
              $temp = array(
                'id_man'        => $value->id_manutencao,
                'dt_realizada'  => date('d/m/Y h:i', strtotime($value->hora_iniciada)),
                'dt_finalizada' => date('d/m/Y h:i', strtotime($value->hora_finalizada)),
                'placa'         => $value->placa_numero,
                'custo'         => number_format($value->valor, 2, '.', ','),
                'descricao'     => $value->descricao,
                'armazem'       => $value->armazem
              );

              array_push($resultado_externa, $temp);
            }

            $this->dados['manutencao_interna'] = $resultado_interna;
            $this->dados['manutencao_externa'] = $resultado_externa;
            $this->dados['total_interna'] = $total_interna;
            $this->dados['total_externa'] = $total_externa;
            $this->load->view('templates/header', $this->cfg);
            $this->load->view('user/relatorios/manutencao', $this->dados, $this->cfg);
            $this->load->view('templates/footer', $this->cfg);
          }
          else if($this->input->post('tipo_relatorio') == '2')
          {
            if(empty($this->input->post('parametro')))
            {
              if($this->input->post('periodo_sim') == 'on')
              {
                $this->dados['relatorio_titulo'] = 'Relatório Geral de Entradas';
                $this->dados['relatorio_periodo'] = '(De '.date('d/m/Y', strtotime($this->input->post('periodo-inicio'))).' até '.date('d/m/Y', strtotime($this->input->post('periodo-fim'))).')';
                $where = array(
                  'data_entrada >=' => $this->input->post('periodo-inicio'),
                  'data_entrada <=' => $this->input->post('periodo-fim')
                );
              }
              else
              {
                $this->dados['relatorio_titulo'] = 'Relatório Geral de Entradas';
                $where = null;
              }
            }
            else
            {
              if($this->input->post('periodo_sim') == 'on')
              {
                $this->dados['relatorio_titulo'] = 'Relatório Individual de Entradas';
                $this->dados['relatorio_periodo'] = '(De '.date('d/m/Y', strtotime($this->input->post('periodo-inicio'))).' até '.date('d/m/Y', strtotime($this->input->post('periodo-fim'))).')';
                $where = array(
                  'codigo' => $this->input->post('parametro'),
                  'data_entrada >=' => $this->input->post('periodo-inicio'),
                  'data_entrada <=' => $this->input->post('periodo-fim')
                );
              }
              else
              {
                $this->dados['relatorio_titulo'] = 'Relatório Individual de Entradas';
                $where = array(
                  'codigo' => $this->input->post('parametro')
                );
              }
            }

            if($where != null)
            {
              $this->dados['produto'] = $this->objprod->retorna_produto(array('codigo' => $this->input->post('parametro')));
            }

            $entradas = $this->objprod->historico($where);

            $resultado_entradas = array();
            $total_entradas = 0;

            foreach ($entradas as $value)
            {
              $total_entradas += $value->valor_unitario*$value->quantia_comprada;
              $temp = array(
                'id'            => $value->id_entrada,
                'data'          => date('d/m/Y h:i', strtotime($value->data_entrada)),
                'codigo'        => $value->codigo,
                'descricao'     => $value->descricao,
                'nota'          => number_format($value->nota_compra, 0, '', '.'),
                'quantidade'    => $value->quantia_comprada,
                'unidade'       => $value->unidade,
                'vlr_unitario'  => number_format($value->valor_unitario, 2, '.',','),
                'vlr_total'     => number_format($value->valor_unitario*$value->quantia_comprada, 2, '.',',')
              );

              array_push($resultado_entradas, $temp);
            }

            $this->dados['entradas'] = $resultado_entradas;
            $this->dados['total_entradas'] = $total_entradas;
            $this->load->view('templates/header', $this->cfg);
            $this->load->view('user/relatorios/produtos', $this->dados, $this->cfg);
            $this->load->view('templates/footer', $this->cfg);
          }
          else if($this->input->post('tipo_relatorio') == '3')
          {
            if(empty($this->input->post('parametro')))
            {
              if($this->input->post('periodo_sim') == 'on')
              {
                $this->dados['relatorio_titulo'] = 'Relatório Geral de Obras';
                $this->dados['relatorio_periodo'] = '(De '.date('d/m/Y', strtotime($this->input->post('periodo-inicio'))).' até '.date('d/m/Y', strtotime($this->input->post('periodo-fim'))).')';
                $where = array(
                  'data_entrada >=' => $this->input->post('periodo-inicio'),
                  'data_entrada <=' => $this->input->post('periodo-fim')
                );
              }
              else
              {
                $this->dados['relatorio_titulo'] = 'Relatório Geral de Obras';
                $where = null;
              }
            }
            else
            {
              if($this->input->post('periodo_sim') == 'on')
              {
                $this->dados['relatorio_titulo'] = 'Relatório Individual da Obra';
                $this->dados['relatorio_periodo'] = '(De '.date('d/m/Y', strtotime($this->input->post('periodo-inicio'))).' até '.date('d/m/Y', strtotime($this->input->post('periodo-fim'))).')';
                $where = array(
                  'armazem like' => '%'.$this->input->post('parametro').'%',
                  'data_entrada >=' => $this->input->post('periodo-inicio'),
                  'data_entrada <=' => $this->input->post('periodo-fim')
                );
              }
              else
              {
                $this->dados['relatorio_titulo'] = 'Relatório Individual da Obra';
                $where = array(
                  'armazem like' => '%'.$this->input->post('parametro').'%'
                );
              }
            }

            if($where != null)
            {
              $this->dados['obras'] = $this->objobra->retorna_obras(array('descricao like' => '%'.$this->input->post('parametro').'%'));
            }

            $interna = $this->objsm->gerarelatorio_maninterna($where);
            $externa = $this->objsm->gerarelatorio_manexterna($where);

            $resultado_interna = array();
            $resultado_externa = array();
            $total_interna = 0;
            $total_externa = 0;

            foreach ($interna as $value)
            {
              $total_interna += $value->custo_interno;
              $temp = array(
                'id_man'        => $value->id_manutencao,
                'dt_realizada'  => date('d/m/Y h:i', strtotime($value->hora_iniciada)),
                'dt_finalizada' => date('d/m/Y h:i', strtotime($value->hora_finalizada)),
                'placa'         => $value->placa_numero,
                'custo'         => number_format($value->custo_interno, 2, '.', ','),
                'descricao'     => $value->descricao,
                'armazem'       => $value->armazem
              );

              array_push($resultado_interna, $temp);
            }

            foreach ($externa as $value)
            {
              $total_externa += $value->valor;
              $temp = array(
                'id_man'        => $value->id_manutencao,
                'dt_realizada'  => date('d/m/Y h:i', strtotime($value->hora_iniciada)),
                'dt_finalizada' => date('d/m/Y h:i', strtotime($value->hora_finalizada)),
                'placa'         => $value->placa_numero,
                'custo'         => number_format($value->valor, 2, '.', ','),
                'descricao'     => $value->descricao,
                'armazem'       => $value->armazem
              );

              array_push($resultado_externa, $temp);
            }


            $this->dados['manutencao_interna'] = $resultado_interna;
            $this->dados['manutencao_externa'] = $resultado_externa;
            $this->dados['total_interna'] = $total_interna;
            $this->dados['total_externa'] = $total_externa;
            $this->load->view('templates/header', $this->cfg);
            $this->load->view('user/relatorios/obras', $this->dados, $this->cfg);
            $this->load->view('templates/footer', $this->cfg);
          }
        }
        else
        {
          redirect($this->cfg['url_base'], 'refresh');
        }
      }
    }

    //  CARREGA A AJUDA
    public function ajuda()
    {
      if ($this->session->userdata('email') != "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {
        $result['success'] = false;

        $mensagem = 'Administrador! <br>Preciso de ajuda!<br>';
        $mensagem .= '<p>' . $this->input->post('mensagem').'<p><br>';

        $headers = "MIME-Version: 1.1\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= 'From: ' .$this->input->post('email-contato'). "\r\n";
        if($this->input->post('enviar-copia') == 'on')
        {
          $headers .= 'Cc: ' .$this->input->post('email-contato'). "\r\n";
        }
        $headers .= 'X-Mailer: PHP/' . phpversion();

        $envio = mail($this->cfg['email_contato'], $this->input->post('assunto'), $mensagem, $headers);

        if($envio)
        {
          $result['success'] = true;
        }

        echo json_encode($result);

      }
    }

    //  CARREGA A ALTUALIZAÇÕES
    public function cadastros($form)
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {
        $this->dados['produtos'] = $this->objprod->retorna_produto(array('estatus' => 1));
        $this->dados['obras'] = $this->objobra->retorna_obras(array('estatus' => 1));
        $this->dados['frotas'] = $this->objfrt->retorna_todos();
        $this->dados['futuras'] = $this->objman->retornaFuturas(array('cf.estatus' => 1, 'mf.estatus' => 1));
        $this->dados['formulario'] = $form;
        $this->load->view('templates/header.php', $this->cfg);
        $this->load->view('templates/loader.php', $this->cfg);
        $this->load->view('templates/menu.php', $this->cfg);
        $this->load->view('templates/notify.php', $this->dados, $this->cfg);
        $this->load->view('atualizacoes/index', $this->dados, $this->cfg);
        $this->load->view('templates/footer.php', $this->cfg);

      }
    }

    public function populaConsulta()
    {
        $result['produtos'] = array();
        $result['frotas'] = array();

        $result['produtos'] = $this->objprod->retorna_produto(array('estatus' => 1));
        $result['frotas'] = $this->objfrt->retorna_todos();

        echo json_encode($result);
    }

    //  CARREGA A ALTUALIZAÇÕES
    public function notfound()
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
        $this->load->view('not_found', $this->dados, $this->cfg);
        $this->load->view('templates/footer.php', $this->cfg);
      }
    }

    //  REGISTRA A ATIVIDADE DO USUÁRIO
    public function atividade()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {
        $dados = array(
          'id' => $this->session->userdata('id')
        );

        $this->objsm->atividade($dados);
      }
    }

    //  ATUALIZA O EMAIL DE CONTATO
    public function atualizaEmailContato()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {
        $result['success'] = false;

        $dados = array(
          'email_contato' => $this->input->post('email_contato')
        );

        if($this->objcfg->updateConfigs($dados))
        {
          $result['success'] = true;
        }

        echo json_encode($result);
      }
    }

    //  ATUALIZA O TEMPO MÁXIMO DE INATIVIDADE
    public function atualizaTempoInativo()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {
        $result['success'] = false;

        $dados = array(
          'tempo_minimo' => $this->input->post('tempo_inativo')
        );

        if($this->objcfg->updateConfigs($dados))
        {
          $result['success'] = true;
        }

        echo json_encode($result);
      }
    }

    public function notificacaoVista($id)
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {

        $where = array(
          'id_notificacao' => $id
        );

        $this->objsm->notificacaoVista($where);

      }

    }

    public function countNotify()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {
        $result['success'] = false;
        $result['dados'] = '';

        $count = $this->objsm->countNotify()[0];

        if($count->count > 0)
        {
          $result['success'] = true;
          $result['dados'] = $count;
        }

        echo json_encode($result);
      }

    }

    public function geraGrafico_custoMes()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {
        $result['success'] = false;

        try
        {

          $retorno = $this->objsm->geraGrafico_custoMes();

          if (count($retorno) != 0)
          {
            $dados['mes'] = array();

            $dados['total'] = array();

            foreach ($retorno as $valor)
            {
              if(intval($valor->mes_compras) != 0)
              {
                $mes = $valor->mes_compras;
              }
              else if(intval($valor->mes_externo) != 0)
              {
                $mes = $valor->mes_externo;
              }
              else if(intval($valor->mes_interno) != 0)
              {
                $mes = $valor->mes_interno;
              }

              $temp1 = ucfirst($this->dados['mes'][$mes - 1]);

              $temp2 = number_format($valor->total, 2, '.', '');

              array_push($dados['mes'], $temp1);
              array_push($dados['total'], $temp2);
            }

            $result['dados'] = $dados;
            $result['success'] = true;
          }

          echo json_encode($result);
        }
        catch(Exception $e)
        {
          echo json_encode($result);
        }

      }

    }

    public function geraGrafico_custoArea()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {
        $result['success'] = false;

        try
        {

          $retorno = $this->objsm->geraGrafico_custoArea();

          $result['dados'] = array();

          if (($retorno[0]->externo != 0)||($retorno[0]->interno != 0)||($retorno[0]->compras != 0))
          {
            foreach ($retorno as $value)
            {
              $result['dados'] = array(number_format($value->interno, 2, '.', ''), number_format($value->externo, 2, '.', ''), number_format($value->compras, 2, '.', ''));
            }

            $result['success'] = true;
          }

          echo json_encode($result);
        }
        catch(Exception $e)
        {
          echo json_encode($result);
        }

      }

    }

    public function confirmNotify()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {

        $result['success'] = false;

        $id_man = $this->input->post('id_manutencao');

        if($this->objsm->confirmNotify($id_man))
        {
          $result['success'] = true;
        }

        echo json_encode($result);

      }
    }

    public function cartDestroy()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {
        if(count($this->cart->contents()) != 0)
        {
          $this->cart->destroy();
        }
      }
    }

}
