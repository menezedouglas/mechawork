<?php if(($this->session->userdata('login_count') != "")&&($this->session->userdata('login_count') == 1)){ ?>
<div id="loader">

  <center>
    <img src="<?= $url_base; ?>/img/logo-v2-white.png" alt="">
    <h4>Aguarde</h4>
    <div class="spinner-border text-light" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </center>

</div>
<?php } ?>
