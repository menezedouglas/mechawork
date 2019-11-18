$(document).ready(function(){

  var base_url = 'https://www.mechawork.ml';

  $.ajax({
    url: base_url + '/sistema/geturlbase',
    dataType: 'json',
    success: function(data)
    {
      base_url = data.url_base;
    }
  });

  $('#tbl_removedUsers').DataTable({
    language: {
      url: base_url + '/js/Portuguese-Brasil.json'
    }
  });

  $('#tbl_cadUsuario').DataTable({
    language: {
      url: base_url + '/js/Portuguese-Brasil.json'
    }
  });

  $('#tbl_consultaProduto').DataTable({
    language: {
      url: base_url + '/js/Portuguese-Brasil.json'
    }
  });  

  $('#tbl_consultaObra').DataTable({
    language: {
      url: base_url + '/js/Portuguese-Brasil.json'
    }
  });

  $('#tbl_consultaVeiculos').DataTable({
    language: {
      url: base_url + '/js/Portuguese-Brasil.json'
    }
  });

  $('#tbl_consultaAgendamentos').DataTable({
    language: {
      url: base_url + '/js/Portuguese-Brasil.json'
    }
  });

});
