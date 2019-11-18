<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Estoque_model extends CI_Model
    {

      public function entrada($dados)
      {
        sleep(2);

        $sql = 'call sp_entrada_estoque(?,?,?,?,?,?,?,?,?,?,?)';

        if($this->db->query($sql, $dados))
        {
          return true;
        }
        else
        {
          return false;
        }
      }

      public function saida($dados)
      {
        $sql = 'insert tbl_saida_produto (quantia_usada, id_estoque, id_manutencao) values (?,?,?)';
        if($this->db->query($sql, $dados))
        {
          return true;
        }
        else
        {
          return false;
        }

      }

      public function retorna_estoque($dados)
      {
        $this->db->select('*');
        $this->db->from('tbl_estoque_produtos e');
        $this->db->join('tbl_produto p', 'e.id_estoque = p.id_produto');
        $this->db->where($dados);
        $this->db->limit(10);
        $this->db->order_by('e.id_estoque', 'DESC');
        return $this->db->get()->result();
      }

      public function retorna_estoque_completo()
      {
        $this->db->select('*');
        $this->db->from('tbl_estoque_produtos e');
        $this->db->join('tbl_produto p', 'e.id_estoque = p.id_produto');
        $this->db->limit(10);
        $this->db->order_by('e.id_estoque', 'DESC');
        return $this->db->get()->result();
      }

      public function retorna_historico($select1, $dados1, $select2, $dados2)
      {
        $return = array();
        $this->db->select($select1);
        $this->db->from('vw_historico_entradas');
        $this->db->where($dados1);
        $entradas = $this->db->get()->result();
        array_push($return, $entradas);

        $this->db->select($select2);
        $this->db->from('vw_historico_saidas');
        $this->db->where($dados2);
        $this->db->group_by('id_manutencao');
        $saidas = $this->db->get()->result();
        array_push($return, $saidas);

        return $return;
      }

    }
