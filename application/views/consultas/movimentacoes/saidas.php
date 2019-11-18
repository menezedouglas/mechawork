<div id="conteudo">
    <div class="card rounded">
        <h3 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">Saídas</h3>
        <hr class="my-2 mb-4">
        <div class="row">
            <div class="col-3">
                <div class="card">
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
                        <form id="pesquisa_saidas" method="post">
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label>Campo: <span class="text-danger">*</span></label>
                                    <select class="form-control" name="campo">

                                        <option value="">Selecione uma opção</option>
                                        <option value="id_saida">Código</option>
                                        <option value="data_saida">Data</option>
                                        <option value="p.codigo">Produto</option>
                                        <option value="p.descricao">Descrição</option>
                                        <option value="quantia_usada">Quantidade</option>
                                        <option value="e.valor_unitario_medio">Valor Unitário</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label for="pesquisa_estoque_valor">Pesquisar: <span class="text-danger">*</span></label>
                                    <input type="text" name="valor" id="pesquisa_estoque_valor" class="form-control" placeholder="Digite algo...">
                                </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-12">
                                <div class="alert alert-info" role="alert">
                                  Deixe vazio para retornar tudo!
                                </div>
                              </div>
                            </div>
                            <div id="valor_pesquisa_saidas" class="form-row">
                              <div class="form-group col-12">
                                <div class="custom-control custom-switch">
                                  <input type="checkbox" class="custom-control-input" name="estornados_sim" id="consideraEstornados">
                                  <label class="custom-control-label" for="consideraEstornados">Considerar Estornadas</label>
                                </div>
                              </div>
                            </div>
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
                        <table id="tabela_saidas" class="table table-striped table-bordere text-center">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Código</th>
                                    <th>Data</th>
                                    <th>Produto</th>
                                    <th>Descrição</th>
                                    <th>Quantidade</th>
                                    <th>Valor Unitário</th>
                                    <th>Total</th>
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
