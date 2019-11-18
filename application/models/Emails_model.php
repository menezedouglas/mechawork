<?php
  defined('BASEPATH') OR exit('No direct script access allowed');

  class Emails_model extends CI_Model
  {

    public function envia_email($f_para, $f_assunto, $f_mensagem)
    {

      $de = "contato@mechawork.tk";

      $para = $f_para;

      $assunto = $f_assunto;

      $mensagem = $f_mensagem;

      $headers = "From:". $de;
      $headers = "To:". $f_para;
      $headers = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

      if(mail($para, $assunto, $mensagem, $cabecalho))
      {
        return true;
      }
      else
      {
        return false;
      }

    }

  }
