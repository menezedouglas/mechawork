<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>MechaWork - In Work</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style type="text/css">

      *{margin: 0; padding: 0;}

      #inwork
      {
        position: absolute;
        box-sizing: border-box;
        width: 100%;
        height: 100%;
        background-color: #074787;
        overflow: hidden;
      }

      #logo
      {
        width: 30%;
        margin-top: 7%;
      }

      #inwork p
      {
        color: #fff;
        margin-top: 6%;
        margin-bottom: 3%;
        font-weight: bold;
        font-style: italic;
      }

    </style>
  </head>
  <body>
    <div id="inwork">

      <div class="row">
        <div class="col-12">
            <center>

              <img id="logo" src="<?= $url_base; ?>/img/logo-v2-white.png" alt="">

              <p>Desenvolvimento em progresso...</p>

              <div class="col-6 mt-5 mb-5">
                <div class="progress">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" aria-valuenow="82" aria-valuemin="0" aria-valuemax="100" style="width: 82%">82%</div>
                </div>
              </div>

            </center>
        </div>
      </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
  </body>
</html>
