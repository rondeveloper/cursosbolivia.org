<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_usuario()) {

    query("UPDATE usuarios SET hash_usuario='" . rand(999, 999999) . "' WHERE id='" . usuario('id') . "' ORDER BY id DESC limit 1 ");
    session_unset();
    session_destroy();

    unset($_SESSION['user_id']);
    header("location: ../../../");
    /* ?>
      <script>
      var idtracking = '0';
      var d = new Date();
      d.setTime(d.getTime() + (0 * 24 * 60 * 60 * 1000));
      var expires = "expires=" + d.toUTCString();
      document.cookie = "datasesion_idtracking=" + idtracking + "; " + expires;
      location.href = "https://gegbolivia.cursos.bo/";
      </script>
      <?php */
}
?>