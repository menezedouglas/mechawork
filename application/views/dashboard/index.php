<div id="conteudo">
  <div class="card">
    <h3 class="card-title" style="border-left: 6px solid #074787;padding-left: 10px;">Início</h3>
    <hr class="my-2 mb-4">
    <div class="row">
      <div class="col-6">
        <div class="card h-100">
          <div class="card-body">
            <div class="row">
              <div class="col-11">
                <h4 class="card-title m-0">GASTOS POR MÊS (<?php echo date('Y');?>)</h4>
              </div>
              <div class="col-1">
                <h6 class="text-muted" data-toggle="tooltip" data-placement="left" title="Este gráfico é atualizado um vez por dia!"><i data-feather="info"></i></h6>
              </div>
            </div>
            <h6 class="text-muted mt-0">Em reais (R$)</h6>
            <hr class="my-1 mb-3 bg-dark">
            <canvas id="gastos_por_mes"></canvas>
            <div id="gastos_por_mes_div"></div>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="card h-100">
          <div class="card-body">
            <div class="row">
              <div class="col-11">
                <h4 class="card-title m-0">GASTOS POR ÁREA (<?php echo date('Y');?>)</h4>
              </div>
              <div class="col-1">
                <h6 class="text-muted" data-toggle="tooltip" data-placement="left" title="Este gráfico é atualizado instantaneamente!"><i data-feather="info"></i></h6>
              </div>
            </div>
            <h6 class="text-muted mt-0">Em %</h6>
            <hr class="my-1 mb-3 bg-dark">
            <canvas id="gastos_por_area"></canvas>
            <div id="gastos_por_area_div"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
