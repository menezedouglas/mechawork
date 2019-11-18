<?php

  defined('BASEPATH') OR exit('No direct script access allowed');

  class Configuracoes_model extends CI_Model
  {

    public $cfg;

    public function getConfigs()
    {

      return $this->db->get('tbl_configuracao')->result();

    }

    public function setConfigs()
    {

      $data = $this->getConfigs();

      foreach ($data as $value)
      {

          $this->cfg['url_base'] = $value->url_base;

          $this->cfg['email_contato'] = $value->email_contato;

      }

      //HABILITA CONFIGURAÇÕES E PREPARA PARA O ENVIO AO FRONT-END
      $this->cfg['url_login'] = $this->cfg['url_base'].$this->config->item('url_login');
      $this->cfg['url_logout'] = $this->cfg['url_base'].$this->config->item('url_logout');
      $this->cfg['url_user_signin'] = $this->cfg['url_base'].$this->config->item('url_user_signin');
      $this->cfg['url_user_edit'] = $this->cfg['url_base'].$this->config->item('url_user_edit');
      $this->cfg['url_user_delete'] = $this->cfg['url_base'].$this->config->item('url_user_delete');
      $this->cfg['url_users'] = $this->cfg['url_base'].$this->config->item('url_users');
      $this->cfg['url_perfil'] = $this->cfg['url_base'].$this->config->item('url_perfil');
      $this->cfg['url_configuracoes'] = $this->cfg['url_base'].$this->config->item('url_configuracoes');
      $this->cfg['url_relatorios'] = $this->cfg['url_base'].$this->config->item('url_relatorios');
      $this->cfg['url_dashboard'] = $this->cfg['url_base'].$this->config->item('url_dashboard');
      $this->cfg['url_frota'] = $this->cfg['url_base'].$this->config->item('url_frota');
      $this->cfg['url_estoque'] = $this->cfg['url_base'].$this->config->item('url_estoque');
      $this->cfg['url_entradas'] = $this->cfg['url_base'].$this->config->item('url_entradas');
      $this->cfg['url_saidas'] = $this->cfg['url_base'].$this->config->item('url_saidas');
      $this->cfg['url_fornecedores'] = $this->cfg['url_base'].$this->config->item('url_fornecedores');
      $this->cfg['url_notas'] = $this->cfg['url_base'].$this->config->item('url_notas');
      $this->cfg['url_manutencoes'] = $this->cfg['url_base'].$this->config->item('url_manutencoes');
      $this->cfg['url_preventivas'] = $this->cfg['url_base'].$this->config->item('url_preventivas');
      $this->cfg['url_atualizacoes'] = $this->cfg['url_base'].$this->config->item('url_atualizacoes');
      $this->cfg['url_obras'] = $this->cfg['url_base'].$this->config->item('url_obras');
      $this->cfg['url_produtos'] = $this->cfg['url_base'].$this->config->item('url_produtos');
      $this->cfg['url_ajuda'] = $this->cfg['url_base'].$this->config->item('url_ajuda');
      $this->cfg['versao'] = $this->config->item('versao');
      $this->cfg['nivel_front'] = $this->config->item('nivel_front');
      $this->cfg['nivel_back'] = $this->config->item('nivel_back');
      $this->cfg['nivel_database'] = $this->config->item('nivel_database');
      $this->cfg['copyright'] = $this->config->item('copyright');

      $mobile = FALSE;

      $user_agents = array("iPhone","iPad","Android","webOS","BlackBerry","iPod","Symbian","IsGeneric");

      foreach($user_agents as $user_agent){

        if (strpos($_SERVER['HTTP_USER_AGENT'], $user_agent) !== FALSE) {

          $mobile = TRUE;

          $modelo	= $user_agent;

          break;

        }
      }
        
      if($this->session->userdata('email') != "")
      {

        $this->cfg['login_count'] = $this->session->userdata('login_count');

      }

      return $this->cfg;

    }

    public function updateConfigs($dados)
    {

      if($this->db->update('tbl_configuracao', $dados, "id_config = 1"))
      {
        return true;
      }
      else
      {
        return false;
      }

    }
  }

?>


