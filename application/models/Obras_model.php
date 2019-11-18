<?php
  defined('BASEPATH') OR exit('No direct script access allowed');

  class Obras_Model extends CI_Model
  {
  	public function cadastra_obra($dados)
  	{
  		if($this->db->insert('tbl_obra', $dados))
      {
        return true;
      }
      else
      {
        return false;
      }
  	}

    public function retorna_obras($dados)
    {
      return $this->db->get_where('tbl_obra', $dados)->result();
    }

    public function editar($dados, $where)
    {
      if($this->db->update('tbl_obra', $dados, $where))
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
      if($this->db->update('tbl_obra', array('estatus'=>0), $where))
      {
        return true;
      }
      else
      {
        return false;
      }
    }
}
