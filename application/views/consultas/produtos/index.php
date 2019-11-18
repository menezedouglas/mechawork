<div id="conteudo">
  <div class="card rounded">
      <div class="row">
        <div class="col-12">
            <h3 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">
              Produtos
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
                      <form id="pesquisa_produto" method="post">
                          <div class="form-row">
                              <div class="form-group col-12">
                                  <label>Campo: <span class="text-danger">*</span></label>
                                  <select class="form-control" name="campo">
                                      <option value="">Selecione uma opção</option>
                                      <option value="codigo">Código</option>
                                      <option value="descricao">Descrição</option>
                                      <option value="unidade">Unidade</option>
                                  </select>
                              </div>
                          </div>
                          <div id="valor_pesquisa_produtos" class="form-row d-none">
                              <div class="form-group col-12">
                                  <label for="pesquisa_estoque_valor">Pesquisar: <span class="text-danger">*</span></label>
                                  <input type="text" name="valor" id="pesquisa_produtos_valor" class="form-control" placeholder="Digite algo...">
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
              <div class="card">
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
                      <table id="datatable" class="table table-striped table-bordere">
                          <thead>
                              <tr>
                                  <th scope="col">Código</th>
                                  <th scope="col">Descrição</th>
                                  <th scope="col">Unidade</th>
                              </tr>
                          </thead>
                          <tbody id="produtostable_body">

                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
