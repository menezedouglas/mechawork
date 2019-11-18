<div class="login_home">
    <div class="banner">
        <div class="logo_login">
            <img src="<?= $url_base; ?>/img/logo-v2-white.png" id="logo_login" class="img-fluid">
        </div>
        <div id="icon-form">
            <span class="ln-1"></span>
            <span class="ln-2"></span>
            <span class="ln-3"></span>
        </div>
        <div id="powered">
            <p class="text-light">© IWG Web Software. Todos os direitos reservados - 2019.</p>
        </div>
    </div>
    <div class="login_form p-5">
        <img src="<?= $url_base; ?>/img/logo-v2.png" class="img-fluid" id="logo-form">
        <h4 class="text-black-50">Entrar</h4>
        <form id="form-login" method="post">
            <div class="form-row mt-5">
                <div class="form-group input-group-lg col-12">
                    <input type="email" id="email" class="form-control" name="email" placeholder="E-mail" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group input-group-lg col-12">
                    <input type="password" id="senha" placeholder="Senha" name="senha" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-6 mt-5 pt-2">
                    <button type="button" class="btn btn-outline-secondary border-0" name="button" id="btn-ajudaLogin">Ajuda</button>
                </div>
                <div class="form-group col-6 mt-5">
                    <button type="submit" id="btn-login" class="btn btn-outline-success btn-lg btn-block">Entrar &nbsp
                        <i data-feather="log-in"></i></button>
                </div>
            </div>
            <div class="footer">
                <div class="form-group col-12 p-2 text-muted text-center">
                    <h6>V<?= number_format((($nivel_front+$nivel_back+$nivel_database)/3), 0, '.', '')?>.<?= $versao; ?></h6>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Ajuda - Login -->
<div class="modal fade" id="modalAjuda" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Fale com o administrador</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form-ajuda-login" method="post">
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-12">
              <label>Assunto <span class="text-danger">*</span></label>
              <select class="custom-select" name="assunto">
                <option value="">Escolha uma opção</option>
                <option value="Esqueci minha senha">Esqueci minha senha</option>
                <option value="Reclamacao">Reclamação</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-12">
              <label for="email-contato" class="col-form-label">E-mail para contato: <span class="text-danger">*</span></label>
              <input type="text" class="form-control" readonly name="email-contato" id="email-contato">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-12">
              <label for="message-text" class="col-form-label">Mensagem: <span class="text-danger">*</span></label>
              <textarea class="form-control" name="mensagem" id="message-text"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="custom-control custom-switch">
            <input type="checkbox" name="enviar-copia" class="custom-control-input" id="customSwitch1">
            <label class="custom-control-label" for="customSwitch1">Receber uma cópia! <span class="text-danger">*</span></label>
          </div>
          <button type="submit" class="btn btn-outline-success">Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>
