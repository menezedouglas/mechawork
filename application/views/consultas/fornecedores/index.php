<div id="conteudo">

  <div class="card rounded">

    <div class="row">

      <div class="col-12 pr-5">

          <h3 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">

            Fornecedores

          </h3>

          <hr class="my-2 mb-4">

      </div>

    </div>

    <div class="row">

      <div class="col-3">

        <div class="card">

          <div class="card-body">

              <div class="form-row pl-2">

                  <h4 class="card-title">

                      Pesquisa

                  </h4>

                  <h5 class="ml-3">

                      <span class="badge badge-pill badge-success">Etapa 1</span>

                  </h5>

              </div>

            <hr class="my-2 mb-4">

            <form id="pesquisa_fornecedores" method="post">

              <div class="form-row">

                <div class="form-group col-12">

                  <label>Campo: <span class="text-danger">*</span></label>

                  <select class="form-control" name="campo">

                    <option value="">Selecione uma opção</option>

                    <option value="cnpj">CNPJ</option>

                    <option value="nome">Razão Social</option>

                    <option value="telefone">Telefone</option>

                  </select>

                </div>

              </div>

              <div id="valor_pesquisa_fornecedores" class="form-row d-none">

                <div class="form-group col-12">

                  <label for="pesquisa_estoque_valor">Pesquisar: <span class="text-danger">*</span></label>

                  <input type="text" name="valor" class="form-control" placeholder="Digite algo...">

                </div>

              </div>

              <div class="form-row">

                <div class="form-group col-12">

                  <div class="alert alert-info" role="alert">

                    Deixe vazio para retornar tudo!

                  </div>

                </div>

              </div>

              <?php if($this->session->userdata('nivel') == 1){ ?>

              <div class="form-row">

                <div class="form-group col-12">

                  <div class="custom-control custom-switch">

                    <input type="checkbox" class="custom-control-input" name="excluidos_sim" id="consideraEstornados">

                    <label class="custom-control-label" for="consideraEstornados">Considerar Excluídos</label>

                  </div>

                </div>

              </div>

              <?php } ?>

              <div class="form-row">

                <div class="form-group col-12">

                  <button type="submit" class="btn btn-sm btn-block btn-outline-success">

                    <i data-feather="search"></i>

                  </button>

                </div>

              </div>

            </form>

          </div>

        </div>

      </div>

      <div class="col-9">

        <div class="card" style="position: absolute; width: 97%; height: 100%; overflow-y: auto; overflow-x: hidden;">

          <div class="card-body">

              <div class="form-row pl-2">

                  <h4 class="card-title">

                      Resultado

                  </h4>

                  <h5 class="ml-3">

                      <span class="badge badge-pill badge-success">Etapa 2</span>

                  </h5>

              </div>

            <hr class="my-2 mb-4">

             <table id="tabela_fornecedores" class="table table-striped table-bordere text-center">

                 <thead>

                   <tr>

                      <th scope="col"></th>

                      <th scope="col">CNPJ</th>

                      <th scope="col">Razão Social</th>

                      <th scope="col">Telefone</th>

                      <th scope="col"></th>

                   </tr>

                 </thead>

                 <tbody>



                 </tbody>

             </table>

          </div>

        </div>

      </div>

    </div>

  </div>

</div>



<!-- Modal Histórico Recente de Notas Fiscais do Fornecedor-->

<div class="modal fade" id="historicoFornecedorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title" id="historicoManFrota_title">Histórico Recente de NF's do Fornecedor <span></span></h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">

        <div class="card shadow mb-3">

          <div class="card-body">

            <h5 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">

                Dados do Fornecedor

            </h5>

            <hr class="my-2 mb-4">

            <div class="form-row">

              <div class="form-group col-3">

                CNPJ:

                <label id="hs_cnpj_label"></label>

              </div>

              <div class="form-group col-9">

                Razão Social:

                <label id="hs_razao_label"></label>

              </div>

            </div>

            <div class="form-row">

              <div class="form-group col-12">

                Telefone:

                <label id="hs_telefone_label"></label>

              </div>

            </div>

          </div>

        </div>

        <div class="card shadow mb-3">

          <div class="card-body">

            <h5 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">

                Notas Fiscais Recentes

            </h5>

            <hr class="my-2 mb-4">

            <table id="tbl_hsfornecedor" class="table text-center">

              <thead>

                <tr>

                  <th>Nº</th>

                  <th>Emissão</th>

                  <th>Vencimento</th>

                  <th>Valor</th>

                  <th>Tipo</th>

                </tr>

              </thead>

              <tbody>



              </tbody>

            </table>

          </div>

        </div>

      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>

      </div>

    </div>

  </div>

</div>


<!-- Modal Editar Fornecedor -->
<div class="modal fade" id="editarFornecedorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title">Editar Fornecedor</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <form id="form-editarFornecedor" method="post" >

        <div class="modal-body">

          <div class="form-row">

            <div class="form-group col-8">

              <label for="#form_editFornecedor_cnpj">CNPJ</label>

              <input type="hidden" name="id_fornecedor" value="">

              <input type="text" id="form_editFornecedor_cnpj" class="form-control" disabled="true" value="">

            </div>

            <div class="form-group col-4">

              <label>Telefone</label>

              <input type="number" name="telefone" class="form-control" required>

            </div>

          </div>

          <div class="form-row">

            <div class="form-group col-12">

              <label>Razão Social</label>

              <input type="text" name="razao_social" class="form-control" placeholder="Razão Social" required>

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

<!-- Modal Excluir Fornecedor -->
<div class="modal fade" id="excluirFornecedorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title">Excluir Fornecedor</h5>

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

              <p>Ao <strong>excluir</strong>, o fornecedor deixará de ser visível no sistema e o CNPJ ficará inválido para uso!</p>

              <hr>

              <p class="mb-0">- Equipe IWG Web Software ©</p>

            </div>

          </div>

        </div>

        <form id="form-excluirFornecedor" method="post">

          <div class="form-row">

            <div class="form-group col-12">

              <input type="hidden" id="id_fornecedor_delete" name="cnpj" value="">

              Deseja realmente excluir este fornecedor? <br><br><label id="delete_fornecedor"></label>

            </div>

          </div>

          <div class="form-row">

            <div class="form-group col-12">

              <label>Digite sua senha  <span class="text-danger">*</span></label>

              <input type="password" class="form-control" id="fornecedorDeleteValida" placeholder="Senha" required data-toggle="tooltip" data-placement="left" title="Digite sua senha para validação!">

            </div>

          </div>

        </div>

        <div class="modal-footer">

          <button type="button" class="btn btn-danger" data-dismiss="modal">

            <i data-feather="x"></i>

            Não

          </button>

          <button type="submit" id="btnDelete_fornecedor" class="btn btn-success" disabled="true">

            <i data-feather="check"></i>

            Sim

          </button>

        </div>

      </div>

    </form>

  </div>

</div>

<!-- Modal Restaurar Fornecedor -->
<div class="modal fade" id="restauraFornecedorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title">Restaurar Fornecedor</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">

        <div class="row">

          <div class="col-12">

            <div class="alert alert-success" role="alert">

              <h4 class="alert-heading">

                <i data-feather="alert-circle"></i>

                Atenção

              </h4>

              <p>Ao <strong>restaurar</strong>, o fornecedor voltará a ser visível no sistema e o CNPJ será reativado!</p>

              <hr>

              <p class="mb-0">- Equipe IWG Web Software ©</p>

            </div>

          </div>

        </div>

        <form id="form-restaurarFornecedor" method="post">

          <div class="form-row">

            <div class="form-group col-12">

              <input type="hidden" id="id_fornecedor_restaura" name="cnpj" value="">

              Deseja realmente restaurar este fornecedor? <br><br><label id="restaura_fornecedor"></label>

            </div>

          </div>

          <div class="form-row">

            <div class="form-group col-12">

              <label>Digite sua senha  <span class="text-danger">*</span></label>

              <input type="password" class="form-control" id="fornecedorRestauraValida" placeholder="Senha" required data-toggle="tooltip" data-placement="left" title="Digite sua senha para validação!">

            </div>

          </div>

        </div>

        <div class="modal-footer">

          <button type="button" class="btn btn-danger" data-dismiss="modal">

            <i data-feather="x"></i>

            Não

          </button>

          <button type="submit" id="btnRestaura_fornecedor" class="btn btn-success" disabled="true">

            <i data-feather="check"></i>

            Sim

          </button>

        </div>

      </div>

    </form>

  </div>

</div>
