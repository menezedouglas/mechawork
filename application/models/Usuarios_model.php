<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Usuarios_model extends  CI_Model
    {
        public function cadastra_usuario($dados)
        {
            $procedure = 'CALL sp_cadastra_usuario(?,?,?,?,?,?, @retorno)';

            if(($this->db->query($procedure, $dados)) !== NULL)
            {

              $stm = $this->db->query('select @retorno as retorno');

              return $stm;
            }
            else
            {
              return false;
            }

        }

        public function valida_email($dados)
        {
          if(($this->db->get_where('tbl_usuario', $dados)->result()) != null)
          {
            return true;
          }

          else
          {
            return false;
          }
        }

        public function retorna_usuarios()
        {
          return $this->db->get_where('tbl_usuario', array('estatus !=' => 0))->result();
        }

        public function retorna_usuario($dados)
        {
          return $this->db->get_where('tbl_usuario', $dados)->result();
        }

        public function login($dados)
        {
            return $this->db->query('call sp_login(?,?)', $dados)->result();
        }

        public function logout($dados)
        {
          $sql = 'call sp_desloga_usuario(?)';

          if($this->db->query($sql, $dados))
          {
            return true;
          }

          else
          {
            return false;
          }
        }

        public function editar_usuario($dados)
        {
          $sql = "update tbl_usuario set nome = ?, sobrenome = ?, telefone = ?, nivel = ? where id_usuario = ?";

          try
          {
            $this->db->query($sql, $dados);
            return true;
          }

          catch(Exception $e)
          {
            return false;
          }

        }

        public function resetpass($dados)
        {

          $sql = 'call sp_alterar_senha(?,?)';

          if($this->db->query($sql, $dados))
          {
            return true;
          }
          else
          {
            return false;
          }

        }

        public function retornaExcluidos()
        {
          $where = array('estatus' => 0);

          return $this->db->get_where('tbl_usuario', $where)->result();
        }

        public function validaUsuario($dados)
        {

          $this->db->select('*');
          $this->db->from('tbl_senha s');
          $this->db->join('tbl_usuario u', 'u.id_usuario = s.id_usuario');
          $this->db->where($dados);
          return $this->db->get()->result();

        }

        public function restauraExcluidos($dados)
        {

          $sql = 'update tbl_usuario set estatus = 1 where id_usuario = ?';

          if($this->db->query($sql, $dados))
          {
            return true;
          }
          else
          {
            return false;
          }

        }

        public function validar($dados)
        {
          $this->db->select('u.email, s.senha');
          $this->db->from('tbl_usuario u');
          $this->db->join('tbl_senha s', 'u.id_usuario = s.id_usuario');
          $this->db->where($dados);
          return $this->db->get()->result();
        }

        public function excluiusuario($id)
        {
          $sql_nivel = 'select nivel from tbl_usuario where id_usuario = ?';
          $sql_count_admin = 'select count(*) as result from tbl_usuario where nivel = 1';
          $sql_deleta = 'update tbl_usuario set estatus = 0 where id_usuario = ?';

          $stm_nivel = $this->db->query($sql_nivel, array('id_usuario'=>$id))->result();
          $stm_count_admin = $this->db->query($sql_count_admin)->result();

          if($stm_nivel[0]->nivel == 1)
          {
            if($stm_count_admin[0]->result > 1)
            {
              $data = array(
                'id' => $id
              );

              if($this->db->query($sql_deleta, $data))
              {
                return 1;
              }
              else
              {
                return 0;
              }

            }
            else
            {
              return 2;
            }
          }
          else
          {
            $data = array(
              'id' => $id
            );

            if($this->db->query($sql_deleta, $data))
            {
              return 1;
            }
            else
            {
              return 0;
            }
          }

        }

        public function retornaSenha($dados)
        {
          $sql = 'select * from tbl_senha where id_usuario = ?';

          return $this->db->query($sql, $dados)->result();

        }

    }
