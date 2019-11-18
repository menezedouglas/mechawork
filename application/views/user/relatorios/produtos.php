<?php date_default_timezone_set('America/Sao_Paulo'); ?>
<a href="<?= $url_base; ?>" id="btn_relvoltar" class="btn btn-outline-primary" style="position:absolute;">
  <i data-feather="chevrons-left"></i>
  Voltar
</a>
<div class="container">
  <div class="row m-1 mt-4">
    <div class="col-4">
      <img src="<?= $url_base; ?>/img/logo-v2.png" width="120px" class="float-left">
    </div>
    <div class="col-4 text-center">
      <h3 class="text-muted mt-3"><?= $relatorio_titulo; ?></h3>
    <?php if(isset($relatorio_periodo)){ ?>
      <h5 class="text-muted mt-3"><?= $relatorio_periodo; ?></h5>
    </div>
    <?php } else { echo '</div>'; }?>
    <div class="col-4">
      <img src="<?= $url_base; ?>/img/logo-iwg.png" width="140px" class="float-right">
    </div>
  </div>
  <?php if($relatorio_titulo != 'Relatório Geral de Entradas'){ ?>
  <div class="row mt-5">
    <div class="col-12">
      <h3 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">
        Veículo/Equipamento
      </h3>
      <hr class="my-2 mb-4">
      <table class="table table-light">
        <thead class="thead-light">
          <th scope="col">Código</th>
          <th scope="col">Descrição</th>
          <th scope="col">Unidade</th>
        </thead>
        <tbody>
          <?php foreach ($produto as $valor) { ?>
            <tr>
              <th scope="col"><?= $valor->codigo; ?></th>
              <td><?= $valor->descricao; ?></td>
              <td><?= $valor->unidade; ?></td>
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
        Entradas
      </h3>
      <hr class="my-2 mb-4">
      <table class="table table-light">
        <thead>
          <tr class="bg-secondary text-light">
            <th class="text-right" colspan="1">Total:</th>
            <th colspan="<?php if($relatorio_titulo == 'Relatório Geral de Entradas'){ echo '8'; } else { echo '6'; }?>">R$ <?= number_format($total_entradas, 2, '.', ','); ?></th>
          </tr>
          <tr class="thead-light">
            <th scope="col">#</th>
            <th scope="col">Data</th>
            <?php if($relatorio_titulo == 'Relatório Geral de Entradas'){ ?>
              <th scope="col">Produto</th>
              <th scope="col">Descrição</th>
            <?php } ?>
            <th scope="col">Nº da Nota</th>
            <th scope="col">Quantidade</th>
            <th scope="col">Unidade</th>
            <th scope="col">Valor Unitario</th>
            <th scope="col">Valor Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($entradas as $entrada) { ?>
            <tr>
              <th scope="col"><?= $entrada['id']; ?></th>
              <th scope="col"><?= $entrada['data']; ?></th>
              <?php if($relatorio_titulo == 'Relatório Geral de Entradas'){ ?>
                <td><?= $entrada['codigo']; ?></td>
                <td><?= $entrada['descricao']; ?></td>
              <?php } ?>
              <td><?= $entrada['nota']; ?></td>
              <td><?= $entrada['quantidade']; ?></td>
              <td><?= $entrada['unidade']; ?></td>
              <td>R$ <?= $entrada['vlr_unitario']; ?></td>
              <td>R$ <?= $entrada['vlr_total']; ?></td>
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
