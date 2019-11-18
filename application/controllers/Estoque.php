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

class Estoque extends CI_Controller
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
    $this->load->model('Estoque_model', 'objestq');
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

      $this->load->view('consultas/estoque/index', $this->dados, $this->cfg);

      $this->load->view('templates/footer.php', $this->cfg);
    }
  }

  /*
  	ADICIONA PRODUTO A LISTA
  */
  public function AdicionaProduto()
  {
    if ($this->session->userdata('email') == "")
    {
      redirect($this->cfg['url_logout'], 'refresh');
    }
    else
    {
      $result['success'] = false;
      $result['produtos'] = array();
      $result['total'] = 0;


      if($this->session->man_cart_control == '1')
      {
        $this->session->set_userdata('man_cart_control', '0');
        $this->cart->destroy();
      }

      $produto = $this->objprod->retorna_produto(array('codigo' => $this->input->post('cod_produto')));

  		$dados = array(
  			'rowid'   => $this->input->post('cod_produto'),
  			'id'      => $produto[0]->codigo,
  			'qty'     => $this->input->post('qntd'),
  			'price'   => $this->input->post('val_unitario'),
  			'name'    => $produto[0]->descricao
  		);

      $this->cart->insert($dados);

  		if(count($this->cart->contents()) != 0)
      {
        $this->session->set_userdata('estq_cart_control', '1');

        if($this->cart->total_items() > 0)
        {
          $total = 0;

           foreach($this->cart->contents() as $prod)
           {

             $total += $prod['price']*$prod['qty'];

             $temp = array(
               'codigo' => $prod['id'],
               'descricao' => $prod['name'],
               'unidade' => $produto[0]->unidade,
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

      echo json_encode($result);
    }
  }

  /*
    ENTRADA NO ESTOQUE
  */
  public function entrada()
  {
    if ($this->session->userdata('email') == "")
    {
      redirect($this->cfg['url_logout'], 'refresh');
    }
    else
    {

      $codigo_prod = '';
      $quantia = '';
      $valor_unitario = '';
      $itens = 0;

      $result['success'] = false;

      foreach ($this->cart->contents() as $produto)
      {
  			if($itens > 0)
        {
          $codigo_prod .= '|'.$produto['id'];
  				$quantia .= '|'.$produto['qty'];
  				$valor_unitario .= '|'.$produto['price'];
          $itens ++;
        }
        else
        {
          $codigo_prod .= $produto['id'];
  				$quantia .= $produto['qty'];
  				$valor_unitario .= $produto['price'];
          $itens ++;
        }
      }

      $dados = array(
        'cnpj'            => $this->input->post('cnpj'),
        'nome'            => $this->input->post('razao-social'),
        'telefone'        => $this->input->post('telefone'),
        'valor_total'     => $this->input->post('v_nf'),
        'valor_unitario'  => $valor_unitario,
        'nota'            => $this->input->post('n_nf'),
        'data_emissao'    => $this->input->post('dt_emissao'),
        'data_vencimento' => $this->input->post('dt_vencimento'),
        'codigo_prod'     => $codigo_prod,
        'quantia'         => $quantia,
        'itens'           => $itens
      );

      if($this->objestq->entrada($dados))
      {
        $this->cart->destroy();
        $result['success'] = true;
      }

      echo json_encode($result);
    }
  }

  /*
    PESQUISAR
  */
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

      $estoque = null;

      if(!empty($this->input->post('valor')))
      {
        $dados = array(
          $this->input->post('campo').' like ' => '%'.$this->input->post('valor').'%',
          'e.estatus' => 1
        );

        $estoque = $this->objestq->retorna_estoque($dados);
      }
      else
      {
        $estoque = $this->objestq->retorna_estoque_completo();
      }


      if(count($estoque) != 0)
      {
        $retorno = array();

        foreach ($estoque as $produto)
        {
          $temp = array(
            'codigo' => $produto->codigo,
            'produto' => $produto->descricao,
            'qntd' => $produto->quantia,
            'unidade' => $produto->unidade,
            'val_unitario' => number_format($produto->valor_unitario_medio, 2, ',', '.'),
            'val_total' => number_format($produto->valor_unitario_medio*$produto->quantia, 2, ',', '.'),
            'situacao' => $produto->estatus,
            'id_produto' => $produto->id_produto,
            'id_estoque' => $produto->id_estoque
          );

          array_push($retorno, $temp);
        }

        $result['success'] = true;
        $result['dados'] = $retorno;
      }

      echo json_encode($result);
    }
  }

  /*
    HISTÓRICO DE MOVIMENTAÇÕES
  */
  public function historico($produto)
  {
    if ($this->session->userdata('email') == "")
    {
      redirect($this->cfg['url_logout'], 'refresh');
    }
    else
    {
      $select1 = 'id_estoque, id_entrada, data_entrada, nota_compra, data_compra, data_vencimento, codigo, descricao, quantia_comprada, unidade, valor_unitario, (valor_unitario*quantia_comprada) valor_total, valor valor_nota';

      $select2 = '*';

      $result['success'] = false;
      $result['dados_entradas'] = array();
      $result['dados_saidas'] = array();

      $dados = array(
        'id_produto' => $produto,
        'estatus' => 1
      );

      $stm = $this->objestq->retorna_historico($select1, $dados, $select2, $dados);

      if(count($stm) != 0)
      {

        $entradas = array();
        $saidas = array();

        foreach ($stm[0] as $nota)
        {
          $temp = array(
            'id' => $nota->id_entrada,
            'dt_entrada' => date("d/m/Y", strtotime($nota->data_entrada)),
            'n_nota' => $nota->nota_compra,
            'dt_nota' =>  date("d/m/Y", strtotime($nota->data_vencimento)),
            'qntd' => $nota->quantia_comprada,
            'vlr_unitario' => number_format($nota->valor_unitario, 2, ',', '.'),
            'vlr_total' =>  number_format($nota->valor_total, 2, ',', '.'),
            'codigo' => $nota->codigo,
            'descricao' => $nota->descricao,
            'unidade' => $nota->unidade
          );

          array_push($entradas, $temp);
        }

        foreach ($stm[1] as $saida)
        {
          $temp = array(
            'id' => $saida->id_saida,
            'data' => date('d/m/Y', strtotime($saida->data_saida)),
            'produto' => $saida->codigo,
            'qnt' => $saida->quantia_usada,
            'valor_unitario' => number_format($saida->valor_unitario, 2, ',', '.'),
            'total' => number_format($saida->valor_unitario*$saida->quantia_usada, 2, ',', '.'),
            'id_manutencao' => $saida->id_manutencao,
            'dt_manutencao' => date('d/m/Y', strtotime($saida->data_realizada)),
            'custo' => $saida->custo_interno,
            'usuario' => $saida->email
          );

          array_push($saidas, $temp);
        }

        $result['success'] = true;
        $result['dados_entradas'] = $entradas;
        $result['dados_saidas'] = $saidas;

      }

      echo json_encode($result);
    }
  }

}
