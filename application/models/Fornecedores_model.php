<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Fornecedores_model extends  CI_Model
    {
        public function cadastra_fornecedor($fornecedor)
        {
            $this->db->insert('tbl_fornecedor', $fornecedor);
        }

        public function retorna_fornecedor($dados)
        {
          return $this->db->get_where('tbl_fornecedor', $dados)->result();
        }

        public function editar($dados, $where)
        {
          if($this->db->update('tbl_fornecedor', $dados, $where))
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
          if($this->db->update('tbl_fornecedor', array('estatus'=>0), $where))
          {
            return true;
          }
          else
          {
            return false;
          }
        }

        public function restaurar($where)
        {
          if($this->db->update('tbl_fornecedor', array('estatus'=>1), $where))
          {
            return true;
          }
          else
          {
            return false;
          }
        }

    }
