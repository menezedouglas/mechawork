<div id="conteudo">

  <div class="card">

    <h3 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">Formulários de Cadastro</h3>

    <hr class="my-2">

    <div class="row">

      <div class="col-2">

	        <h6 style="border-left: 2px solid #555;padding-left: 5px;margin-top:30px;">ESCOLHA UMA OPÇÃO</h6>

	        <hr class="my-2 mb-4">

	        <div class="list-group" id="list-tab" role="tablist">

	          <a class="list-group-item list-group-item-action <?php if($formulario == "manutencao") { echo 'active'; } ?>" id="cadastro-man-corretivas" href="<?= $url_base; ?>/sistema/cadastros/manutencao" role="tab" aria-controls="ManutencoesCorretivas">

	            Manutenção

	          </a>

	          <a class="list-group-item list-group-item-action <?php if($formulario == "entrada-estoque") { echo 'active'; } ?>" id="entrada-estoque" href="<?= $url_base; ?>/sistema/cadastros/entrada-estoque" role="tab" aria-controls="entradaEstoque">

	            Entrada no Estoque

	          </a>

	          <a class="list-group-item list-group-item-action <?php if($formulario == "agendamento") { echo 'active'; } ?>" id="cadastro-man-preventivas" href="<?= $url_base; ?>/sistema/cadastros/agendamento" role="tab" aria-controls="profile">

	            Agendamento

	          </a>

	          <a class="list-group-item list-group-item-action <?php if($formulario == "veiculo-equipamento") { echo 'active'; } ?>" id="cadastro-frota" href="<?= $url_base; ?>/sistema/cadastros/veiculo-equipamento" role="tab" aria-controls="cadastroFrota">

	            Veículo/Equipamento

	          </a>

	          <a class="list-group-item list-group-item-action <?php if($formulario == "produto") { echo 'active'; } ?>" id="cadastro-produtos" href="<?= $url_base; ?>/sistema/cadastros/produto" role="tab" aria-controls="produtos">

	            Produtos

	          </a>

	          <a class="list-group-item list-group-item-action <?php if($formulario == "obra") { echo 'active'; } ?>" id="cadastro-obra" href="<?= $url_base; ?>/sistema/cadastros/obra" role="tab" aria-controls="obra">

	            Obra

	          </a>

	        </div>

	  </div>

      <div class="col-10">

        <div class="tab-content h-100" id="nav-tabContent">


          <!-- FORMULÁRIO PARA O CADASTRO DE MANUTENÇÕES CORRETIVAS -->

          <div class="tab-pane fade <?php if($formulario == "manutencao") { echo 'show active'; } ?>" id="list-cadastro-man-corretivas" role="tabpanel" aria-labelledby="cadastro-man-corretivas">

            <div class="row">

			        <!-- Ações do formulário -->

              <div class="col-2">

                <h6 style="border-left: 2px solid #555;padding-left: 5px;margin-top:30px;">
              		TIPO <span class="text-danger">*</span>
                </h6>

                <hr class="my-2 mb-4">

                <div class="form-row">

                  <div class="form-group col-12">

                    <select class="custom-select" name="tipo" id="tipoManutencao" required>

                      <option selected value="1">Man. Interna</option>

                      <option value="2">Man. Externa</option>

                    </select>

                  </div>

                </div>

                <h6 style="border-left: 2px solid #555;padding-left: 5px;margin-top:30px;">
              		PLACA/Nº <span class="text-danger">*</span>
                </h6>

                <hr class="my-2 mb-4">

                <div class="form-row">

                  <div class="form-group col-12">

                    <div class="input-group mb-3">

                      <input type="text" id="placaManutencao" class="form-control" minlength="7" placeholder="Placa/Nº" value="" aria-describedby="btn-searchfrota" required>

                      <div class="input-group-append">

                        <button class="btn btn-outline-primary" type="button" id="btn-searchfrota" data-toggle="modal" data-target="#consultaFrotaModal">

                          <i style="width: 20px; height: 16px;" data-feather="search"></i>

                        </button>

                      </div>

                    </div>

                  </div>

                </div>

              	<h6 style="border-left: 2px solid #555;padding-left: 5px;margin-top:20px;">
              		AÇÕES
                </h6>

                <hr class="my-2 mb-4">

                <button type="button" id="form-cadMan-add-produto" class="btn btn-block btn-sm btn-outline-primary" data-toggle="modal" data-target="#addProduto">

                	<span class="float-left">Adicionar Produto</span>

                	<i data-feather="plus" class="float-right"></i>&nbsp

                </button>

                <button type="button" id="form-cadMan-remove-produto" class="btn btn-block btn-sm btn-outline-danger" data-toggle="modal" data-target="#rmvProduto">

                	<span class="float-left">Excluir Produto(s)</span>

                	<i data-feather="trash-2" class="float-right"></i>&nbsp

                </button>

              </div>

			        <!-- Formulário -->

              <div class="col-10">

                <form id="form-cadMan" method="POST">

                  <input type="hidden" name="tipo" value="1">

                  <input type="hidden" name="placa" value="">

                  <input type="hidden" id="cartCount" value="">

                  <div id="form-cadMan_Interna">

                    <h6 style="border-left: 2px solid #555;padding-left: 5px;margin-top:30px;">

                      CADASTRAR MANUTENÇÃO INTERNA

                    </h6>

                    <hr class="my-2 mb-4">

                    <h6 class="text-muted mb-2">Dados da Manutenção</h6>

                    <div class="form-row">

                      <div class="form-group col-3">

                        <label>Custo da Mão de Obra <span class="text-danger">*</span></label>

                        <div class="input-group">

                          <div class="input-group-prepend">

                            <div class="input-group-text">R$</div>

                          </div>

                          <input type="number" name="mao-obra" class="form-control" step="0.01" placeholder="0.000,00" required>

                        </div>

                      </div>

                      <div class="form-group col-3">

                        <label>Local <span class="text-danger">*</span></label>

                        <select class="custom-select" name="obra" required>

                          <option value="">Selecione...</option>

                          <?php foreach ($obras as $obra) { ?>

                            <option value="<?= $obra->id_obra;?>"><?= $obra->descricao;?></option>

                          <?php } ?>

                        </select>

                      </div>

                      <div class="form-group col-3">

                        <label>Início  <span class="text-danger">*</span></label>

                        <input type="datetime-local" class="form-control " name="data-inicio" required>

                      </div>

                      <div class="form-group col-3">

                        <label>Término  <span class="text-danger">*</span></label>

                        <input type="datetime-local" name="data-fim" class="form-control" required>

                      </div>

                    </div>

                    <div class="form-row">

                      <div class="form-group col-12">

                        <label>Descrição  <span class="text-danger">*</span></label>

                        <textarea name="descricao" class="form-control" minlength="4" maxlength="255" required></textarea>

                      </div>

                    </div>

                    <br>

                    <div class="row">

                      <div class="col-12">

                        <h6 style="border-left: 2px solid #555;padding-left: 5px;">

                          PRODUTOS USADOS

                        </h6>

                        <hr class="my-2 mb-4">

                        <table id="tbl_prod_usados_cadman" class="table table-striped table-bordere">

                          <thead class="thead-light">

                            <tr>

                              <th scope="col">Código</th>

                              <th scope="col">Descrição</th>

                              <th scope="col">Unid.</th>

                              <th scope="col">Qntd.</th>

                              <th scope="col">Valor Unitário</th>

                              <th scope="col">Valor Total</th>

                            </tr>

                          </thead>

                          <tbody style="overflow-y: auto; overflow-x: hidden;">

                            <tr class="bg-light text-dark text-center">

                              <td colspan="6">Nenhum produto no formulário</td>

                            </tr>

                            <tr class="bg-light text-dark">

                              <td colspan="5"><strong class="float-right">Total:</strong></td>

                              <td>R$ <?php echo number_format(0, 2, ',', '.'); ?></td>

                            </tr>

                          </tbody>

                        </table>

                      </div>

                    </div>


                    <div class="form-row">

                      <div class="form-group col-12">

                        <button type="submit" id="btn-cadManI" class="btn mt-5 btn-outline-success float-right" disabled="true">

                          Aguardando Validação...

                        </button>

                      </div>

                    </div>

                  </div>

                  <div id="form-cadMan_Externa">

                    <h6 style="border-left: 2px solid #555;padding-left: 5px;margin-top:30px;">

                      CADASTRAR MANUTENÇÃO EXTERNA

                    </h6>

                    <hr class="my-2 mb-4">

                    <h6 class="text-muted mt-4 mb-2">Dados da Manutenção</h6>

                    <hr class="my-0 mb-3">

                    <div class="form-row">

                      <div class="form-group col-4">

                        <label>Início <span class="text-danger">*</span></label>

                        <input type="datetime-local" class="form-control" name="data-inicio" required disabled="true">

                      </div>

                      <div class="form-group col-4">

                        <label>Término <span class="text-danger">*</span></label>

                        <input type="datetime-local" name="data-fim" class="form-control" required disabled="true">

                      </div>

                      <div class="form-group col-4">

                        <label>Local <span class="text-danger">*</span></label>

                        <select class="custom-select" name="obra" required disabled="true">

                          <option value="">Selecione...</option>

                          <?php foreach ($obras as $obra) { ?>

                            <option value="<?= $obra->id_obra;?>"><?= $obra->descricao;?></option>

                          <?php } ?>

                        </select>

                      </div>

                    </div>

                    <div class="form-row">

                      <div class="form-group col-12">

                        <label>Descrição <span class="text-danger">*</span></label>

                        <textarea name="descricao" class="form-control" minlength="4" maxlength="255" required disabled="true"></textarea>

                      </div>

                    </div>

                    <h6 class="text-muted mt-4 mb-2">Dados do Fornecedor do Serviço</h6>
                    <hr class="my-0 mb-3">

                    <div class="form-row">

                      <div class="form-group col-3">

                        <label>Fornecedor <span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="fornecedor" minlength="14" maxlength="14" placeholder="CNPJ" required disabled="true" disabled="true">

                      </div>

                      <div class="form-group col-6">

                        <label>Razão Social <span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="razao-social" minlength="4" placeholder="Razão Social" required disabled="true" disabled="true">

                      </div>

                      <div class="form-grop col-3">

                        <label>Telefone (Somente números) <span class="text-danger">*</span></label>

                        <input type="number" step="1" class="form-control" name="telefone" minlength="10" placeholder="1101234567" required disabled="true">

                      </div>

                    </div>

                    <h6 class="text-muted mt-4 mb-2">Dados da Nota de Serviço</h6>
                    <hr class="my-0 mb-3">

                    <div class="form-row">

                      <div class="form-group col-3">

                        <label>Nº da Nota: <span class="text-danger">*</span></label>

                        <input type="text" name="numero-nota" class="form-control" placeholder="Número da nota" required disabled="true" disabled="true">

                      </div>

                      <div class="form-group col-3">

                        <label>Valor da Nota <span class="text-danger">*</span></label>

                        <div class="input-group">

                          <div class="input-group-prepend">

                            <div class="input-group-text">R$</div>

                          </div>

                          <input type="number" name="valor-nota" class="form-control " step="0.01" placeholder="0.000,00" required disabled="true" disabled="true">

                        </div>

                      </div>


                      <div class="form-grop col-3">

                        <label>Data Emissão <span class="text-danger">*</span></label>

                        <input type="date" class="form-control" name="data-nota" required disabled="true" disabled="true">

                      </div>

                      <div class="form-grop col-3">

                        <label>Data Vencimento <span class="text-danger">*</span></label>

                        <input type="date" class="form-control" name="data-venc" required disabled="true" disabled="true">

                      </div>

                    </div>

                    <div class="form-row">

                      <div class="form-group col-12">

                        <label class="text-muted" style="visibility: hidden;">ESPAÇAMENTO</label>

                        <button type="submit" id="btn-cadManE" class="btn btn-outline-success float-right" disabled="true">

                          Aguardando Validação...

                        </button>

                        </div>

                    </div>

                  </div>

                </form>

              </div>

            </div>

          </div>


          <!-- FORMULÁRIO PARA O CADASTRO DE ENTRADA NO ESTOQUE -->

          <div class="tab-pane fade <?php if($formulario == "entrada-estoque") { echo 'show active'; } ?>" id="list-entradaEstoque" role="tabpanel" aria-labelledby="entrada-estoque">

            <div class="row">

              <div class="col-2">

                <div class="row">

                  <div class="col-12">

                    <h6 style="border-left: 2px solid #555;padding-left: 5px;margin-top:30px;">

                      AÇÕES

                    </h6>

                    <hr class="my-2 mb-4">

                    <button type="button" class="btn btn-block btn-sm btn-outline-primary" data-toggle="modal" data-target="#addProdutoEntrada">

                      <span class="float-left">Adicionar Produto</span>

                      <i data-feather="plus" class="float-right"></i>&nbsp

                    </button>

                    <button type="button" id="rmv-produto-estq" class="btn btn-block btn-sm btn-outline-danger" data-toggle="modal" data-target="#rmvProduto">

                      <span class="float-left">Excluír Produto(s)</span>

                      <i data-feather="trash-2" class="float-right"></i>&nbsp

                    </button>

                    <div class="alert alert-info mt-4 pt-3 pb-2" role="alert">

                      <h5 class="alert-heading"><i data-feather="info"></i> <strong>Informação</strong></h5>

                      <p>Veja o <a href="<?= $url_base; ?>/estoque" class="alert-link">Estoque</a>!</p>

                    </div>

                  </div>

                </div>

              </div>

              <div class="col-10">

                <form id="form-entradaEstoque" method="post">

                  <h6 style="border-left: 2px solid #074787;padding-left: 5px;margin-top:30px;">

                    ENTRADA NO ESTOQUE

                  </h6>

                  <hr class="my-2 mb-2">

                  <h6 class="text-muted mb-2">Dados do Fornecedor</h6>

                  <div class="form-row">

                  </div>

                  <div class="form-row">

                    <div class="form-group col-3">

                      <label>Fornecedor <span class="text-danger">*</span></label>

                      <input type="text" id="cnpj_fornecedor" name="cnpj" class="form-control" placeholder="CNPJ" minlength="14" maxlength="14" required>

                    </div>

                    <div class="form-group col-6">

                      <label>Razão Social <span class="text-danger">*</span></label>

                      <input type="text" id="razsoc_fornecedor" minlength="4" class="form-control " name="razao-social" placeholder="Razão Social" required>

                    </div>

                    <div class="form-group col-3">

                        <label>Telefone (Somente números) <span class="text-danger">*</span></label>

                        <input type="text" id="telfon_fornecedor" class="form-control" minlength="10" name="telefone" placeholder="Telefone" required>

                    </div>

                  </div>

                  <h6 class="text-muted mb-2">Dados da Nota Fiscal/Recibo</h6>

                  <div class="form-row">

                    <div class="form-group col-2">

                      <label>Valor <span class="text-danger">*</span></label>

                      <div class="input-group">

                        <div class="input-group-prepend">

                          <span class="input-group-text" id="v_nf">R$</span>

                        </div>

                        <input type="number" step="0.01" class="form-control " name="v_nf" placeholder="0.000,00" aria-describedby="v_nf" required>

                      </div>

                    </div>

                    <div class="form-group col-2">

                      <label>Número <span class="text-danger">*</span></label>

                      <input type="number" class="form-control" name="n_nf" placeholder="Número" required>

                    </div>

                    <div class="form-group col-3">

                      <label>Data de Emissão <span class="text-danger">*</span></label>

                      <input type="date" name="dt_emissao" class="form-control" required>

                    </div>

                    <div class="form-group col-3">

                      <label>Data de Vencimento <span class="text-danger">*</span></label>

                      <input type="date" name="dt_vencimento" class="form-control" required>

                    </div>

                    <div class="form-group col-2">

                        <label class="text-muted">Tudo certo?</label>

                      <button type="submit" class="btn btn-block btn-outline-success" name="button">Efetuar Entrada</button>

                    </div>

                  </div>

                </form>

              </div>

            </div>

            <br>

            <div class="row">

              <div class="col-12">

                <h6 style="border-left: 2px solid #555;padding-left: 5px;">

                  PRODUTOS À ADICIONAR AO ESTOQUE

                </h6>

                <hr class="my-2 mb-4">

                <table id="tbl_prod_entrada_estoque" class="table table-striped table-bordere">

                  <thead class="thead-light">

                    <tr>

                      <th scope="col">Código</th>

                      <th scope="col">Descrição</th>

                      <th scope="col">Unid.</th>

                      <th scope="col">Qntd.</th>

                      <th scope="col">Valor Unitário</th>

                      <th scope="col">Valor Total</th>

                    </tr>

                  </thead>

                  <tbody style="overflow-y: auto; overflow-x: hidden;">

                    <tr class="bg-light text-dark text-center">

                      <td colspan="6">Nenhum produto no formulário</td>

                    </tr>

                    <tr class="bg-light text-dark">

                      <td colspan="5"><strong class="float-right">Total:</strong></td>

                      <td>R$ <?php echo number_format(0, 2, ',', '.'); ?></td>

                    </tr>

                  </tbody>

                </table>



              </div>

            </div>

          </div>


          <!-- FORMULÁRIO PARA O CADASTRO DE MANUTENÇÕES AGENDADAS -->

          <div class="tab-pane fade <?php if($formulario == "agendamento") { echo 'show active'; } ?>" id="list-cadastro-man-preventivas" role="tabpanel" aria-labelledby="cadastro-man-preventivas">

            <h6 style="border-left: 2px solid #555;padding-left: 5px;margin-top:30px;">

              AGENDAR MANUTENÇÃO

            </h6>

            <hr class="my-2 mb-4">

            <form id="form-prev" class="" method="post">

              <div class="form-row">

                <div class="form-group col-3">

                  <label>Placa/Nº <span class="text-danger">*</span></label>

                  <div class="input-group mb-3">

                    <input type="text" id="form_prev_placa" name="placa" class="form-control" minlength="7" maxlength="14" placeholder="Placa/Nº" aria-describedby="btn-searchfrota" required>

                    <div class="input-group-append">

                      <button class="btn btn-outline-primary" type="button" id="btn-searchfrota2" data-toggle="modal" data-target="#consultaFrotaModal">

                        <i style="width: 20px; height: 16px;" data-feather="search"></i>

                      </button>

                    </div>

                  </div>

                </div>

                <div class="form-group col-3">

                  <label>Data Prevista <span class="text-danger">*</span></label>

                  <input type="date" min="<?php echo date('Y-m-d'); ?>" class="form-control" name="data-prevista" required>

                </div>

                <div class="form-group col-6">

                  <label>Descrição <span class="text-danger">*</span></label>

                  <input type="text" class="form-control" minlength="4" name="descricao" placeholder="Descrição" required>

                </div>

              </div>

              <div id="alerta-antecipado">

                <div class="form-row">

                  <div class="form-group col-6">

                    <label>Data Mínima <span class="text-danger">*</span></label>

                    <input type="date" min="<?php echo date('Y-m-d'); ?>" class="form-control" name="data-minima" data-toggle="tooltip" data-placement="right" title="Destinada a gerar um alerta sobre a quilometragem.">

                  </div>

                  <div class="form-group col-6">

                    <label>Quilometragem <span class="text-danger">*</span></label>

                    <input type="text" class="form-control" name="quilometragem" placeholder="Quilometragem" data-toggle="tooltip" data-placement="left" title="Valor a ser notificado ao chegar a data mínima.">

                  </div>

                </div>

              </div>

              <div class="form-row">

                <div class="form-group col-3">

                  <button type="button" id="btn-alerta-antecipado" class="btn btn-sm btn-block btn-outline-primary">Configurar Alerta Antecipado</button>

                </div>

                <div class="col-5"></div>

                <div class="form-group col-4">

                  <button type="submit" id="btn_formPrev" class="btn btn-block btn-outline-success" disabled="true">Aguardando Validação...</button>

                </div>

              </div>

            </form>

            <div class="row">

              <div class="col-12">

                <h6 style="border-left: 2px solid #555;padding-left: 5px;margin-top: 20px;">

                  AGENDAMENTOS CADASTRADOS

                </h6>

                <hr class="my-2 mb-4">

                <table id="tbl_consultaAgendamentos" class="table">

                  <thead class="thead-light">

                    <tr>

                      <th scope="col">Placa/Nº</th>

                      <th scope="col">Data Prevista</th>

                      <th scope="col">Descrição</th>

                      <th scope="col">Usuário</th>

                      <th scope="col">Data Mínima</th>

                      <th scope="col">Quilometragem</th>

                      <th scope="col"></th>

                    </tr>

                  </thead>

                  <tbody>

                    <?php foreach ($futuras as $futura) { ?>

                    <tr>

                      <th scope="row"><?= $futura->placa_numero; ?></th>

                      <td><?= date("d/m/Y", strtotime($futura->data_prevista)); ?></td>

                      <td><?= $futura->descricao; ?></td>

                      <td><?= $futura->email; ?></td>

                      <td><?php if($futura->data_minima != 0){ echo date("d/m/Y", strtotime($futura->data_minima)); } else { echo '<h5><span class="badge badge-warning">Não Configurado</span></h5>'; } ?></td>

                      <td><?php if($futura->quilometragem != 0){ echo number_format($futura->quilometragem, 0, '','.'); } else { echo '<h5><span class="badge badge-warning">Não Configurado</span></h5>'; } ?></td>

                      <td>

                        <div class="dropdown">

                          <button class="btn btn-sm btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            Ações

                          </button>

                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#editarAgendamentoModal" data-whatever="<?= $futura->id_futura; ?>">Editar</button>

                            <?php if($this->session->userdata('nivel') == 1){ ?>

                              <div class="dropdown-divider"></div>

                              <button type="button" class="dropdown-item text-danger" data-toggle="modal" data-target="#excluirAgendamentoModal" data-whatever="<?= $futura->id_futura; ?>">Excluir</button>

                            <?php }?>

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


          <!-- FORMULÁRIO PARA O CADASTRO DE VEÍCULO/EQUIPAMENTO -->

          <div class="tab-pane fade <?php if($formulario == "veiculo-equipamento") { echo 'show active'; } ?>" id="list-cadastro-frota" role="tabpanel"

            aria-labelledby="cadastro-frota">

            <h6 style="border-left: 2px solid #555;padding-left: 5px;margin-top:30px;">

              CADASTRO DE VEÍCULO/EQUIPAMENTO

            </h6>

            <hr class="my-2 mb-4">

            <form id="form-frota" class="" method="post">

              <div class="form-row">

                <div class="form-group col-4">

                  <label>Placa/Nº <span class="text-danger">*</span></label>

                  <input type="text" name="placa" minlength="7" class="form-control" placeholder="ABC1234" required>

                </div>

                <div class="form-group col-8">

                  <label>Descrição <span class="text-danger">*</span></label>

                  <input type="text" name="descricao" minlength="4" class="form-control" placeholder="Nome do Veículo/Equipamento" required>

                </div>

              </div>

              <div class="form-row">

                <div class="form-group col-3">

                  <label>Fabricante <span class="text-danger">*</span></label>

                  <input type="text" name="fabricante" minlength="4" class="form-control "placeholder="Razão Social do Fabricante" required>

                </div>

                <div class="form-group col-2">

                  <label>Ano <span class="text-danger">*</span></label>

                  <input type="text" name="ano" minlength="4" maxlength="4" class="form-control " placeholder="2015" required>

                </div>

                <div class="form-group col-4">

                  <label>Tipo <span class="text-danger">*</span></label>

                  <select name="tipo" class="custom-select " required>

                    <option value="">Selecione uma opção</option>
                    <option value="1">MÁQUINAS DE CARGA E ELEVAÇÃO</option>
                    <option value="2">VEÍCULOS DE CARGA E ELEVAÇÃO</option>
                    <option value="3">VEÍCULOS DE TRANSPORTES DE PASSAGEIROS</option>
                    <option value="4">VEÍCULOS DE PASSEIO</option>
                    <option value="5">VEÍCULOS UTILITÁRIOS</option>
                    <option value="6">GERADORES</option>

                  </select>

                </div>

                <div class="form-group col-3">

                    <label class="text-muted">Tudo certo?</label>

                  <button type="submit" class="btn btn-block btn-outline-success" disabled="true">Aguardando a validação...</button>

                </div>

              </div>

            </form>

            <div class="row">

              <div class="col-12">

                <h6 style="border-left: 2px solid #555;padding-left: 5px;margin-top: 20px;">

                  VEÍCULOS/EQUIPAMENTOS CADASTRADOS

                </h6>

                <hr class="my-2 mb-4">

                <table id="tbl_consultaVeiculos" class="table">

                  <thead class="thead-light">

                    <tr>

                      <th scope="col">Placa/Nº</th>

                      <th scope="col">Descrição</th>

                      <th scope="col">Fabricante</th>

                      <th scope="col">Ano</th>

                      <th scope="col">Tipo</th>

                      <th scope="col"></th>

                    </tr>

                  </thead>

                  <tbody>

                    <?php foreach ($frotas as $frota) { ?>

                    <tr>

                      <th scope="row"><?= $frota->placa_numero; ?></th>

                      <td><?= $frota->descricao; ?></td>

                      <td><?= $frota->fabricante; ?></td>

                      <td><?= $frota->ano; ?></td>

                      <td><?= strtoupper($frota->tipo); ?></td>

                      <td>

                        <div class="dropdown">

                          <button class="btn btn-sm btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            Ações

                          </button>

                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#editarFrotaModal" data-whatever="<?= $frota->id_veiculo; ?>">Editar</button>

                            <?php if($this->session->userdata('nivel') == 1){ ?>

                              <div class="dropdown-divider"></div>

                              <button type="button" class="dropdown-item text-danger" data-toggle="modal" data-target="#excluirFrotaModal" data-whatever="<?= $frota->id_veiculo; ?>">Excluir</button>

                            <?php }?>

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


          <!-- FORMULÁRIO PARA O CADASTRO DE PRODUTOS -->

          <div class="tab-pane fade <?php if($formulario == "produto") { echo 'show active'; } ?>" id="list-cad-produtos" role="tabpanel" aria-labelledby="cadastro-produtos">

            <h6 style="border-left: 2px solid #555;padding-left: 5px;margin-top: 30px;">

              CADASTRAR PRODUTO

            </h6>

            <hr class="my-2 mb-4">

            <form id="form-cadProdutos" method="post">

              <div class="form-row">

                <div class="form-group col-2">

                  <label>Código <span class="text-danger">*</span></label>

                  <input type="text" minlength="5" maxlength="5" class="form-control" name="codigo" placeholder="Código" required>

                </div>

                <div class="form-group col-5">

                  <label>Descrição <span class="text-danger">*</span></label>

                  <input type="text" class="form-control" minlength="4" name="descricao" placeholder="Descrição" required>

                </div>

                <div class="form-group col-2">

                  <label>Unidade <span class="text-danger">*</span></label>

                  <select class="custom-select" name="unidade" required>

                    <option selected value="">Selecione...</option>

                    <option value="1">AMPOLA - AMPOLA</option>

                    <option value="2">BALDE - BALDE</option>

                    <option value="3">BANDEJ - BANDEJA</option>

                    <option value="4">BARRA - BARRA</option>

                    <option value="5">BISNAG - BISNAGA</option>

                    <option value="6">BLOCO - BLOCO</option>

                    <option value="7">BOBINA - BOBINA</option>

                    <option value="8">BOMB - BOMBONA</option>

                    <option value="9">CAPS - CAPSULA</option>

                    <option value="10">CART - CARTELA</option>

                    <option value="11">CENTO - CENTO</option>

                    <option value="12">CJ - CONJUNTO</option>

                    <option value="13">CM - CENTIMETRO</option>

                    <option value="14">CM2 - CENTIMETRO QUADRADO</option>

                    <option value="15">CX - CAIXA</option>

                    <option value="16">CX2 - CAIXA COM 2 UNIDADES</option>

                    <option value="17">CX3 - CAIXA COM 3 UNIDADES</option>

                    <option value="18">CX5 - CAIXA COM 5 UNIDADES</option>

                    <option value="19">CX10 - CAIXA COM 10 UNIDADES</option>

                    <option value="20">CX15 - CAIXA COM 15 UNIDADES</option>

                    <option value="21">CX20 - CAIXA COM 20 UNIDADES</option>

                    <option value="22">CX25 - CAIXA COM 25 UNIDADESCOM</option>

                    <option value="23">CX50 - CAIXA COM 50 UNIDADES</option>

                    <option value="24">CX100 - CAIXA COM 100 UNIDADES</option>

                    <option value="25">DISP - DISPLAY</option>

                    <option value="26">DUZIA - DUZIA</option>

                    <option value="27">EMBAL - EMBALAGEM</option>

                    <option value="28">FARDO - FARDO</option>

                    <option value="29">FOLHA - FOLHA</option>

                    <option value="30">FRASCO - FRASCO</option>

                    <option value="31">GALAO - GALÃO</option>

                    <option value="32">GF - GARRAFA</option>

                    <option value="33">GRAMAS - GRAMAS</option>

                    <option value="34">JOGO - JOGO</option>

                    <option value="35">KG - QUILOGRAMA</option>

                    <option value="36">KIT - KIT</option>

                    <option value="37">LATA - LATA</option>

                    <option value="38">LITRO - LITRO</option>

                    <option value="39">M - METRO</option>

                    <option value="40">M2 - METRO QUADRADO</option>

                    <option value="41">M3 - METRO CÚBICO</option>

                    <option value="42">MILHEI - MILHEIRO</option>

                    <option value="43">ML - MILILITRO</option>

                    <option value="44">MWH - MEGAWATT</option>

                    <option value="45">PACOTE - PACOTE</option>

                    <option value="46">PALETE - PALETE</option>

                    <option value="47">PARES - PARES</option>

                    <option value="48">PC - PEÇA</option>

                    <option value="49">POTE - POTE</option>

                    <option value="50">K - QUILATE</option>

                    <option value="51">RESMA - RESMA</option>

                    <option value="52">ROLO - ROLO</option>

                    <option value="53">SACO - SACO</option>

                    <option value="54">SACOLA - SACOLA</option>

                    <option value="55">TAMBOR - TAMBOR</option>

                    <option value="56">TANQUE - TANQUE</option>

                    <option value="57">TON - TONELADA</option>

                    <option value="58">TUBO - TUBO</option>

                    <option value="59">UNID - UNIDADE</option>

                    <option value="60">VASIL - VASILHAME</option>

                    <option value="61">VIDRO - VIDRO</option>

                  </select>

                </div>

                <div class="form-group col-3">

                    <label class="text-muted">Tudo certo?</label>

                  <button type="submit" id="btn-cadProdutos" class="btn btn-block btn-outline-success" disabled="true">Aguardando validação...</button>

                </div>

              </div>

            </form>

            <div class="row">

              <div class="col-12">

                <h6 style="border-left: 2px solid #555;padding-left: 5px;margin-top: 20px;">

                  PRODUTOS CADASTRADOS

                </h6>

                <hr class="my-2 mb-4">

                <table id="tbl_consultaProduto" class="table">

                  <thead class="thead-light">

                    <tr>

                      <th scope="col">Código</th>

                      <th scope="col">Descrição</th>

                      <th scope="col">Unidade</th>

                      <th scope="col"></th>

                    </tr>

                  </thead>

                  <tbody>

                    <?php foreach ($produtos as $produto) { ?>

                    <tr>

                      <th scope="row"><?= $produto->codigo;?></th>

                      <td><?= $produto->descricao;?></td>

                      <td><?= $produto->unidade;?></td>

                      <td>

                        <div class="dropdown">

                          <button class="btn btn-sm btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            Ações

                          </button>

                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#editarProdutoModal" data-whatever="<?= $produto->id_produto; ?>">Editar</button>

                            <?php if($this->session->userdata('nivel') == 1){ ?>

                              <div class="dropdown-divider"></div>

                              <button type="button" class="dropdown-item text-danger" data-toggle="modal" data-target="#excluirProdutoModal" data-whatever="<?= $produto->id_produto; ?>">Excluir</button>

                            <?php }?>

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


          <!-- FORMULÁRIO PARA O CADASTRO DE OBRAS -->

          <div class="tab-pane fade <?php if($formulario == "obra") { echo 'show active'; } ?>" id="list-cad-obra" role="tabpanel" aria-labelledby="cadastro-obra">

            <h6 style="border-left: 2px solid #555;padding-left: 5px;margin-top: 30px;">

              CADASTRAR OBRA

            </h6>

            <hr class="my-2 mb-4">

            <form id="form-cadObra" class="" method="post">

              <div class="form-row">

                <div class="form-group col-9">

                  <label>Descrição <span class="text-danger">*</span></label>

                  <input type="text" class="form-control" minlength="4" name="descricao" placeholder="Descrição" required>

                </div>

                <div class="form-group col-3">

                    <label class="text-muted">Tudo certo?</label>

                  <button type="submit" class="btn btn-block btn-outline-success">Cadastrar Armazém</button>

                </div>

              </div>

            </form>

            <div class="row">

              <div class="col-12">

                <h6 style="border-left: 2px solid #555;padding-left: 5px;margin-top: 20px;">

                  OBRAS CADASTRADAS

                </h6>

                <hr class="my-2 mb-4">

                <table id="tbl_consultaObra" class="table table-striped table-border">

                    <thead>

                      <tr>

                          <th scope="col">Código</th>

                          <th scope="col">Descrição</th>

                          <th scope="col"></th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php $count = 0; foreach ($obras as $obra) { $count++; ?>

                        <tr>

                          <td><?= $count; ?></td>

                          <td><?= $obra->descricao; ?></td>

                          <td>

                            <div class="dropdown">

                              <button class="btn btn-sm btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                Ações

                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                <button type="button" class="dropdown-item" data-toggle="modal" data-target="#editarObraModal" data-whatever="<?= $obra->id_obra; ?>">Editar</button>

                                <?php if($this->session->userdata('nivel') == 1){ ?>

                                  <div class="dropdown-divider"></div>

                                  <button type="button" class="dropdown-item text-danger" data-toggle="modal" data-target="#excluirObraModal" data-whatever="<?= $obra->id_obra; ?>">Excluir</button>

                                <?php }?>

                              </div>

                            </div>

                          </td>

                        </tr>

                      <?php }?>

                    </tbody>

                  </table>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

</div>

  <!-- Modal Remover Produtos -->
  <div class="modal fade" id="rmvProduto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <h5 class="modal-title" id="exampleModalLabel">Remover Produto</h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

          </button>

        </div>

        <form id="form-rmvProduto" class="" method="post">

          <div class="modal-body">

            <table id="tbl_rmvProduto" class="table table-striped table-bordere">

              <thead>

                <tr>

                  <th><input type="checkbox" id="marcartodos" data-toggle="tooltip" data-placement="right" title="Marcar todos"></th>

                  <th>Código</th>

                  <th>Descrição</th>

                </tr>

              </thead>

              <tbody>

                <?php $count = 0; foreach($this->cart->contents() as $produto){ ?>

                  <tr>

                    <th>

                      <input type="checkbox" name="<?= $count ?>" classs="marcar" value="<?= $produto['rowid']; ?>">

                    </th>

                    <td><?= $produto['id']; ?></td>

                    <td><?= $produto['name']; ?></td>

                  </tr>

                  <?php $count++;} ?>

                </tbody>

              </table>

            </div>

            <div class="modal-footer">

              <button type="submit" id="btn-rmvProduto" class="btn btn-danger">Remover</button>

            </div>

          </form>

        </div>

      </div>

    </div>

  <!-- Modal Adicionar Produtos -->
  <div class="modal fade" id="addProduto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <h5 class="modal-title" id="exampleModalLabel">Adicionar Produto</h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

          </button>

        </div>

        <form id="form-addProduto" method="post">

          <div class="modal-body">

            <div class="form-row">

              <div class="form-group col-6">

                <label>Código do Produto <span class="text-danger">*</span></label>

                <div class="input-group">

                  <input type="text" id="ap_codproduto" name="cod_produto" maxlength="5" minlength="5" class="form-control" placeholder="Código" required aria-label="Código do produto" aria-describedby="btn-searchprod">

                  <div class="input-group-append">

                    <span class="input-group-text" id="btn-searchprod" style="cursor: pointer;" data-toggle="modal" data-target="#consultaProdutoModal"><svg><i data-feather="search"></i></svg></span>

                  </div>

                </div>

              </div>

              <div class="form-group col-6">

                <label>Quantidade <span class="text-danger">*</span></label>

                <input type="text" name="qntd" class="form-control " placeholder="Quantidade"

                required>

              </div>

            </div>

          </div>

          <div class="modal-footer">

            <button type="submit" id="btn-addProduto" class="btn btn-success" disabled="true">Adicionar</button>

          </div>

        </form>

      </div>

    </div>

  </div>

  <!-- Modal Adicionar Produtos -->
  <div class="modal fade" id="addProdutoEntrada" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <h5 class="modal-title" id="exampleModalLabel">Adicionar Produto</h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

          </button>

        </div>

        <form id="form-addProdutoEntrada" method="post">

          <div class="modal-body">

            <div class="form-row">

              <div class="form-group col-4">

                <label>

                  Código do Produto

                 <span class="text-danger">*</span></label>

                <div class="input-group">

                  <input type="text" id="ape_codproduto" name="cod_produto" maxlength="5" minlength="5" class="form-control" placeholder="Código" required aria-label="Código do produto" aria-describedby="btn-searchprod">

                  <div class="input-group-append">

                    <span class="input-group-text" id="btn-searchprod" style="cursor: pointer;" data-toggle="modal" data-target="#consultaProdutoModal"><svg><i data-feather="search"></i></svg></span>

                  </div>

                </div>

              </div>

              <div class="form-group col-4">

                <label>Valor Unitário <span class="text-danger">*</span></label>

                <input type="number" step="0.01" name="val_unitario" class="form-control" placeholder="Valor Unitário" required>

              </div>

              <div class="form-group col-4">

                <label>Quantidade <span class="text-danger">*</span></label>

                <input type="number" name="qntd" class="form-control" placeholder="Quantidade" required>

              </div>

            </div>

          </div>

          <div class="modal-footer">

            <button type="submit" id="btn-addProdutoEntrada" class="btn btn-success" disabled="true">Adicionar</button>

          </div>

        </form>

      </div>

    </div>

  </div>

  <!-- Modal Consultar Produtos -->
  <div class="modal fade" id="consultaProdutoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <h5 class="modal-title">Consultar Produto</h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

          </button>

        </div>

        <div class="modal-body">

          <form id="form-filtraConsultaProduto" method="post">
            <input type="hidden" name="campo" value="descricao">
            <div class="form-row">
              <div class="form-group col-12">
                <div class="input-group mb-2 mr-sm-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text">Fitrar</div>
                  </div>
                  <input type="text" class="form-control" name="valor" placeholder="Pesquise aqui...">
                </div>
              </div>
            </div>
          </form>

          <table id="tbl_consultaProdutoModal" class="table table-striped table-bordere">

            <input type="hidden" name="valor" value="">

            <thead>

              <tr>

                <th>#</th>

                <th>Código</th>

                <th>Descrição</th>

              </tr>

            </thead>

            <tbody style="overflow: auto;">


            </tbody>

          </table>

        </div>

        <div class="modal-footer">

          <button type="button" id="btnResetUsarProduto" class="btn btn-outline-secondary" disabled="true">Selecionar outro</button>

          <button type="button" id="btnUsarProduto" class="btn btn-outline-primary" disabled="true" data-dismiss="modal">Selecione uma linha</button>

        </div>

      </div>

    </div>

  </div>

  <!-- Modal Consultar Frota -->
  <div class="modal fade" id="consultaFrotaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <h5 class="modal-title">Consultar Frota</h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

          </button>

        </div>

        <div class="modal-body">

          <form id="form-filtraConsultaFrota" method="post">
            <input type="hidden" name="campo" value="descricao">
            <div class="form-row">
              <div class="form-group col-12">
                <div class="input-group mb-2 mr-sm-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text">Fitrar</div>
                  </div>
                  <input type="text" class="form-control" name="valor" placeholder="Pesquise aqui...">
                </div>
              </div>
            </div>
          </form>

          <table id="tbl_consultaFrota" class="table table-striped table-bordere">

            <input type="hidden" name="valor" value="">

            <thead>

              <tr>

                <th class="placa">Placa</th>

                <th>Descrição</th>

              </tr>

            </thead>

            <tbody>

            </tbody>

          </table>

        </div>

        <div class="modal-footer">

          <button type="button" id="btnResetUsarFrota" class="btn btn-outline-secondary" disabled="true">Selecionar outro</button>

          <button type="button" id="btnUsarFrota" class="btn btn-outline-primary" disabled="true" data-dismiss="modal">Selecione uma linha</button>

        </div>

      </div>

    </div>

  </div>

  <!-- Modal Editar Obra -->
  <div class="modal fade" id="editarObraModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <h5 class="modal-title">Editar Obra</h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

          </button>

        </div>

        <form id="form-editarObra" method="post">

          <div class="modal-body">

            <div class="form-row">

              <div class="form-group col-12">

                <label for="#form_editObra_descricao">Descrição <span class="text-danger">*</span></label>

                <input type="hidden" id="form_editObra_id" name="id_obra" value="">
                <input type="text" id="form_editObra_descricao" name="descricao" class="form-control" placeholder="Descrição" required>

              </div>

            </div>

          </div>

          <div class="modal-footer">

            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>

            <button type="submit" class="btn btn-primary">Salvar Alterações</button>

          </div>

        </form>

      </div>

    </div>

  </div>

  <!-- Modal Excluir Obra -->
  <div class="modal fade" id="excluirObraModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <h5 class="modal-title">Excluir Obra</h5>

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

                <p>Ao <strong>excluir</strong>, a obra deixará de ser visível no sistema! <br> Será necessário um novo cadastro! </p>

                <hr>

                <p class="mb-0">- Equipe IWG Web Software ©</p>

              </div>

            </div>

          </div>

          <form id="form-excluirObra" method="post">

            <div class="form-row">

              <div class="form-group col-12">

                <input type="hidden" id="id_obra_delete" name="id_obra" value="">

                Deseja excluir a obra <label id="delete_obra"> <span class="text-danger">*</span></label>?

              </div>

            </div>

            <div class="form-row">

              <div class="form-group col-12">

                <label>Digite sua senha <span class="text-danger">*</span></label>

                <input type="password" class="form-control" id="obraDeleteValida" placeholder="Senha" required data-toggle="tooltip" data-placement="left" title="Digite sua senha para validação!">

              </div>

            </div>

          </div>

          <div class="modal-footer">

            <button type="button" class="btn btn-danger" data-dismiss="modal">

              <i data-feather="x"></i>

              Não

            </button>

            <button type="submit" id="btnDelete_obra" class="btn btn-success" disabled="true">

              <i data-feather="check"></i>

              Sim

            </button>

          </div>

        </div>

      </form>

    </div>

  </div>

  <!-- Modal Editar Produto -->
  <div class="modal fade" id="editarProdutoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <h5 class="modal-title">Editar Produto</h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

          </button>

        </div>

        <form id="form-editarProduto" method="post">

          <div class="modal-body">

            <div class="form-row">

              <div class="form-group col-4">

                <label for="#form_editProduto_codigo">Código <span class="text-danger">*</span></label>

                <input type="text" id="form_editProduto_codigo" class="form-control" disabled="true">

              </div>

              <div class="form-group col-8">

                <label for="#form_editObra_descricao">Descrição <span class="text-danger">*</span></label>

                <input type="hidden" id="form_editProduto_id" name="id_produto" value="">
                <input type="text" id="form_editProduto_descricao" name="descricao" class="form-control" placeholder="Descrição" required>

              </div>

            </div>

            <div class="form-row">

              <div class="form-group col-12">

                <label for="">Unidade  <span class="text-danger">*</span></label>

                <select class="custom-select" name="unidade" required>

                  <option selected id="form_editProduto_unidade">Selecione...</option>

                  <option value="1">AMPOLA - AMPOLA</option>

                  <option value="2">BALDE - BALDE</option>

                  <option value="3">BANDEJ - BANDEJA</option>

                  <option value="4">BARRA - BARRA</option>

                  <option value="5">BISNAG - BISNAGA</option>

                  <option value="6">BLOCO - BLOCO</option>

                  <option value="7">BOBINA - BOBINA</option>

                  <option value="8">BOMB - BOMBONA</option>

                  <option value="9">CAPS - CAPSULA</option>

                  <option value="10">CART - CARTELA</option>

                  <option value="11">CENTO - CENTO</option>

                  <option value="12">CJ - CONJUNTO</option>

                  <option value="13">CM - CENTIMETRO</option>

                  <option value="14">CM2 - CENTIMETRO QUADRADO</option>

                  <option value="15">CX - CAIXA</option>

                  <option value="16">CX2 - CAIXA COM 2 UNIDADES</option>

                  <option value="17">CX3 - CAIXA COM 3 UNIDADES</option>

                  <option value="18">CX5 - CAIXA COM 5 UNIDADES</option>

                  <option value="19">CX10 - CAIXA COM 10 UNIDADES</option>

                  <option value="20">CX15 - CAIXA COM 15 UNIDADES</option>

                  <option value="21">CX20 - CAIXA COM 20 UNIDADES</option>

                  <option value="22">CX25 - CAIXA COM 25 UNIDADESCOM</option>

                  <option value="23">CX50 - CAIXA COM 50 UNIDADES</option>

                  <option value="24">CX100 - CAIXA COM 100 UNIDADES</option>

                  <option value="25">DISP - DISPLAY</option>

                  <option value="26">DUZIA - DUZIA</option>

                  <option value="27">EMBAL - EMBALAGEM</option>

                  <option value="28">FARDO - FARDO</option>

                  <option value="29">FOLHA - FOLHA</option>

                  <option value="30">FRASCO - FRASCO</option>

                  <option value="31">GALAO - GALÃO</option>

                  <option value="32">GF - GARRAFA</option>

                  <option value="33">GRAMAS - GRAMAS</option>

                  <option value="34">JOGO - JOGO</option>

                  <option value="35">KG - QUILOGRAMA</option>

                  <option value="36">KIT - KIT</option>

                  <option value="37">LATA - LATA</option>

                  <option value="38">LITRO - LITRO</option>

                  <option value="39">M - METRO</option>

                  <option value="40">M2 - METRO QUADRADO</option>

                  <option value="41">M3 - METRO CÚBICO</option>

                  <option value="42">MILHEI - MILHEIRO</option>

                  <option value="43">ML - MILILITRO</option>

                  <option value="44">MWH - MEGAWATT</option>

                  <option value="45">PACOTE - PACOTE</option>

                  <option value="46">PALETE - PALETE</option>

                  <option value="47">PARES - PARES</option>

                  <option value="48">PC - PEÇA</option>

                  <option value="49">POTE - POTE</option>

                  <option value="50">K - QUILATE</option>

                  <option value="51">RESMA - RESMA</option>

                  <option value="52">ROLO - ROLO</option>

                  <option value="53">SACO - SACO</option>

                  <option value="54">SACOLA - SACOLA</option>

                  <option value="55">TAMBOR - TAMBOR</option>

                  <option value="56">TANQUE - TANQUE</option>

                  <option value="57">TON - TONELADA</option>

                  <option value="58">TUBO - TUBO</option>

                  <option value="59">UNID - UNIDADE</option>

                  <option value="60">VASIL - VASILHAME</option>

                  <option value="61">VIDRO - VIDRO</option>

                </select>

              </div>

            </div>

          </div>

          <div class="modal-footer">

            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>

            <button type="submit" class="btn btn-primary">Salvar Alterações</button>

          </div>

        </form>

      </div>

    </div>

  </div>

  <!-- Modal Excluir Produto -->
  <div class="modal fade" id="excluirProdutoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <h5 class="modal-title">Excluir Produto</h5>

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

                <p>Ao <strong>excluir</strong>, o produto deixará de ser visível no sistema! <br> Será necessário um novo cadastro! </p>

                <hr>

                <p class="mb-0">- Equipe IWG Web Software ©</p>

              </div>

            </div>

          </div>

          <form id="form-excluirProduto" method="post">

            <div class="form-row">

              <div class="form-group col-12">

                <input type="hidden" id="id_produto_delete" name="id_produto" value="">

                Deseja excluir o produto <label id="delete_produto"> <span class="text-danger">*</span></label>?

              </div>

            </div>

            <div class="form-row">

              <div class="form-group col-12">

                <label>Digite sua senha <span class="text-danger">*</span></label>

                <input type="password" class="form-control" id="produtoDeleteValida" placeholder="Senha" required data-toggle="tooltip" data-placement="left" title="Digite sua senha para validação!">

              </div>

            </div>

          </div>

          <div class="modal-footer">

            <button type="button" class="btn btn-danger" data-dismiss="modal">

              <i data-feather="x"></i>

              Não

            </button>

            <button type="submit" id="btnDelete_produto" class="btn btn-success" disabled="true">

              <i data-feather="check"></i>

              Sim

            </button>

          </div>

        </div>

      </form>

    </div>

  </div>

  <!-- Modal Editar Frota -->
  <div class="modal fade" id="editarFrotaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <h5 class="modal-title">Editar Frota</h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

          </button>

        </div>

        <form id="form-editarFrota" method="post">

          <div class="modal-body">

            <div class="form-row">

              <div class="form-group col-4">

                <label for="#form_editFrota_placa">Placa <span class="text-danger">*</span></label>

                <input type="text" id="form_editFrota_placa" class="form-control" disabled="true" value="">

              </div>

              <div class="form-group col-8">

                <label for="#form_editFrota_descricao">Descrição <span class="text-danger">*</span></label>

                <input type="hidden" id="form_editFrota_id" name="id_veiculo" value="">
                <input type="text" id="form_editFrota_descricao" name="descricao" class="form-control" placeholder="Descrição" required>

              </div>

            </div>

            <div class="form-row">

              <div class="form-group col-12">

                <label for="#form_editFrota_fabricante">Fabricante <span class="text-danger">*</span></label>

                <input type="text" id="form_editFrota_fabricante" class="form-control" name="fabricante" placeholder="Fabricante">

              </div>

            </div>

            <div class="form-row">

              <div class="form-group col-4">

                <label for="#form_editFrota_ano">Ano <span class="text-danger">*</span></label>

                <input type="text" id="form_editFrota_ano" class="form-control" name="ano" placeholder="Ano">

              </div>

              <div class="form-group col-8">

                <label for="form_editProduto_tipo">Tipo <span class="text-danger">*</span></label>

                <select class="custom-select" name="tipo" required>

                  <option selected id="form_editFrota_tipo">Selecione...</option>

                  <option value="1">MÁQUINAS DE CARGA E ELEVAÇÃO</option>

                  <option value="2">VEÍCULOS DE CARGA E ELEVAÇÃO</option>

                  <option value="3">VEÍCULOS DE TRANSPORTES DE PASSAGEIROS</option>

                  <option value="4">VEÍCULOS DE PASSEIO</option>

                  <option value="5">VEÍCULOS UTILITÁRIOS</option>

                  <option value="6">GERADORES</option>

                </select>

              </div>

            </div>

          </div>

          <div class="modal-footer">

            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>

            <button type="submit" class="btn btn-primary">Salvar Alterações</button>

          </div>

        </form>

      </div>

    </div>

  </div>

  <!-- Modal Excluir Frota -->
  <div class="modal fade" id="excluirFrotaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <h5 class="modal-title">Excluir Veículo/Equipamento</h5>

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

                <p>Ao <strong>excluir</strong>, o veículo/equipamento deixará de ser visível no sistema! <br> Será necessário um novo cadastro! </p>

                <hr>

                <p class="mb-0">- Equipe IWG Web Software ©</p>

              </div>

            </div>

          </div>

          <form id="form-excluirFrota" method="post">

            <div class="form-row">

              <div class="form-group col-12">

                <input type="hidden" id="id_frota_delete" name="id_veiculo" value="">

                Deseja excluir o <label id="delete_frota"></label>?

              </div>

            </div>

            <div class="form-row">

              <div class="form-group col-12">

                <label>Digite sua senha <span class="text-danger">*</span></label>

                <input type="password" class="form-control" id="frotaDeleteValida" placeholder="Senha" required data-toggle="tooltip" data-placement="left" title="Digite sua senha para validação!">

              </div>

            </div>

          </div>

          <div class="modal-footer">

            <button type="button" class="btn btn-danger" data-dismiss="modal">

              <i data-feather="x"></i>

              Não

            </button>

            <button type="submit" id="btnDelete_frota" class="btn btn-success" disabled="true">

              <i data-feather="check"></i>

              Sim

            </button>

          </div>

        </div>

      </form>

    </div>

  </div>

  <!-- Modal Editar Agendamento -->
  <div class="modal fade" id="editarAgendamentoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <h5 class="modal-title">Editar Agendamento</h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

          </button>

        </div>

        <form id="form-editarAgendamento" method="post" >

          <div class="modal-body">

            <div class="form-row">

              <div class="form-group col-4">

                <label for="#form_editAgendamento_placa">Placa  <span class="text-danger">*</span></label>

                <input type="hidden" name="id_futura" value="">

                <input type="text" id="form_editAgendamento_placa" class="form-control" disabled="true" value="">

              </div>

              <div class="form-group col-8">

                <label>Data Prevista  <span class="text-danger">*</span></label>

                <input type="date" min="<?php echo date('Y-m-d'); ?>" name="data-prevista" class="form-control" required>

              </div>

            </div>

            <div class="form-row">

              <div class="form-group col-12">

                <label>Descrição  <span class="text-danger">*</span></label>

                <input type="text" name="descricao" class="form-control" placeholder="Descrição" required>

              </div>

            </div>

            <div class="card col-12">

              <div class="m-4">

                <h6 style="border-left: 2px solid orange;color: #888;padding-left: 5px;">

                  ALERTA ANTECIPADO (opcional)

                </h6>

                <hr class="my-2 mb-4">

                <div class="form-row">

                  <div class="form-group col-6">

                    <label>Data Mínima</label>

                    <input type="date" min="<?php echo date('Y-m-d'); ?>" name="data-minima" class="form-control">

                  </div>

                  <div class="form-group col-6">

                    <label>Quilometragem</label>

                    <input type="number" step="1" name="km" class="form-control" placeholder="KM">

                  </div>

                </div>

              </div>

            </div>

          </div>

          <div class="modal-footer">

            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>

            <button type="submit" class="btn btn-primary">Salvar Alterações</button>

          </div>

        </form>

      </div>

    </div>

  </div>

  <!-- Modal Excluir Agendamento -->
  <div class="modal fade" id="excluirAgendamentoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <h5 class="modal-title">Excluir Agendamento</h5>

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

                <p>Ao <strong>excluir</strong>, o agendamento deixará de ser visível no sistema e não será notificado por nenhum meio! <br> Será necessário um novo cadastro! </p>

                <hr>

                <p class="mb-0">- Equipe IWG Web Software ©</p>

              </div>

            </div>

          </div>

          <form id="form-excluirAgendamento" method="post">

            <div class="form-row">

              <div class="form-group col-12">

                <input type="hidden" id="id_agendamento_delete" name="id_futura" value="">

                Deseja excluir o agendamento <br> para o dia <label id="delete_agendamento"></label>?

              </div>

            </div>

            <div class="form-row">

              <div class="form-group col-12">

                <label>Digite sua senha  <span class="text-danger">*</span></label>

                <input type="password" class="form-control" id="agendamentoDeleteValida" placeholder="Senha" required data-toggle="tooltip" data-placement="left" title="Digite sua senha para validação!">

              </div>

            </div>

          </div>

          <div class="modal-footer">

            <button type="button" class="btn btn-danger" data-dismiss="modal">

              <i data-feather="x"></i>

              Não

            </button>

            <button type="submit" id="btnDelete_agendamento" class="btn btn-success" disabled="true">

              <i data-feather="check"></i>

              Sim

            </button>

          </div>

        </div>

      </form>

    </div>

  </div>
