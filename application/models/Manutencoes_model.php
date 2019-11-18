<?php
 defined('BASEPATH') OR exit('No direct script access allowed');


    class Manutencoes_model extends CI_Model
    {
        public function cadastra($tipo, $dados_externa, $dados_interna)
        {
          if($tipo == 1)
          {
            //Cadastro Manuen��o Corretiva Interna

            $sql = 'call sp_manutencao_realizada_interna(?,?,?,?,?,?,?)';

            if($this->db->query($sql, $dados_interna))
            {
              return true;
            }

            else
            {
              return false;
            }

          }
          else
          {
            //Cadastro Manuten��o Corretiva Externa

            $sql = 'call sp_manutencao_realizada_externa(?,?,?,?,?,?,?,?,?,?,?,?,?)';

            if($this->db->query($sql, $dados_externa))
            {
              return true;
            }

            else
            {
              return false;
            }

          }
        }

        public function agendar($dados)
        {
          $procedure = 'CALL sp_manutencao_futura(?,?,?,?,?,?)';

           if($this->db->query($procedure, $dados))
           {
              return true;
           }
           else
           {
              return false;
           }
        }

        public function retornaRealizada($where, $select)
        {
          $this->db->select($select);
          $this->db->from('tbl_manutencao_realizada m');
          $this->db->join('tbl_cad_frota c', 'm.id_veiculo = c.id_veiculo');
          $this->db->join('tbl_usuario u', 'm.id_usuario = u.id_usuario');
          $this->db->join('tbl_obra o', 'm.id_obra = o.id_obra');
          $this->db->where($where);
          $this->db->order_by('m.estatus', 'desc');
          return $this->db->get()->result();
        }

        public function retornaFuturas($where)
        {
          $this->db->select('id_futura, mf.descricao, data_prevista, data_minima, quilometragem, cf.placa_numero, u.email');
          $this->db->from('tbl_manutencao_futura mf');
          $this->db->join('tbl_cad_frota cf', 'cf.id_veiculo = mf.id_veiculo');
          $this->db->join('tbl_usuario u', 'u.id_usuario = mf.id_usuario');
          $this->db->where($where);
          return $this->db->get()->result();
        }

        public function edita_agendamento($dados, $where)
        {
          if($this->db->update('tbl_manutencao_futura', $dados, $where))
          {

            $this->db->select('count(*)');
            $this->db->from('tbl_manutencao_futura');
            $this->db->where($where);
            $notify = $this->db->get()->result();

            if(count($notify) != 0)
            {
              $where['situacao !='] = 3;

              if($this->db->update('tbl_notificacao', array('situacao' => 3), $where))
              {
                return true;
              }
              else
              {
                return false;
              }
            }
            else
            {
              return true;
            }

          }
          else
          {
            return false;
          }
        }

        public function excluir_agendada($where)
        {
          if($this->db->update('tbl_manutencao_futura', array('estatus' => 0), $where))
          {

            $where['estatus'] = 0;

            $this->db->select('count(*)');
            $this->db->from('tbl_manutencao_futura');
            $this->db->where($where);
            $notify = $this->db->get()->result();

            if(count($notify) != 0)
            {

              unset($where['estatus']);

              $where['situacao !='] = 3;

              if($this->db->update('tbl_notificacao', array('situacao' => 3), $where))
              {
                return true;
              }
              else
              {
                return false;
              }
            }
            else
            {
              return true;
            }

          }
          else
          {
            return false;
          }
        }

        public function estornar($where)
        {
          if($this->db->update('tbl_manutencao_realizada', array('estatus'=>0), $where))
          {
            return true;
          }
          else
          {
            return false;
          }
        }
    }
