<nav class="navbar navbar-expand-lg navbar-dark" id="bartop">

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="<?= $url_dashboard;?>"><i data-feather="home"></i> Início</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $url_atualizacoes;?>/manutencao"><i data-feather="edit-3"></i> Cadastros</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i data-feather="search"></i> Consultas
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <h6 class="dropdown-header"></h6>
                    <a class="dropdown-item" href="<?= $url_estoque;?>"><i data-feather="box"></i>&nbsp
                        Estoque
                    </a>
                    <a class="dropdown-item" href="<?= $url_frota;?>"><i data-feather="truck"></i>&nbsp
                      Frota
                    </a>
                    <a class="dropdown-item" href="<?= $url_fornecedores;?>"><i data-feather="shopping-cart"></i>&nbsp
                        Fornecedores
                    </a>
                    <a class="dropdown-item" href="<?= $url_produtos;?>"><i data-feather="shopping-bag"></i>&nbsp
                        Produtos
                    </a>
                    <a class="dropdown-item" href="<?= $url_notas;?>"><i data-feather="file-text"></i>&nbsp
                        Notas & Recibos
                    </a>
                    <div class="dropdown-divider"></div>
                    <h6 class="dropdown-header">
                      Movimentações
                    </h6>
                    <a class="dropdown-item dropdown-item-disabled" href="<?= $url_entradas;?>">
                      <i data-feather="download"></i>&nbsp
                        Entradas
                    </a>
                    <a class="dropdown-item dropdown-item-disabled" href="<?= $url_saidas;?>">
                      <i data-feather="upload"></i>&nbsp
                        Saídas
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?= $url_manutencoes;?>">
                        <i data-feather="settings"></i>&nbsp
                        Manutenções
                    </a>
                    <!-- <a class="dropdown-item" href="<?= $url_preventivas;?>">
                        <i data-feather="clock"></i>&nbsp
                        Agendadas
                    </a> -->
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#relatoriosModal"><i data-feather="printer"></i> Gerar Relatórios</a>
            </li>
        </ul>
    </div>

    <div id="menutop-logo">
        <a class="navbar-brand" href="<?= $url_base; ?>">
            <img src="<?= $url_base; ?>/img/logo-v2-white.png" class="img-fluid">
        </a>
    </div>

    <?php if($this->session->userdata('nivel') == 1){ ?>
    <h5 id="badge-admin">
        <span class="badge badge-pill badge-primary">Admin</span>
    </h5>
    <?php } ?>

    <div id="versao-menu">
        <h6 class="text-light"><span class="badge badge-info">V<?= number_format((($nivel_front+$nivel_back+$nivel_database)/3), 0, '.', '')?>.<?=$versao; ?></span></h6>
    </div>

</nav>

<!-- Icone do menu de usuÃ¡rio -->
<div id="user-icon">
    <i data-feather="menu"></i>
</div>

<!-- Menu do UsuÃ¡rio / Acionado quando clicar no icone do usuÃ¡rio -->
<div id="user-menu">

    <img src="<?= $url_base; ?>/img/logo-v2-white.png" id="logo-usermenu">

    <div class="boasvindas">
        <div class="nome">
          <?= $this->session->userdata('nome'); ?>
          <br>
          <h6>
            <?= $this->session->userdata('sobrenome'); ?>
          </h6>
        </div>
        <div class="acoes">
          <div class="nivel">
            <?php if($this->session->userdata('nivel') == 1){ ?>
              <h5>
                <span class="badge badge-primary">Administrador</span>
              </h5>
            <?php } else { ?>
              <span class="badge badge-primary">Usuário</span>
            <?php } ?>
          </div>
        </div>
    </div>

    <ul>

        <!-- Link - PERFIL DO USUÃ�RIO -->
        <a href="<?= $url_perfil; ?>">
            <li>
                <i data-feather="user"></i>
                <p>Meu Perfil</p>
            </li>
        </a>

        <?php if($this->session->userdata('nivel') == 1){ ?>

        <a href="<?= $url_users;?>">
            <li id="item-usuarios">
                <i data-feather="users"></i>
                <p>Usuários</p>
            </li>
        </a>

        <!--Link - CONFIGURAcõES DO SISTEMA -->
        <a href="<?= $url_configuracoes; ?>">
            <li>
                <i data-feather="settings"></i>
                <p>Configurações</p>
            </li>
        </a>
        <?php } ?>


        <!-- Link - Sair do Sistema -->
        <div id="sair">
            <a class="text-danger">
                <li>
                    <i class="mr-4" data-feather="power"></i>
                    <p>Sair</p>
                </li>
            </a>
        </div>
    </ul>
</div>


<!-- Modal Relatórios -->
<div class="modal fade" id="relatoriosModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Gerar Relatório</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="gerarRelatorio" method="post" action="<?= $url_base; ?>/sistema/relatorio">

          <div class="form-row">
            <div class="form-group col-12">
              <label>Tipo de Relatório<span class="text-danger">*</span></label>
              <select class="custom-select" name="tipo_relatorio" required>
                <option selected value="0">Selecione</option>
                <option value="1">Manutencão</option>
                <option value="2">Produto</option>
                <option value="3">Obra</option>
              </select>
            </div>
          </div>

          <div id="gerarRelatorio_tipoFrota" class="form-row d-none">
            <div class="form-group col-12">
              <label>Tipo de Frota<span class="text-danger">*</span></label>
              <select class="custom-select" name="tipo_frota">
                <option value="TODOS">TODOS</option>
                <option value="MÁQUINAS DE CARGA E ELEVAÇÃO">MÁQUINAS DE CARGA E ELEVAÇÃO</option>
                <option value="VEÍCULOS DE CARGA E ELEVAÇÃO">VEÍCULOS DE CARGA E ELEVAÇÃO</option>
                <option value="VEÍCULOS DE TRANSPORTES DE PASSAGEIROS">VEÍCULOS DE TRANSPORTES DE PASSAGEIROS</option>
                <option value="VEÍCULOS DE PASSEIO">VEÍCULOS DE PASSEIO</option>
                <option value="VEÍCULOS UTILITÁRIOS">VEÍCULOS UTILITÁRIOS</option>
                <option value="GERADORES">GERADORES</option>
              </select>
            </div>
          </div>

          <div id="gerarRelatorio_filtrarSim" class="form-row d-none">
            <div class="form-group col-12">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" name="filtrar_sim" id="filtrarRelatorio" data-toggle="toggle">
                <label class="custom-control-label" for="filtrarRelatorio">Filtrar <span class="text-danger">*</span></label>
              </div>
            </div>
          </div>

          <div id="gerarRelatorio_parametro" class="form-row d-none">
            <div class="form-group col-12">
              <input type="text" name="parametro" id="relatorio_parametro" class="form-control" placeholder="" disabled="true">
            </div>
          </div>

          <div id="gerarRelatorio_periodoSim" class="form-row d-none">
            <div class="form-group col-12">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" name="periodo_sim" id="periodoRelatorio">
                <label class="custom-control-label" for="periodoRelatorio">Período <span class="text-danger">*</span></label>
              </div>
            </div>
          </div>

          <div id="gerarRelatorio_periodo" class="form-row d-none">
            <div class="form-group col-6">
              <label>De <span class="text-danger">*</span></label>
              <input type="date" name="periodo-inicio" class="form-control">
            </div>
            <div class="form-group col-6">
              <label>Até <span class="text-danger">*</span></label>
              <input type="date" name="periodo-fim" class="form-control">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-12">
              <button type="submit" class="btn btn-block btn-outline-primary" disabled="true">Selecione um tipo</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
