</div>
</div>
<div class="clear"></div>
</div>
<footer class="clearfix" style="clear: both;">
    <div class="pull-right">
        Desarrollado por <a href="http://desteco.net" target="_blank">Desteco S.R.L.</a>
    </div>
    <div class="pull-left">
        <span id="year-copy"><?php echo date("Y"); ?></span> &reg; <a target="_blank">Version 1.7</a>
    </div>
</footer>
</div>
</div>

<!-- BOX PROCES -->
<script>
    function accionar() {
        $("#boxprocess").addClass("boxproceswake");
        sw_opencloseboxprocess = false;
        opencloseboxprocess();
        var cont = '<div class="box-process">' +
                '<div class="row">' +
                '<div class="col-md-3">' +
                '<div class="loader"></div>' +
                '</div>' +
                '<div class="col-md-9">' +
                '<h3>Testing</h3>' +
                '</div>' +
                '</div>' +
                '</div>'
                ;
        $("#process-container").append(cont);
    }
</script>
<style>
    .loader {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 70px;
        height: 70px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .loader {
        border-top: 16px solid #bace1b;
        border-right: 16px solid #219438;
        border-bottom: 16px solid #41dafd;
        border-left: 16px solid #ffffff;
    }
    .persisting-proces{
        display: none;
        position: fixed;
        left: 20px;
        bottom: 0px;
        width: 500px;
        height: auto;
        background: rgba(191, 191, 191, 0.35);
        box-shadow: 1px 1px 6px grey;
        overflow: hidden;
        border: 1px solid #1ccaf5;
        z-index: 1000;
        border-radius: 10px 10px 0px 0px;
    }
    .box-process{
        background: #1bbae1;
        border-radius: 10px 10px 10px 10px;
        margin: 10px;
    }
    .box-process-close{
        vertical-align: middle;
        background: #c3c3c3;
        color: #FFF;
        width: 10px;
        cursor: pointer;
        border-radius: 0px 12px 0px 0px;
        border-left: 1px dashed #0687ae;
        padding: 7px;
        font-size: 20pt;
        font-family: cursive;
    }
    .boxprocessclosed{
        left: 0px;
        width: 50px;
        border-radius: 0px 10px 0px 0px;
        transition: .3s;
    }
    .boxprocessclosed .td-proces-content{
        display: none;
        transition: .3s;
    }
    .boxproceswake{
        display: block;
    }
</style>
<div id="boxprocess" class="persisting-proces">
    <table style="width:100%;">
        <tr>
            <td class="td-proces-content" id="process-container"></td>
            <td class="box-process-close" onclick="opencloseboxprocess();"><b><</b></td>
        </tr>
    </table>
</div>
<script>
    var sw_opencloseboxprocess = true;
    function opencloseboxprocess() {
        if (sw_opencloseboxprocess) {
            sw_opencloseboxprocess = false;
            $("#boxprocess").addClass("boxprocessclosed");
            $("#boxprocess").removeClass("boxprocessopen");
        } else {
            sw_opencloseboxprocess = true;
            $("#boxprocess").addClass("boxprocessopen");
            $("#boxprocess").removeClass("boxprocessclosed");
        }
    }
</script>
<!-- END BOX PROCES -->

<!-- Modal general 1 -->
<div id="MODAL-modgeneral" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="TITLE-modgeneral"></h4>
      </div>
      <div class="modal-body">
          <div id="AJAXCONTENT-modgeneral"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<!-- Apertura de Caja -->
<?php
$id_administrador = administrador('id');
$rqdas1 = query("SELECT a.sw_cierreapertura_caja FROM administradores a WHERE a.id='$id_administrador' ");
$rqdas2 = fetch($rqdas1);
if($rqdas2['sw_cierreapertura_caja']==1){
    $rqv1 = query("SELECT id FROM caja WHERE id_administrador='$id_administrador' AND fecha=CURDATE() ORDER BY id DESC limit 1 ");
    if (num_rows($rqv1)==0) {
?>
<script>
    setTimeout(()=>{
        $("#TITLE-modgeneral").html('APERTURA DE CAJA');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.contabilidad-caja.apertura_caja.php',
            data: {},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    },10);
</script>
<?php
    }
}
?>


</body>
</html>
<?php
mysqli_close($mysqli);