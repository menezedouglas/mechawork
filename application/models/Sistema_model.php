<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Sistema_model extends CI_Model
    {

      public function atividade($dados)
      {
        $sql = 'call sp_usuario_ativo(?)';

        $this->db->query($sql, $dados);
      }

      public function query($sql)
      {
        return $this->db->query($sql)->result();
      }

      public function pesquisa($dados)
      {
        $sql = 'call sp_consulta(?,?,?)';

        return $this->db->query($sql, $dados)->result();
      }

      public function exclui($dados)
      {
        $sql = 'call sp_exclui(?,?)';

        if($this->db->query($sql, $dados))
        {
          return true;
        }
        else
        {
          return false;
        }
      }

      public function notificacoes()
      {
        $this->db->select('*');
        $this->db->from('tbl_notificacao');
        $this->db->where(array('situacao <' => 3));
        $this->db->order_by('data_cadastrada', 'desc');
        return $this->db->get()->result();
      }

      public function notificacaoVista($where)
      {
        $this->db->update('tbl_notificacao', array('situacao' => 2), $where);
      }

      public function countNotify()
      {
        return $this->db->query('select count(id_notificacao) as count from tbl_notificacao where situacao = 1')->result();
      }

      public function geraGrafico_custoMes()
      {
        $this->db->select('*');
        $this->db->from('tbl_grafico_p');
        $this->db->order_by('mes_interno', 'asc');
        return $this->db->get()->result();
      }

      public function geraGrafico_custoArea()
      {
        $sql = 'call grafico_area()';

        return $this->db->query($sql)->result();
      }

      public function gerarelatorio_maninterna($where)
      {
        $this->db->select('*');
        $this->db->from('vw_manutencao_interna');
        if($where != null)
        {
          $this->db->where($where);
        }
        return $this->db->get()->result();
      }

      public function gerarelatorio_manexterna($where)
      {
        $this->db->select('*');
        $this->db->from('vw_manutencao_externa');
        if($where != null)
        {
          $this->db->where($where);
        }
        return $this->db->get()->result();
      }

      public function confirmNotify($id_notify)
      {

        $status = array('notificado'=>1,'visualizada'=>2,'atendida'=>3);

        $this->db->select('id_futura, situacao');
        $this->db->from('tbl_notificacao');
        $this->db->where('id_notificacao', $id_notify);

        $notify = $this->db->get()->result();

        if($status[$notify[0]->situacao] != 3)
        {

          if($this->db->update('tbl_notificacao', array('situacao' => 'atendida'), array('id_notificacao' => $id_notify)))
          {
            if($this->db->insert('tbl_controle', array('id_futura' => $notify[0]->id_futura, 'id_manutencao' => $id_notify)))
            {
              return true;
            }
            else
            {
              $this->db->update('tbl_notificacao', 'situacao = 2', array('id_notificacao' => $id_notify));
              return false;
            }
          }
          else
          {
            return false;
          }

        }
        else
        {
          return false;
        }



      }

    }
