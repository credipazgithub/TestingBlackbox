<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
    <h5 class="my-0 mr-md-auto font-weight-normal"> <img class="mb-2" src="/assets/img/small.png" alt="" width="24" height="24">eo Hub <img class="mb-2 infinity" src="/assets/img/wait.gif" alt="" width="64" height="30"></h5>
    <img class="rounded-circle shadow" style="height:40px;" src="<?php echo $session["master_image"];?>"/>
    <img class="rounded-circle shadow" style="height:40px;" src="<?php echo $session["image"];?>"/>
    <div class="dropdown pl-2">
      <button class="btn btn-sm btn-danger dropdown-toggle" type="button" id="dropLogged" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?php echo $session["username"];?>
      </button>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropLogged">
        <a class="dropdown-item" href="/site/lg_account">Cuenta</a>
        <a class="dropdown-item" href="/site/lg_applications">Aplicaciones</a>
        <a class="dropdown-item" href="/site/lg_users">Usuarios</a>
        <a class="dropdown-item" href="/site/lg_transactions">Transacciones</a>
        <a class="dropdown-item" href="/site/lg_api">API</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="/site/logout">Logout</a>
      </div>
    </div>
</div>
