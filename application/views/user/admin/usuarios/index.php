<div id="conteudo">
  <div class="card rounded">
    <h3 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">
      Usuários
    </h3>
    <button type="button" id="btnInativeUser" data-toggle="modal" data-target="#usuariosExcluídosModal" class="btn btn-sm btn-outline-danger">
        Usuários Excluidos &nbsp
        <i data-feather="user-x"></i>
    </button>
    <button type="button" id="btnCadUser" data-toggle="modal" data-target="#cadastroUsuarioModal" class="btn btn-sm btn-outline-success">
        Novo Usuário &nbsp
        <i data-feather="user-plus"></i>
    </button>
    <hr class="my-2 mb-4">
    <div class="col-12">
      <table id="tbl_cadUsuario" class="table table-striped table-border">
          <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Sobrenome</th>
                <th scope="col">E-mail</th>
                <th scope="col">Nível</th>
                <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <?php $count = 0; foreach ($usuarios as $usuario) { $count ++;  ?>
            <tr>
              <td><?= $count; ?></td>
              <td><?= $usuario->nome;?></td>
              <td><?= $usuario->sobrenome;?></td>
              <td><?= $usuario->email;?></td>
              <td>
                <?php if($usuario->nivel == 1){ ?>
                  <h5><span class="badge badge-warning">Administrador</span></h5>
                <?php }else{ ?>
                  <h5><span class="badge badge-primary">Usuário</span></h5>
                <?php } ?>
              </td>
              <td>
                <div class="dropdown">
                  <button class="btn btn-sm btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Ações
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <button type="button" class="dropdown-item" data-toggle="modal" data-target="#verUsuarioModal" data-whatever="<?= $usuario->id_usuario; ?>">Visualizar</button>
                      <button type="button" class="dropdown-item" data-toggle="modal" data-target="#resetUsuarioModal" data-whatever="<?= $usuario->id_usuario; ?>">Reset de Senha</button>
                      <div class="dropdown-divider"></div>
                      <button type="button" class="dropdown-item text-danger" data-toggle="modal" data-target="#excluirUsuarioModal" data-whatever="<?= $usuario->id_usuario; ?>">Excluir</button>
                  </div>
                </div>
              </td>
            </tr>
            <?php } ?>
          </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Usuários Excluídos -->
<div class="modal fade" id="usuariosExcluídosModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Usuários Excluídos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form-removedUsers" method="post">
        <div class="modal-body">
          <table id="tbl_removedUsers" class="table">
            <thead>
              <tr>
                <th scope="col"><input type="checkbox" id="marcartodos" data-toggle="tooltip" data-placement="right" title="Marcar todos"></th>
                <th scope="col">Nome</th>
                <th scope="col">E-mail</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <?php $count=0; foreach ($excluidos as $user) { $count++; ?>
              <tr>
                <th scope="row"><input type="checkbox" name="id_usuario[]" value="<?= $user->id_usuario; ?>"></th>
                <td><?= $user->nome;?></td>
                <td><?= $user->email;?></td>
                <td></td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-outline-success">Restaurar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Ver Cadastro do Usuário -->
<div class="modal fade" id="verUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="verUsuarioModalTitle">Visualizar Usuário</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-editUsuario" method="post">
          <input type="hidden" id="id_usuario" name="id_usuario" value="">
          <div class="card p-3 mb-4">
            <h5 class="text-muted">Dados Pessoais</h5>
            <hr class="my-2 mb-4">

            <div class="form-row">
              <div class="form-group col-4">
                <label>Nome: <span class="text-danger">*</span></label>
                <input type="text" id="edit_nome" name="nome" placeholder="Nome" class="form-control" required disabled="true">
              </div>
              <div class="form-group col-8">
                <label>Sobrenome: <span class="text-danger">*</span></label>
                <input type="text" id="edit_sobrenome" name="sobrenome" placeholder="Sobrenome" class="form-control" required disabled="true">
              </div>
            </div>
          </div>

          <div class="card p-3 mb-4">
            <h5 class="text-muted">Contato</h5>
            <hr class="my-2 mb-4">

            <div class="form-row">
              <div class="form-group col-8">
                <label>E-mail: <span class="text-danger">*</span></label>
                <input type="email" id="edit_email" name="email" placeholder="E-mail" class="form-control" readonly>
              </div>
              <div class="form-group col-4">
                <label>Telefone: <span class="text-danger">*</span></label>
                <input type="text" id="edit_telefone" name="telefone" placeholder="Telefone" class="form-control" required disabled="true">
              </div>
            </div>
          </div>

          <div class="card p-3">
            <h5 class="text-muted">Controle de acesso</h5>
            <hr class="my-2 mb-4">

            <div class="form-row">
              <div class="form-group col-6">
                <label>Nível: <span class="text-danger">*</span></label>
                <select id="edit_nivel" name="nivel" class="custom-select" required disabled="true">
                  <option id="edit_nivelatual" value=""></option>
                  <option value="2">Usuário</option>
                  <option value="1">Administrador</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" id="editar_usuario" class="btn btn-outline-primary">Editar Usuário</button>
          <button type="button" id="cancelar_edicao" class="btn btn-sm btn-outline-dark d-none">Cancelar</button>
          <button type="submit" id="salvar_alteracoes" class="btn btn-outline-success d-none">Salvar Alterações</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Cadastrar Novo Usuário -->
<div class="modal fade" id="cadastroUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Novo Usuário</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-cadUsuario" method="post" class="was-validated">

          <div class="card p-3 mb-4">
            <h5 class="text-muted">Dados Pessoais</h5>
            <hr class="my-2 mb-4">

            <div class="form-row">
              <div class="form-group col-4">
                <label>Nome: <span class="text-danger">*</span></label>
                <input type="text" name="nome" placeholder="Nome" class="form-control is-invalid" required>
              </div>
              <div class="form-group col-8">
                <label>Sobrenome: <span class="text-danger">*</span></label>
                <input type="text" name="sobrenome" placeholder="Sobrenome" class="form-control is-invalid" required>
              </div>
            </div>
          </div>

          <div class="card p-3 mb-4">
            <h5 class="text-muted">Contato</h5>
            <hr class="my-2 mb-4">

            <div class="form-row">
              <div class="form-group col-12">
                <label>E-mail: <span class="text-danger">*</span></label>
                <input type="email" name="email" placeholder="E-mail" class="form-control is-invalid" id="user-email" required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-6">
                <label>Confirme o e-mail: <span class="text-danger">*</span></label>
                <input type="email" name="cEmail" placeholder="Confirme o e-mail" class="form-control is-invalid" id="user-cEmail" required>
              </div>
              <div class="form-group col-6">
                <label>Telefone: <span class="text-danger">*</span></label>
                <input type="text" name="telefone" placeholder="Telefone" class="form-control is-invalid" required>
              </div>
            </div>
          </div>

          <div class="card p-3">
            <h5 class="text-muted">Controle de acesso</h5>
            <hr class="my-2 mb-4">

            <div class="form-row">
              <div class="form-group col-6">
                <label>Nível: <span class="text-danger">*</span></label>
                <select name="nivel" class="custom-select is-invalid" data-toggle="tooltip" data-placement="right" title="Atribua a este usuário um nível de acesso no sistema!" required>
                  <option value="">Selecione uma opção</option>
                  <option value="2">Usuário</option>
                  <option value="1">Administrador</option>
                </select>
              </div>
              <div class="from-group col-6">
                <label>Deseja gerar uma senha? <span class="text-danger">*</span></label>
                <button type="button" class="btn btn-block btn-outline-primary" id="gerarSenha">Gerar senha</button>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-6">
                <label>Senha: <span class="text-danger">*</span></label>
                <input type="password" name="senha" id="user-senha" placeholder="Senha" class="form-control is-invalid" required>
              </div>
              <div class="form-group col-6">
                <label>Confirme a senha: <span class="text-danger">*</span></label>
                <input type="password" name="cSenha" id="user-cSenha" placeholder="Confirme a senha" class="form-control is-invalid" required>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Cadastrar Usuário</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Restaurar Senha do Usuário -->
<div class="modal fade" id="resetUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Restaurar Senha</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <div class="alert alert-danger" role="alert">
              <h4 class="alert-heading">
                <i data-feather="alert-circle"></i>
                Atenção
              </h4>
              <p>Ao <strong>restaurar</strong> uma nova senha será gerada para este usuário. <br>O usuário será notificado pelo sistema sobre essa operação!</p>
              <hr>
              <p class="mb-0">- Equipe IWG Web Software ©</p>
            </div>
          </div>
        </div>
        <form id="form-resetSenha" method="post">
          <div class="form-row">
            <div class="form-group col-12">
              <input type="hidden" id="email" name="email" value="">
              <input type="hidden" id="senha" name="senha" value="">
              Deseja restauar a senha do(a) <label id="resetSenha_user"> <span class="text-danger">*</span></label>?
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-12">
              <label>Digite sua senha <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="resetPassValida" name="senha_atual" placeholder="Senha" required data-toggle="tooltip" data-placement="left" title="Digite sua senha para validação!">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            <i data-feather="x"></i>
            Não
          </button>
          <button type="submit" id="btnResetSenha_user" class="btn btn-success">
            <i data-feather="check"></i>
            Sim
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Excluir Cadastro do Usuário -->
<div class="modal fade" id="excluirUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Excluir Usuário</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <div class="alert alert-danger" role="alert">
              <h4 class="alert-heading">
                <i data-feather="alert-circle"></i>
                Atenção
              </h4>
              <p>Ao <strong>excluir</strong> o usuário ele deixará de ter acesso ao sistema. <br>O usuário será notificado pelo sistema sobre essa operação!</p>
              <hr>
              <p class="mb-0">- Equipe IWG Web Software ©</p>
            </div>
          </div>
        </div>
        <form id="form-excluirUser" method="post">
          <div class="form-row">
            <div class="form-group col-12">
              <input type="hidden" id="id_user_delete" name="id" value="">
              Deseja excluir o(a) <label id="delete_user"> <span class="text-danger">*</span></label>?
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-12">
              <label>Digite sua senha <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="userDeleteValida" placeholder="Senha" required data-toggle="tooltip" data-placement="left" title="Digite sua senha para validação!">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            <i data-feather="x"></i>
            Não
          </button>
          <button type="submit" id="btnDelete_user" class="btn btn-success">
            <i data-feather="check"></i>
            Sim
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
