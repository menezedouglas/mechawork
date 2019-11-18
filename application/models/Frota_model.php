<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Frota_model extends CI_Model
    {
        public function cadastra_frota($dados)
        {
          if($this->db->insert('tbl_cad_frota', $dados))
          {
              return true;
          }
          else
          {
              return false;
          }
        }

        public function retorna_todos()
        {
          return $this->db->get_where('tbl_cad_frota', array('estatus' => 1))->result();
        }

        public function validar($where)
        {
          if(count($this->db->get_where('tbl_cad_frota', $where)->result()) != 0)
          {
            return true;
          }
          else
          {
            return false;
          }
        }

        public function retornaFrota($where)
        {

          $frotas = null;

          if($where == null)
          {
            $frotas = $this->db->get_where('tbl_cad_frota', array('estatus' => 1))->result();
          }
          else
          {
            $frotas = $this->db->get_where('tbl_cad_frota', $where)->result();
          }

          $retorno = array();

          foreach($frotas as $frota)
          {
            $sql = 'select max(hora_iniciada) as ult_man from tbl_manutencao_realizada where id_veiculo = ?';

            $man = $this->db->query($sql, array('id_veiculo' => $frota->id_veiculo))->result();

            $temp = array(
                'id' => $frota->id_veiculo,
                'placa' => $frota->placa_numero,
                'descricao' => $frota->descricao,
                'ano' => $frota->ano,
                'fabricante' => $frota->fabricante,
                'tipo' => $frota->tipo,
                'ult_man' => $man[0]->ult_man
            );

            array_push($retorno, $temp);
          }

          return $retorno;

        }

        public function editar($dados, $where)
        {
          if($this->db->update('tbl_cad_frota', $dados, $where))
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
          if($this->db->update('tbl_cad_frota', array('estatus'=>0), $where))
          {
            return true;
          }
          else
          {
            return false;
          }
        }

        public function historico($where)
        {

          $this->db->select('id_manutencao, placa_numero, cf.descricao as nome, cf.ano, hora_iniciada, hora_finalizada, mr.descricao, nota_servico, custo_interno, ob.descricao as obra, email');
          $this->db->from('tbl_manutencao_realizada mr');
          $this->db->join('tbl_cad_frota cf', 'mr.id_veiculo = cf.id_veiculo');
          $this->db->join('tbl_usuario us', 'mr.id_usuario = us.id_usuario');
          $this->db->join('tbl_obra ob', 'mr.id_obra = ob.id_obra');
          $this->db->where($where);
          $this->db->order_by('hora_iniciada', 'desc');
          $this->db->limit(10);
          return $this->db->get()->result();

        }
    }

?>
