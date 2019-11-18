<div id="conteudo">
  <?php foreach ($configs as $config) { ?>
  <div class="card rounded">
    <h3 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">
      Configurações
    </h3>
    <hr class="my-2 mb-4">
    <div class="col-12">
      <div class="row">
        <!-- CONFIGURAÇÃO: URL BASE -->
        <div class="col-4">
          <div class="card row border-0">
            <h5 class="card-title" style="border-left: 6px solid #074787;padding-left: 5px;">
              URL BASE
            </h5>
            <hr class="my-1 mb-3">
            <form id="form-urlbase" method="post">
              <div class="form-row">
                <div class="form-group col-12">
                  <div class="input-group mb-3">
                    <input type="text" class="form-control" value="<?= $config->url_base; ?>" name="url_" aria-label="URL BASE" aria-describedby="btn-urlbase" disabled="true" required>
                  </div>
                </div>
              </div>
            </form>
            <div class="alert alert-warning text-justify" role="alert">
              A "URL BASE" é definida na instalação do sistema no servidor e não pode ser alterada sem intervenção de uma pessoa qualificada e capacitada!
            </div>
          </div>
        </div>

        <!-- CONFIGURAÇÃO: E-MAIL DE CONTATO -->
        <div class="col-4">
          <div class="card row border-0">
            <h5 class="card-title" style="border-left: 6px solid #074787;padding-left: 5px;">
              E-mail de contato
            </h5>
            <hr class="my-1 mb-3">
            <form id="form-emailcontato" method="post">
              <div class="form-row">
                <div class="form-group col-12">
                  <div class="input-group mb-3">
                    <input type="text" class="form-control" value="<?= $config->email_contato; ?>" placeholder="E-mail de contato" name="email_contato" aria-label="E-mail de Contato" aria-describedby="btn-emailcontato" required>
                    <div class="input-group-append">
                      <button class="btn btn-outline-success" type="submit" id="btn-emailcontato">Salvar</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            <div class="alert alert-warning text-justify" role="alert">
              Este e-mail será usado pelo sistema para o envio de mensagens automáticas!
            </div>
          </div>
        </div>

        <div class="col-4">
          <div class="card row border-0">
            <h5 class="card-title" style="border-left: 6px solid #074787;padding-left: 5px;">
              Tempo máximo de inatividade
            </h5>
            <hr class="my-1 mb-3">
            <form id="form-tmpinativo" method="post">
              <div class="form-row">
                <div class="form-group col-12">
                  <div class="input-group mb-3">
                    <select class="custom-select" id="inputGroupTmpInativo" name="tempo_inativo" required>
                      <option <?php if(($config->tempo_minimo) == 5)   {?>selected class="bg-success text-light" <?php } ?> value="5">5 Min.   </option>
                      <option <?php if(($config->tempo_minimo) == 10)  {?>selected class="bg-success text-light" <?php } ?> value="10">10 Min. </option>
                      <option <?php if(($config->tempo_minimo) == 20)  {?>selected class="bg-success text-light" <?php } ?> value="20">20 Min. </option>
                      <option <?php if(($config->tempo_minimo) == 50)  {?>selected class="bg-success text-light" <?php } ?> value="50">50 Min. </option>
                      <option <?php if(($config->tempo_minimo) == 60)  {?>selected class="bg-success text-light" <?php } ?> value="60">1 Hora  </option>
                      <option <?php if(($config->tempo_minimo) == 120) {?>selected class="bg-success text-light" <?php } ?> value="120">2 Horas</option>
                      <option <?php if(($config->tempo_minimo) == 240) {?>selected class="bg-success text-light" <?php } ?> value="240">4 Horas</option>
                      <option <?php if(($config->tempo_minimo) == 480) {?>selected class="bg-success text-light" <?php } ?> value="480">8 Horas</option>
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-outline-success" type="submit" id="button-tmpinativo">Salvar</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            <div class="alert alert-warning text-justify" role="alert">
              Limita o tempo de cada usuário sem atividade enquanto logado no sistema. Todos os usuários que atingirem este tempo serão desconectados do sistema!
            </div>
          </div>
        </div>


        <!-- CONFIGURAÇÃO: E-MAIL DE CONTATO -->
        <div class="col-4">
          <div class="card row border-0">
            <h5 class="card-title" style="border-left: 6px solid #074787;padding-left: 5px;">
              Antecipar notificações em...
            </h5>
            <hr class="my-1 mb-3">
            <form id="form-margemnotifica" method="post">
              <div class="form-row">
                <div class="form-group col-12">
                  <div class="input-group mb-3">
                    <select class="custom-select" id="inputGroupmMargemNotifica" name="margem_notifica" required>
                      <option <?php if(($config->margem_notifica) == 0)   {?>selected class="bg-success text-light" <?php } ?> value="0">Na data!         </option>
                      <option <?php if(($config->margem_notifica) == 1)  {?>selected class="bg-success text-light" <?php } ?> value="1">1 Dia            </option>
                      <option <?php if(($config->margem_notifica) == 2)  {?>selected class="bg-success text-light" <?php } ?> value="2">2 Dias           </option>
                      <option <?php if(($config->margem_notifica) == 5)  {?>selected class="bg-success text-light" <?php } ?> value="5">5 Dias.          </option>
                      <option <?php if(($config->margem_notifica) == 10)  {?>selected class="bg-success text-light" <?php } ?> value="10">10 Dias         </option>
                      <option <?php if(($config->margem_notifica) == 15) {?>selected class="bg-success text-light" <?php } ?> value="15">15 Diass        </option>
                      <option <?php if(($config->margem_notifica) == 30) {?>selected class="bg-success text-light" <?php } ?> value="30">1 Mês(30 Dias)  </option>
                      <option <?php if(($config->margem_notifica) == 60) {?>selected class="bg-success text-light" <?php } ?> value="60">2 Mêses(60 Dias)</option>
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-outline-success" type="submit" id="btn-margemnotifica">Salvar</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            <div class="alert alert-warning text-justify" role="alert">
              Este serve para anteceder as notificações de manutenções agendadas no sistema!
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
