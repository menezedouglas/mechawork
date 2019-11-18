<div id="bg-notify">

  <div class="legenda">

    <div class="col-12">

      <h4>Legenda</h4>

      <div class="form-row list-group-item  active">

        <span></span>

        <label>Notificação Recente</label>

      </div>

      <div class="form-row">

        <span></span>

        <label>Notificação Vista</label>

      </div>

      <div class="form-row bg-warning">

        <span></span>

        <label>Alerta Antecipado</label>

      </div>

    </div>

  </div>

</div>

<div id="btn-notify">

  <i class="btn-notify-icon" data-feather="chevrons-right"></i>
  <h5><span class="bagde badge-pill badge-warning"> 1 </span></h5>

</div>

<div id="notify">

  <div class="notify-header">

    <h5 class="text-light"><i data-feather="alert-triangle"></i> Notificações</h5>

  </div>

  <div class="notify-body">

    <div class="list-group">

      <img src="<?= $url_base; ?>/img/notificacao.png" id="notify_logo" class="img-fluid">

      <center class="subtitle">

        Suas notificações serão exibidas aqui!

      </center>


      <?php if(count($notificacoes)!=0){ ?>

        <div class="notify-contents">

          <?php
            foreach ($notificacoes as $notificacao) {
              if($notificacao['km'] != 0) {
          ?>

          <a href="#" data-toggle="modal" data-target="#modalConfirmNotify" data-id="<?= $notificacao['id_notificacao']; ?>" data-placa="<?= $notificacao['placa']; ?>" data-dt="<?= $notificacao['dt_man']; ?>"class="list-group-item list-group-item-action bg-warning <?php if($notificacao['situacao'] == 'notificado') { echo 'active'; } ?>">

            <input type="hidden" id="id_notificacao" value="<?= $notificacao['id_notificacao']; ?>">

            <div class="d-flex w-100 justify-content-between">

              <h5 class="mb-1">Alerta Antecipado</h5>

              <small>

                <?= $notificacao['tempo']; ?>

              </small>

            </div>

            <p class="mb-1">O Veículo/Equipamento <?= $notificacao['placa']; ?> tem um alerta antecipado para o dia <?= Date('d/m/Y', strtotime($notificacao['dt_man'])); ?> com a quilometragem em <?= number_format($notificacao['km'], 2, ',', ' '); ?>!</p>

            <small>Clique para confirmar.</small>

          </a>

          <?php } else { ?>

          <a href="#" data-toggle="modal" data-target="#modalConfirmNotify" data-id="<?= $notificacao['id_notificacao']; ?>" data-placa="<?= $notificacao['placa']; ?>" data-dt="<?= $notificacao['dt_man']; ?>" class="list-group-item list-group-item-action <?php if($notificacao['situacao'] == 'notificado') { echo 'active'; } ?>">

            <input type="hidden" id="id_notificacao" value="<?= $notificacao['id_notificacao']; ?>">

            <div class="d-flex w-100 justify-content-between">

              <h5 class="mb-1">Manutenção Agendada</h5>

              <small>

                <?= $notificacao['tempo']; ?>

              </small>

            </div>

            <p class="mb-1">O Veículo/Equipamento <?= $notificacao['placa']; ?> tem uma manutenção agendada para o dia <?= Date('d/m/Y', strtotime($notificacao['dt_man'])); ?>!</p>

            <small>Clique para confirmar.</small>

          </a>

          <?php } } ?>

        </div>
      <?php } ?>

    </div>
  </div>
</div>



<div style="position: absolute; bottom: 10px; right: 10px; width:auto; z-index:1000;">

  <!-- Then put toasts within -->
  <div id="alerta_notify" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="true" data-delay="5000">
    <div class="toast-header">
      <i data-feather="alert-triangle" class="rounded mr-2"></i>
      <strong class="mr-auto titulo"></strong>
      <small class="text-muted tempo">just now</small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">
      See? Just like this.
    </div>
  </div>

</div>

<!-- Modal -->
<div class="modal fade" id="modalConfirmNotify" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirmar Realização da Manutenção</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="card shadow mb-3">
          <div class="card-body">
            <h5 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">
                Dados do Veículo/Equipamento
            </h5>
            <hr class="my-2 mb-4">
            <div class="form-row">
              <div class="form-group col-2">
                Placa:
                <label id="nt_placa_label"> <span class="text-danger">*</span></label>
              </div>
              <div class="form-group col-2">
                Descricao:
                <label id="nt_descricao_label"> <span class="text-danger">*</span></label>
              </div>
            </div>
          </div>
        </div>

        <form id="form-confirmNotify" method="post">
          <div class="card shadow mb-3">
            <div class="card-body">
              <h5 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">
                Manutenções Cadastradas
              </h5>
              <hr class="my-2 mb-4">
              <table id="tbl_nt_config_man" class="table">
                <thead>
                  <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Início</th>
                    <th scope="col">Término</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Nº da Nota</th>
                    <th scope="col">Custo</th>
                    <th scope="col">Usuário</th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Registrar Manutenção</button>
        </div>
      </form>
    </div>
  </div>
</div>
