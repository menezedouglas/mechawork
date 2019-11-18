<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Produtos_model extends CI_Model{

    public function cadastra_produto($dados)
    {
    	if($this->db->insert('tbl_produto', $dados))
      {
        return true;
      }
      else
      {
        return false;
      }
    }

    public function valida_produtos($dados)
    {
      $this->db->select('count(id_produto) as count');
      $this->db->from('tbl_produto');
      $this->db->where($dados);

      $dados = $this->db->get()->result();

      if($dados[0]->count == 1)
      {
        return true;
      }
      else
      {
        return false;
      }
    }

    public function retorna_produto($dados)
    {
      return $this->db->get_where('tbl_produto', $dados)->result();
    }

    public function retorna_produto_c_estoque($dados)
    {
      $this->db->select('*');
      $this->db->from('tbl_produto');
      $this->db->join('tbl_estoque_produtos', 'id_produto = id_estoque');
      $this->db->where($dados);

      return $this->db->get()->result();
    }

    public function historico($where)
    {
      $this->db->select('*');
      $this->db->from('vw_historico_entradas');
      if($where != null)
      {
        $this->db->where($where);
      }
      return $this->db->get()->result();
    }

    public function editar($dados, $where)
    {
      if($this->db->update('tbl_produto', $dados, $where))
      {
        return true;
      }
      else
      {
        return false;
      }
    }

    public function excluir($where)
    {
      if($this->db->update('tbl_produto', array('estatus'=>0), $where))
      {
        return true;
      }
      else
      {
        return false;
      }
    }

}
