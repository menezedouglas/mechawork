<?php date_default_timezone_set('America/Sao_Paulo'); ?>
<a href="<?= $url_base; ?>" id="btn_relvoltar" class="btn btn-outline-primary" style="position:absolute;">
  <i data-feather="chevrons-left"></i>
  Voltar
</a>
<div class="container">
  <div class="row m-1 mt-4">
    <div class="col-3">
      <img src="<?= $url_base; ?>/img/logo-v2.png" width="120px" class="float-left">
    </div>
    <div class="col-6 text-center">
      <h3 class="text-muted mt-3"><?= $relatorio_titulo; ?></h3>
      <h4 class="text-muted mt-3"><?= $tipo_frota_titulo; ?></h4>
    <?php if(isset($relatorio_periodo)){ ?>
      <h5 class="text-muted mt-3"><?= $relatorio_periodo; ?></h5>
    </div>
    <?php } else { echo '</div>'; }?>
    <div class="col-3">
      <img src="<?= $url_base; ?>/img/logo-iwg.png" width="140px" class="float-right">
    </div>
  </div>
  <?php if($relatorio_titulo != 'Relatório de Manutenção Geral'){ ?>
  <div class="row mt-5">
    <div class="col-12">
      <h3 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">
        Veículo/Equipamento
      </h3>
      <hr class="my-2 mb-4">
      <table class="table table-light">
        <thead class="thead-light">
          <th scope="col">Placa/Nº</th>
          <th scope="col">Descrição</th>
          <th scope="col">Ano</th>
          <th scope="col">Fabricante</th>
          <th scope="col">Tipo</th>
          <th scope="col">Ultima Manutenção</th>
        </thead>
        <tbody>
          <?php foreach ($frotas as $frota) { ?>
            <tr>
              <th scope="col"><?= $frota['placa']; ?></th>
              <td><?= $frota['descricao']; ?></td>
              <td><?= $frota['ano']; ?></td>
              <td><?= $frota['fabricante']; ?></td>
              <td><?= $frota['tipo']; ?></td>
              <td><?= date('d/m/Y h:i', strtotime($frota['ult_man'])); ?></td>
            </tr>
          <?php }?>
        </tbody>
      </table>
    </div>
  </div>
  <?php } ?>
  <div class="row mt-5">
    <div class="col-12">
      <h3 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">
        Manutenções Internas
      </h3>
      <hr class="my-2 mb-4">
      <table class="table table-light">
        <thead>
          <tr class="bg-light text-dark">
            <th class="text-right" colspan="1">Total:</th>
            <th colspan="<?php if($relatorio_titulo == 'Relatório de Manutenção Geral'){ echo '6'; } else { echo '5'; }?>">R$ <?= number_format($total_interna, 2, '.', ','); ?></th>
          </tr>
          <tr class="thead-light">
            <th scope="col">#</th>
            <?php if($relatorio_titulo == 'Relatório de Manutenção Geral'){ ?>
              <th scope="col">Placa</th>
            <?php } ?>
            <th scope="col">Início</th>
            <th scope="col">Término</th>
            <th scope="col">Descrição</th>
            <th scope="col">Valor</th>
            <th scope="col">Armazém</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($manutencao_interna as $relatorio) { ?>
            <tr>
              <th scope="col"><?= $relatorio['id_man']; ?></th>
              <?php if($relatorio_titulo == 'Relatório de Manutenção Geral'){ ?>
                <td><?= $relatorio['placa']; ?></td>
              <?php } ?>
              <td><?= $relatorio['dt_realizada']; ?></td>
              <td><?= $relatorio['dt_finalizada']; ?></td>
              <td><?= $relatorio['descricao']; ?></td>
              <td>R$ <?= $relatorio['custo']; ?></td>
              <td><?= $relatorio['armazem']; ?></td>
            </tr>
          <?php }?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="row mt-5">
    <div class="col-12">
      <h3 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">
        Manutenções Externas
      </h3>
      <hr class="my-2 mb-4">
      <table class="table table-light">
        <thead>
          <tr class="bg-light text-dark">
            <th class="text-right" colspan="1">Total:</th>
            <th colspan="<?php if($relatorio_titulo == 'Relatório de Manutenção Geral'){ echo '6'; } else { echo '5'; }?>">R$ <?= number_format($total_externa, 2, '.', ','); ?></th>
          </tr>
          <tr class="thead-light">
            <th scope="col">#</th>
            <?php if($relatorio_titulo == 'Relatório de Manutenção Geral'){ ?>
              <th scope="col">Placa</th>
            <?php } ?>
            <th scope="col">Início</th>
            <th scope="col">Término</th>
            <th scope="col">Descrição</th>
            <th scope="col">Valor</th>
            <th scope="col">Armazém</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($manutencao_externa as $relatorio) { ?>
            <tr>
              <th scope="col"><?= $relatorio['id_man']; ?></th>
              <?php if($relatorio_titulo == 'Relatório de Manutenção Geral'){ ?>
                <td><?= $relatorio['placa']; ?></td>
              <?php } ?>
              <td><?= $relatorio['dt_realizada']; ?></td>
              <td><?= $relatorio['dt_finalizada']; ?></td>
              <td><?= $relatorio['descricao']; ?></td>
              <td>R$ <?= $relatorio['custo']; ?></td>
              <td><?= $relatorio['armazem']; ?></td>
            </tr>
          <?php }?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="row mt-2 col-12">
    Relatório emitido dia <?php echo date('d/m/Y'); ?> as <?php echo date('g:iA'); ?> (Horário de Brasília)
  </div>
</div>
