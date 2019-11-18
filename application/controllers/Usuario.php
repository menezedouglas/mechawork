<?php
  defined('BASEPATH') OR exit('No direct script access allowed');

  	/*

  		CENTRO PAULA SOUZA
  		ESCOLA TÉCNICA ESTADUAL DE EMBU - Embu das Artes - SP
  		CURSO TÉCNICO DE NÍVEL MÉDIO EM INFORMÁTICA

  		SISTEMA: MECHAWORK - Gerenciamento de Oficina Mecânica
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

  class Usuario extends CI_Controller
  {

    public $cfg;

    public $dados;

    public function __construct()
    {
      parent::__construct();
      $this->load->model('Configuracoes_model', 'objcfg');
      $this->load->model('Usuarios_model', 'objusu');
      $this->load->model('Emails_model', 'objmail');
      $this->load->model('Sistema_model', 'objsm');

      //  CARREGA ARQUIVO DE CONFIGURAÇÕES
      $this->config->load('mw_conf');

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

    //Realizar login do usuário
    public function entrar()
    {
      $result['success'] = False;
      $result['jaemuso'] = False;

      $dados = array(
        'email' => $this->input->post('email'),
        'senha' => md5($this->input->post('senha'))
      );

      $retorno = $this->objusu->login($dados);

      if($retorno != null)
      {
        if($retorno[0]->estatus == 1)
        {
          $this->session->set_userdata('id', $retorno[0]->id_usuario);
          $this->session->set_userdata('nome', $retorno[0]->nome);
          $this->session->set_userdata('sobrenome', $retorno[0]->sobrenome);
          $this->session->set_userdata('email', $retorno[0]->email);
          $this->session->set_userdata('senha', md5($this->input->post('senha')));
          $this->session->set_userdata('nivel', $retorno[0]->nivel);
          $this->session->set_userdata('login_count', 1);

          $result['success'] = True;
        }
        else
        {
          $result['jaemuso'] = True;
        }
      }

      echo json_encode($result);
    }

    //Cadastrar o usuário
    public function cadastrar()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {

        $result['success'] = False;
        $result['senha_error'] = False;
        $result['email_error'] = False;
        $result['existe'] = False;
        $result['msg'] = null;

          if($this->input->post('email') == $this->input->post('cEmail'))
          {
            if($this->input->post('senha') == $this->input->post('cSenha'))
            {
              $dados['nome'] = $this->input->post('nome');
              $dados['sobrenome'] = $this->input->post('sobrenome');
              $dados['telefone'] = $this->input->post('telefone');
              $dados['email'] = $this->input->post('email');
              $dados['senha'] = md5($this->input->post('senha'));
              $dados['nivel'] = (int) $this->input->post('nivel');

              $valida['email'] = $this->input->post('email');

              if($this->objusu->valida_email($valida))
              {
                $result['existe'] = True;
              }

              else
              {
                $mensagem = 'Parabéns! <br>Você foi cadastrado em nosso sistema!<br>';

                $mensagem .= '<p>Seu usuario: ' . $this->input->post('email').'<p><br>';

                $mensagem .= '<p>Sua senha: ' . $this->input->post('senha').'<p><br>';


                $headers = "MIME-Version: 1.1\r\n";
                $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                $headers .= 'To: '.$this->input->post('email'). "\r\n";
                $headers .= 'From: '.$this->cfg['email_contato']."\r\n";
                $headers .= 'X-Mailer: PHP/' . phpversion();

                $envio = mail($this->input->post('email'), 'MECHAWORK - Conta criada!', $mensagem, $headers);

                if($envio)
                {
                  $return = $this->objusu->cadastra_usuario($dados);

                  if($return != false)
                  {
                    $result['success'] = True;
                  }
                }
              }
            }

            else
            {
              $result['senha_error'] = True;
            }
          }

          else
          {
            $result['email_error'] = True;
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
        $result['dados'] = null;

        $return = $this->objusu->retorna_usuario(array('id_usuario'=>$id));

        if($return != null)
        {
          $result['dados']['id'] = $id;
          $result['dados']['nome'] = $return[0]->nome;
          $result['dados']['sobrenome'] = $return[0]->sobrenome;
          $result['dados']['email'] = $return[0]->email;
          $result['dados']['telefone'] = $return[0]->telefone;
          $result['dados']['nivel'] = $return[0]->nivel;
          $result['success'] = True;
        }

        echo json_encode($result);
      }
    }

    //Deletar usuário
    public function deletar()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {
        $result['success'] = False;
        $result['umadmin'] = False;
        $result['emuso'] = False;

        $id = $this->input->post('id');

        if($this->input->post('id') != $this->session->userdata('id'))
        {
          $return = $this->objusu->excluiUsuario($id);

          if($return == 1)
          {
            $result['success'] = True;
          }
          else if($return == 2)
          {
            $result['umadmin'] = True;
          }
        }

        else
        {

          $return = $this->objusu->excluiUsuario($id);

          if($return == 1)
          {
            $result['success'] = Flase;
            $result['emuso'] = True;
          }
          else if($return == 2)
          {
            $result['umadmin'] = True;
          }
        }
        echo json_encode($result);
      }
    }

    //Editar usuário
    public function editar()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {

        $result['success'] = False;

        $dados = array(

          'nome'        => $this->input->post('nome'),
          'sobrenome'   => $this->input->post('sobrenome'),
          'telefone'    => $this->input->post('telefone'),
          'nivel'       => $this->input->post('nivel'),
          'id_usuario'  => $this->input->post('id_usuario')

        );

        if($this->objusu->editar_usuario($dados))
        {
          $result['success'] = True;
        }

        if($result['success'])
        {
          $mensagem = 'Seu perfil foi alterado por '.$this->session->userdata('email').'! <br> Confira os novos dados abaixo! <br>';

          $mensagem .= 'Nome Completo: ' . $this->input->post('nome').' '.$this->input->post('sobrenome').'<br>';

          $mensagem .= 'Telefone: ' . $this->input->post('telefone').'<br>';

          $nivel = '';

          if($this->input->post('nivel') == '1'){ $nivel = 'Administrador'; }else{ $nivel = 'Usuário'; }

          $mensagem .= 'Nível de acesso: ' .$nivel.'<br>';

          $mensagem .= 'Acesse o sistema <a href="'.$this->cfg['url_base'].'">aqui</a>!';

          $headers = "MIME-Version: 1.1\r\n";
          $headers .= "Content-type: text/html; charset=UTF-8\r\n";
          $headers .= 'To: '.$this->input->post('email'). "\r\n";
          $headers .= 'From: '.$this->cfg['email_contato']."\r\n";
          $headers .= 'X-Mailer: PHP/' . phpversion();

          mail($this->input->post('email'), 'MECHAWORK - Perfil alterado!', $mensagem, $headers);
        }

        echo json_encode($result);
      }
    }

    //Sair do sistema
    public function sair()
    {
      $dados = array(
        'id_usuario' => $this->session->userdata('id')
      );

      if($this->objusu->logout($dados))
      {
          $this->session->sess_destroy();  //Destrói a sessão

          redirect($this->cfg['url_base'], 'refresh'); //Redireciona
      }
    }

    //perfil do usuário
    public function perfil()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {
        $dados = array('id_usuario' => $this->session->userdata('id'));
        $this->dados['usuario'] = $this->objusu->retorna_usuario($dados);
        $this->load->view('templates/header.php', $this->cfg);
        $this->load->view('templates/loader.php', $this->cfg);
        $this->load->view('templates/menu.php', $this->cfg);
        $this->load->view('templates/notify.php', $this->dados, $this->cfg);
        $this->load->view('user/perfil/index', $this->dados, $this->cfg);
        $this->load->view('templates/footer.php', $this->cfg);
      }
    }

    public function resetpass()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {

        $result['success'] = False;

        $dados = array(
          'email' => $this->input->post('email'),
          'senha' => md5($this->input->post('senha'))
        );

        $mensagem = 'Sua senha foi alterada pelo administrador! <br>';
        $mensagem .= 'Nova senha: ' . $this->input->post('senha').'<br>';
        $mensagem .= 'Acesse o sistema <a href="'.$this->cfg['url_base'].'">aqui</a>!';

        $headers = "MIME-Version: 1.1\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= 'To: '.$this->input->post('email'). "\r\n";
        $headers .= 'From: '.$this->cfg['email_contato']."\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();

        $envio = mail($this->input->post('email'), 'MECHAWORK - Senha alterada!', $mensagem, $headers);

        if($envio)
        {

          if($this->objusu->resetpass($dados))
          {
            $result['success'] = True;
          }

        }

        echo json_encode($result);
      }
    }

    public function validar()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {
        $result['success'] = False;
        $result['erro_nivel'] = False;
        $result['error_pass'] = False;

        if(!empty($this->input->post()))
        {
          $dados = array('email' => $this->session->userdata('email'), 'senha' => md5($this->input->post('senha_atual')),'u.estatus' => 2,'s.estatus'=>1);
        }
        else
        {
          $dados = array('email' => $this->session->userdata('email'), 'senha' => $this->session->userdata('senha'),'u.estatus' => 2,'s.estatus'=>1);
        }

        $id = array('id_usuario' => $this->session->userdata('id'));
        $retorno = $this->objusu->retorna_usuario($id);

        if($retorno[0]->estatus == 2)
        {
          if($retorno[0]->nivel != $this->session->userdata('nivel'))
          {
            $result['erro_nivel'] = True;
          }
          else
          {
            $user = $this->objusu->validaUsuario($dados);


            if(count($user) == 1)
            {
              $result['success'] = True;
            }
            else
            {
              $result['error_pass'] = True;
            }

          }
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

        $result['success'] = False;

        foreach($this->input->post()['id_usuario'] as $id)
        {

          $dados = array('id_usuario' => $id);

          if($this->objusu->restauraExcluidos($dados))
          {
            $result['success'] = True;
          }
          else
          {
            $result['success'] = False;
          }

        }

        echo json_encode($result);
      }
    }

    public function alterarsenha()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {

        $result['success'] = false;
        $result['error_pass'] = false;
        $result['error_validate'] = false;
        $result['error_edit_pass'] = false;
        $result['password_exists'] = false;

        if($this->input->post('nova_senha') == $this->input->post('cNova_senha'))
        {
          $dados_validar = array(
            'email' => $this->input->post('email'),
            'senha' => md5($this->input->post('senha_atual')),
            'u.estatus' => 2,
            's.estatus' => 1
          );

          $dados_alterar = array(
            'email' => $this->input->post('email'),
            'senha' => md5($this->input->post('nova_senha'))
          );

          $dados = array(
            'id_usuario' => $this->session->userdata('id')
          );

          $senhas = $this->objusu->retornaSenha($dados);

          if($this->objusu->validar($dados_validar))
          {

            if(count($senhas)!= 0)
            {
              foreach ($senhas as $senha)
              {
                if($senha->senha == md5($this->input->post('nova_senha')))
                {
                  $result['password_exists'] = true;
                  break;
                }
              }

              if(!$result['password_exists'])
              {
                if($this->objusu->resetpass($dados_alterar))
                {

                  $result['success'] = true;

                }

                else
                {

                  $result['error_edit_pass'] = true;

                }
              }
            }
            else
            {
              if($this->objusu->resetpass($dados_alterar))
              {

                $result['success'] = true;

              }

              else
              {

                $result['error_edit_pass'] = true;

              }
            }

          }
          else
          {

            $result['error_validate'] = true;

          }

        }

        else
        {

          $result['error_pass'] = true;

        }

        if($result['success'])
        {
          $mensagem = 'Sua senha foi alterada usando seu perfil! <br>';

          $mensagem .= 'Nova senha: ' . $this->input->post('nova_senha').'<br>';

          $mensagem .= 'Acesse o sistema <a href="'.$this->cfg['url_base'].'">aqui</a>!';


          $headers = "MIME-Version: 1.1\r\n";
          $headers .= "Content-type: text/html; charset=UTF-8\r\n";
          $headers .= 'To: '.$this->input->post('email'). "\r\n";
          $headers .= 'From: '.$this->cfg['email_contato']."\r\n";
          $headers .= 'X-Mailer: PHP/' . phpversion();

          mail($this->input->post('email'), 'MECHAWORK - Senha alterada!', $mensagem, $headers);
        }

        echo json_encode($result);
      }
    }

    public function atualizasessao()
    {
      if ($this->session->userdata('email') == "")
      {
        redirect($this->cfg['url_logout'], 'refresh');
      }
      else
      {
        $dados = array(
          'email' => $this->session->userdata('email')
        );

        $retorno = $this->objusu->retorna_usuario($dados);

        if($retorno != null)
        {
          $this->session->set_userdata('id', $retorno[0]->id_usuario);
          $this->session->set_userdata('nome', $retorno[0]->nome);
          $this->session->set_userdata('sobrenome', $retorno[0]->sobrenome);
          $this->session->set_userdata('email', $retorno[0]->email);
          $this->session->set_userdata('nivel', $retorno[0]->nivel);
        }
      }
    }

  }
