<div id="conteudo">

    <div class="card rounded">

        <div class="row">

          <div class="col-12">

              <h3 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">

                Estoque

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

                        <form id="pesquisa_estoque" method="post">

                            <div class="form-row">

                                <div class="form-group col-12">

                                    <label>Campo: <span class="text-danger">*</span></label>

                                    <select class="form-control" name="campo">

                                        <option value="">Selecione uma opção</option>

                                        <option value="codigo">Código</option>

                                        <option value="descricao">Descrição</option>

                                        <option value="quantia">Qtde. Disponível</option>

                                        <option value="unidade">Unidade</option>

                                        <option value="valor_unitario_medio">Valor Unitário</option>

                                    </select>

                                </div>

                            </div>

                            <div id="valor_pesquisa_estoque" class="form-row d-none">

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

                        <table id="tbl_estoque" class="table table-striped table-bordere text-center">

                            <thead>

                                <tr>

                                    <th>Código</th>

                                    <th>Descrição</th>

                                    <th>Qtde. Disponível</th>

                                    <th>Unidade</th>

                                    <th>Valor Unitário Médio</th>

                                    <th>Valor Total</th>

                                    <th></th>

                                </tr>

                            </thead>

                            <tbody id="estoquetable_body">



                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>




<!-- Modal Histórico Produto -->

<div class="modal fade" id="historicoMoviModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title">Histórico de Movimentações</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">

        <div class="card shadow mb-3">

          <div class="card-body">

            <h5 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">

                Dados do produto

            </h5>

            <hr class="my-2 mb-4">

            <div class="form-row">

              <div class="form-group col-2">

                Código:

                <label id="hs_codigo_label"> <span class="text-danger">*</span></label>

              </div>

              <div class="form-group col-2">

                Unidade:

                <label id="hs_unidade_label"> <span class="text-danger">*</span></label>

              </div>

            </div>

            <div class="form-row">

              <div class="form-group col-12">

                Descrição:

                <label id="hs_descricao_label"> <span class="text-danger">*</span></label>

              </div>

            </div>

          </div>

        </div>

        <div class="card shadow mb-3">

          <div class="card-body">

            <h5 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">

                Entradas Realizadas

            </h5>

            <hr class="my-2 mb-4">

            <table id="datatable2" class="table">

              <thead>

                <tr>

                  <th>Código</th>

                  <th>Data da Entrada</th>

                  <th>Nº da Nota</th>

                  <th>Data de Emissão</th>

                  <th>Quantidade</th>

                  <th>Valor Unitário</th>

                  <th>Valor Total</th>

                </tr>

              </thead>

              <tbody id="hs_entradasproduto">



              </tbody>

            </table>

          </div>

        </div>

        <div class="card shadow">

          <div class="card-body">

            <h5 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">

                Saídas Realizadas

            </h5>

            <hr class="my-2 mb-4">

            <table id="datatable3" class="table">

              <thead>

                <tr>

                  <th>Código</th>

                  <th>Data Saída</th>

                  <th>Manutenção</th>

                  <th>Qnt. Usada</th>

                  <th>Valor Unitario</th>

                  <th>Valor Total</th>

                </tr>

              </thead>

              <tbody id="hs_saidasproduto">



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
