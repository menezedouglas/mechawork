var gastos_por_area = document.getElementById("gastos_por_area").getContext('2d');
var gastos_por_mes = document.getElementById("gastos_por_mes").getContext('2d');
var base_url = 'http://localhost/mechawork';

$(document).ready(function(){

  var dataMes = [];
  var dataTotal = [];

  $.ajax({
    url: base_url + '/sistema/geragrafico_custoarea',
    dataType: 'json',
    success: function(data)
    {
      if(data.success)
      {
        var myPieChart = new Chart(gastos_por_area, {
            type: 'pie',
            data: {
              datasets: [{
                  data: data.dados,
                  backgroundColor: ['#0048FF','#0CE86E','#FF690D']
              }],

              // These labels appear in the legend and in the tooltips when hovering different arcs
              labels: [
                  'Manuteções Internas (%)',
                  'Serviços Externos (%)',
                  'Peças (%)'
              ]

          }
        });
      }
      else
      {
        $('#gastos_por_area').css('display', 'none');

        htmlString =
        '<div class="alert alert-info" role="alert">'
          +'<h4 class="alert-heading"><i data-feather="info"></i> Atenção!</h4>'
          +'<p>Não há dados para gerar este gráfico! <br>Por favor, realize cadastros para que possamos gerar informações úteis para você!</p>'
          +'<hr>'
          +'<p class="mb-0">- Equipe IWG Web Software.</p>'
        +'</div>';

        $('#gastos_por_area_div').html(htmlString);
      }
    }
  });

  $.ajax({
    url: base_url + '/sistema/geragrafico_customes',
    dataType: 'json',
    success: function(data)
    {
      if(data.success)
      {
        var myPieChart = new Chart(gastos_por_mes, {
            type: 'line',
            data: {
              datasets: [{
                label: 'Total (R$)',
                data: data.dados['total'],
                borderColor: '#074787'
              }],
              // These labels appear in the legend and in the tooltips when hovering different arcs
              labels: data.dados['mes']
            }
        });
      }
      else
      {
        $('#gastos_por_mes').css('display', 'none');

        htmlString =
        '<div class="alert alert-info" role="alert">'
          +'<h4 class="alert-heading"><i data-feather="info"></i> Atenção!</h4>'
          +'<p>Não há dados para gerar este gráfico! <br>Por favor, realize cadastros para que possamos gerar informações úteis para você!</p>'
          +'<hr>'
          +'<p class="mb-0">- Equipe IWG Web Software.</p>'
        +'</div>';

        $('#gastos_por_mes_div').html(htmlString);
      }
    }
  });



});
