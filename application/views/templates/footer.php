<script src="<?= $url_base; ?>/js/jquery.min.js"></script>
<script src="<?= $url_base; ?>/js/validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="<?= $url_base; ?>/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script src="<?= $url_base; ?>/js/dataTables.min.js"></script>
<script src="<?= $url_base; ?>/js/Chart.js"></script>
<script src="<?= $url_base; ?>/js/sweetalert.min.js"></script>
<script src="<?= $url_base; ?>/js/Effects-jquery.js"></script>
<script src="<?= $url_base; ?>/js/Ajax-forms.js"></script>
<script src="<?= $url_base; ?>/js/Javascript_Functions.js"></script>
<script src="<?= $url_base; ?>/js/DataTables.js"></script>
<?php if($this->session->userdata('email') != ""){ ?>
  <input type="hidden" value="<?= $versao; ?>" id="versao">
  <input type="hidden" value="<?= $nivel_front; ?>" id="nivel_front">
  <input type="hidden" value="<?= $nivel_back; ?>" id="nivel_back">
  <input type="hidden" value="<?= $nivel_database; ?>" id="nivel_database">
  <input type="hidden" value="<?= $login_count; ?>" id="login_count">
<?php } ?>
</body>
</html>
<?php $this->session->set_userdata('login_count', $this->session->userdata('login_count')+1);?>
