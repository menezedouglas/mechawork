<div id="conteudo">

  <div class="card rounded">

    <div class="row">

      <div class="col-12">

        <h3 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">

          Frota

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

            <form id="pesquisa_frota" method="post">

              <div class="form-row">

                <div class="form-group col-12">

                  <label>Campo: <span class="text-danger">*</span></label>

                  <select class="form-control" name="campo">

                      <option value="">Selecione uma opção</option>

                    <option value="placa_numero">Placa/Nº</option>

                    <option value="descricao">Descrição</option>

                    <option value="Fabricante">Fabricante</option>

                    <option value="ano">Ano</option>

                  </select>

                </div>

              </div>

              <div id="valor_pesquisa_frota" class="form-row d-none">

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

                  <div class="custom-control custom-switch">

                    <input type="checkbox" class="custom-control-input" name="estornados_sim" id="consideraEstornados">

                    <label class="custom-control-label" for="consideraEstornados">Considerar Excluídos</label>

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

             <table id="tbl_frota" class="table table-striped table-bordere text-center">

                 <thead>

                   <tr>

                       <th>Placa/Nº</th>

                       <th>Descrição</th>

                       <th>Fabricante</th>

                       <th>Ano</th>

                       <th>Última Manutenção</th>

                       <th></th>

                   </tr>

                 </thead>

                 <tbody id="tbl_frota_body">



                 </tbody>

             </table>

          </div>

        </div>

      </div>

    </div>

  </div>

</div>



<!-- Modal Histórico Manutenções da Frota -->

<div class="modal fade" id="historicoManFrota" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title" id="historicoManFrota_title">Histórico de Manutenções <span></span></h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">

        <div class="card shadow mb-3">

          <div class="card-body">

            <h5 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">

                Dados do Veículo/Equipamento

            </h5>

            <hr class="my-2 mb-4">

            <div class="form-row">

              <div class="form-group col-2">

                Placa:

                <label id="hs_placa_label"> <span class="text-danger">*</span></label>

              </div>

              <div class="form-group col-2">

                Descrição:

                <label id="hs_descricao_label"> <span class="text-danger">*</span></label>

              </div>

            </div>

            <div class="form-row">

              <div class="form-group col-12">

                Ano:

                <label id="hs_ano_label"> <span class="text-danger">*</span></label>

              </div>

            </div>

          </div>

        </div>

        <div class="card shadow mb-3">

          <div class="card-body">

            <h5 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">

                Manutenções Realizadas

            </h5>

            <hr class="my-2 mb-4">

            <table id="tbl_hsmanfrota" class="table">

              <thead>

                <tr>

                  <th>Código</th>

                  <th>Início</th>

                  <th>Término</th>

                  <th>H.T</th>

                  <th>Descrição</th>

                  <th>Nota Serviço</th>

                  <th>Custo Interno</th>

                  <th>Obra</th>

                  <th>Registrado por</th>

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
