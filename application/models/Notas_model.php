<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Notas_model extends CI_Model
    {

      public function retorna($where)
      {
        $this->db->select('*');
        $this->db->from('vw_notas');
        $this->db->where($where);
        return $this->db->get()->result();
      }

      public function por_fornecedor($cnpj)
      {
        $this->db->select('*');
        $this->db->from('vw_notas');
        $this->db->where(array('cnpj' => $cnpj, 'estatus' => 1));
        $this->db->order_by('emissao', 'desc');
        $this->db->limit(10);
        return $this->db->get()->result();
      }

      public function estornar($where, $table)
      {
        if($this->db->update($table, array('estatus'=>0), $where))
        {
          return true;
        }
        else
        {
          return false;
        }
      }

    }
