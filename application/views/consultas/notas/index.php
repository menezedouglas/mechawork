<div id="conteudo">

    <div class="card rounded">

        <div class="row">

          <div class="col-12">

              <h3 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">

                Notas & Recibos

              </h3>

              <hr class="my-2 mb-4">

          </div>

        </div>

        <div class="row">

            <div class="col-3">

                <div class="card" style="overflow: auto;">

                    <div class="card-body">

                        <div class="form-row pl-2">

                            <h4 class="card-title">

                                Pesquisa Específica

                            </h4>

                            <h5 class="ml-3">

                                <span class="badge badge-pill badge-success">Etapa 1</span>

                            </h5>

                        </div>

                        <hr class="my-2 mb-4">

                        <form id="pesquisa_notas" method="post">

                            <div class="form-row">

                                <div class="form-group col-12">

                                    <label>Campo: <span class="text-danger">*</span></label>

                                    <select class="form-control" name="campo">

                                        <option value="">Selecione uma opção</option>

                                        <option value="nota">Nº</option>

                                        <option value="cnpj">Fornecedor</option>

                                        <option value="emissao">Emissão</option>

                                        <option value="vencimento">Vencimento</option>

                                        <option value="valor">Valor da Nota</option>


                                    </select>

                                </div>

                            </div>

                            <div id="valor_pesquisa_notas" class="form-row">

                                <div class="form-group col-12">

                                    <label>Pesquisar: <span class="text-danger">*</span></label>

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

                            <div class="form-row">

                              <div class="form-group col-12">

                                <div class="custom-control custom-switch">

                                  <input type="checkbox" class="custom-control-input" name="excluidos_sim" id="consideraExcluidos">

                                  <label class="custom-control-label" for="consideraExcluidos">Considerar Excluídos</label>

                                </div>

                              </div>

                            </div>

                            <div class="form-row">

                                <div class="form-group col-12">

                                    <button type="submit" class="btn btn-block btn-outline-success">

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

                        <table id="tbl_notaserecibos" class="table table-striped table-bordere text-center">

                            <thead>

                                <tr>

                                    <th></th>

                                    <th>Nº</th>

                                    <th>Fornecedor</th>

                                    <th>Emissão</th>

                                    <th>Vencimento</th>

                                    <th>Valor da Nota</th>

                                    <th>Tipo</th>

                                    <th></th>

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

<!-- Modal Estorna Nota -->

<div class="modal fade" id="estornaNotaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title">Histórico de Movimentações</h5>

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

              <p>Ao <strong>estornar</strong>, a nota e todos os produtos à ela atrelados deixarão de ser contabilizados no sistema! <br> Será necessário um novo cadastro! </p>

              <hr>

              <p class="mb-0">- Equipe IWG Web Software ©</p>

            </div>

          </div>

        </div>

        <form id="form-estornaNota" method="post" action="<?= $url_base; ?>/notas/estornar">

          <div class="form-row">

            <div class="form-group col-12">

              <input type="hidden" name="id_nota" value="">
              <input type="hidden" name="tabela" value="">

              <label id="estorna_nota"></label>
              <br>
              Deseja estornar esta nota?

            </div>

          </div>

          <div class="form-row">

            <div class="form-group col-12">

              <label>Digite sua senha  <span class="text-danger">*</span></label>

              <input type="password" class="form-control" id="notaEstornaValida" placeholder="Senha" required data-toggle="tooltip" data-placement="left" title="Digite sua senha para validação!">

            </div>

          </div>

        </div>

        <div class="modal-footer">

          <button type="button" class="btn btn-danger" data-dismiss="modal">

            <i data-feather="x"></i>

            Não

          </button>

          <button type="submit" id="btnEstorna_nota" class="btn btn-success" disabled="true">

            <i data-feather="check"></i>

            Sim

          </button>

        </div>

      </div>

    </form>

  </div>

</div>
