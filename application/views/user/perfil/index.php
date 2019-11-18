<div id="conteudo">
    <div class="card">
      <div class="row">
        <div class="col-4">

          <h3 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">
            Perfil
            <?php if($this->session->userdata('nivel') == 1){ ?>
                <span class="badge badge-primary float-right">Administrador</span>
            <?php } else { ?>
                <span class="badge badge-primary float-right">Usuário</span>
            <?php }?>
          </h3>
          <hr class="my-2 mb-2">

          <?php foreach ($usuario as $user): ?>
            <form id="form-profile" method="post">
              <div class="form-row">
                <div class="form-group col-5">
                  <label>Nome <span class="text-danger">*</span></label>
                  <input type="hidden" name="id_usuario" value="<?= $user->id_usuario; ?>">
                  <input type="hidden" name="nivel" value="<?= $this->session->userdata('nivel'); ?>">
                  <input type="text" class="form-control" name="nome" value="<?= $user->nome;?>" placeholder="Nome" disabled required>
                </div>
                <div class="form-group col-7">
                  <label>Sobrenome <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="sobrenome" value="<?= $user->sobrenome;?>" placeholder="Sobrenome" disabled required>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-12">
                  <label>E-mail <span class="text-danger">*</span></label>
                  <input type="email" id="email_perfil" class="form-control" name="email" value="<?= $user->email;?>" placeholder="E-mail" readonly required>
                </div>
              </div>
              <div id="perfil-senhas">
                <div class="form-row">
                  <div class="form-group col-6">
                    <label>Senha <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="perfil_senha" placeholder="Senha" required>
                  </div>
                  <div class="form-group col-6">
                    <label>Confirme a senha <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="perfil_cSenha" placeholder="Confirme a senha" required>
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-5">
                  <label>Telefone <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="telefone" value="<?= $user->telefone;?>" placeholder="Telefone" disabled required>
                </div>
                <div class="form-group col-6 ml-auto">
                  <div id="btn-perfil-senhas-group">
                    <div>
                      <button type="button" id="btn-cancel-ed-prf" class="btn btn-outline-danger">Cancelar</button>
                      <button type="button" id="btn-editar-perfil" class="btn btn-block btn-outline-primary">Editar</button>
                      <button type="submit" id="btn-salvar-perfil" class="btn btn-block btn-outline-success">Salvar</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          <?php endforeach; ?>
        </div>
        <div class="col-8">
          <h3 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">
            Alterar Senha
          </h3>
          <hr class="my-2 mb-2">
          <div class="row">
            <div class="col-4">
              <form id="form-alterarSenha" method="post">
                <h6 class="text-muted mt-0">Validar</h6>
                <hr class="my-2">
                <div class="form-row">
                  <div class="form-group col-12">
                    <input type="hidden" name="id_usuario" value="<?= $this->session->userdata('id'); ?>">
                    <input type="email" id="editpass_email" name="email" class="form-control" required placeholder="Digite seu E-mail">
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-12">
                    <input type="password" id="editpass_senhaatual" name="senha_atual" class="form-control" required placeholder="Digite sua senha atual">
                  </div>
                </div>
                <h6 class="text-muted mt-1">Nova Senha</h6>
                <hr class="my-2">
                <div class="form-row">
                  <div class="form-group col-12">
                    <input type="password" id="editpass_novasenha" name="nova_senha" class="form-control" required placeholder="Digite sua nova senha">
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-12">
                    <input type="password" id="editpass_cnovasenha" name="cNova_senha" class="form-control" requireds placeholder="Digite sua nova senha novamente">
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-12">
                    <button type="submit" class="btn btn-block btn-outline-success">Alterar</button>
                  </div>
                </div>
              </form>
            </div>
            <div class="col-8">
              <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">
                  <i data-feather="alert-circle"></i>
                  Atenção
                </h4>
                <p>Antes de alterar sua senha, lembre-se:</p>
                <p>- Será enviado uma mensagem ao e-mail do seu perfil!</p>
                <p>- Você será desconectado após a conclusão da operação!</p>
                <p>- Não é permitido usar senhas antigas!</p>
                <p>- Não utilize senhas comuns!</p>
                <hr>
                <p class="mb-0">- Equipe IWG Web Software ©</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
