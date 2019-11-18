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

  <?php if($relatorio_titulo != 'Relatório Geral de Obras'){ ?>

  <div class="row mt-5">

    <div class="col-12">

      <h3 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">

        Obra

      </h3>

      <hr class="my-2 mb-4">

      <table class="table table-light">

        <thead class="thead-light">

          <th scope="col">Descrição</th>

        </thead>

        <tbody>

          <?php foreach ($obras as $obra) { ?>

            <tr>

              <td><?= $obra['descricao']; ?></td>

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

          <tr class="bg-secondary text-light">

            <th class="text-right" colspan="1">Total:</th>

            <th colspan="<?php if($relatorio_titulo == 'Relatório Geral de Obras'){ echo '6'; } else { echo '5'; }?>">R$ <?= number_format($total_interna, 2, '.', ','); ?></th>

          </tr>

          <tr class="thead-light">

            <th scope="col">#</th>

            <th scope="col">Placa</th>

            <th scope="col">Início</th>

            <th scope="col">Término</th>

            <th scope="col">Descrição</th>

            <th scope="col">Valor</th>

            <?php if($relatorio_titulo == 'Relatório Geral de Obras'){ ?>

              <th scope="col">Armazém</th>

            <?php } ?>

          </tr>

        </thead>
        <tbody>
          <?php foreach ($manutencao_interna as $relatorio) { ?>
            <tr>
              <th scope="col"><?= $relatorio['id_man']; ?></th>
                <td><?= $relatorio['placa']; ?></td>
              <td><?= $relatorio['dt_realizada']; ?></td>
              <td><?= $relatorio['dt_finalizada']; ?></td>
              <td><?= $relatorio['descricao']; ?></td>
              <td>R$ <?= $relatorio['custo']; ?></td>
              <?php if($relatorio_titulo == 'Relatório Geral de Obras'){ ?>
                <td><?= $relatorio['armazem']; ?></td>
              <?php } ?>
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
          <tr class="bg-secondary text-light">
            <th class="text-right" colspan="1">Total:</th>
            <th colspan="<?php if($relatorio_titulo == 'Relatório Geral de Obras'){ echo '6'; } else { echo '5'; }?>">R$ <?= number_format($total_externa, 2, '.', ','); ?></th>
          </tr>
          <tr class="thead-light">
            <th scope="col">#</th>
            <th scope="col">Placa</th>
            <th scope="col">Início</th>
            <th scope="col">Término</th>
            <th scope="col">Descrição</th>
            <th scope="col">Valor</th>
            <?php if($relatorio_titulo == 'Relatório Geral de Obras'){ ?>
              <th scope="col">Armazém</th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($manutencao_externa as $relatorio) { ?>
            <tr>
              <th scope="col"><?= $relatorio['id_man']; ?></th>
              <td><?= $relatorio['placa']; ?></td>
              <td><?= $relatorio['dt_realizada']; ?></td>
              <td><?= $relatorio['dt_finalizada']; ?></td>
              <td><?= $relatorio['descricao']; ?></td>
              <td>R$ <?= $relatorio['custo']; ?></td>
              <?php if($relatorio_titulo == 'Relatório Geral de Obras'){ ?>
                <td><?= $relatorio['armazem']; ?></td>
              <?php } ?>
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
