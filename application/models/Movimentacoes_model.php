<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Movimentacoes_model extends CI_Model
    {

      public function retorna_entrada($where, $select)
      {
        $this->db->select($select);
        $this->db->from('tbl_entrada_produto e');
        $this->db->join('tbl_produto p', 'e.id_produto = p.id_produto');
        $this->db->where($where);
        $this->db->order_by('e.estatus', 'desc');
        return $this->db->get()->result();
      }

      public function retorna_saida($where, $select)
      {
        $this->db->select($select);
        $this->db->from('tbl_saida_produto e');
        $this->db->join('tbl_produto p', 'e.id_estoque = p.id_produto');
        $this->db->join('tbl_estoque_produtos ep', 'ep.id_estoque = p.id_produto');
        $this->db->where($where);
        $this->db->order_by('e.estatus', 'desc');
        return $this->db->get()->result();
      }

    }
