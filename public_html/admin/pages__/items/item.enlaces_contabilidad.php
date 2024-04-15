<a class="btn btn-sm btn-default" <?php echo loadpage('contabilidad-ingresos'); ?>>INGRESOS</a> &nbsp;&nbsp;
<a class="btn btn-sm btn-default" <?php echo loadpage('contabilidad-egresos'); ?>>SALIDAS</a> &nbsp;&nbsp;
<a class="btn btn-sm btn-default" <?php echo loadpage('contabilidad-mis-registros'); ?>>MIS REGISTROS</a> &nbsp;&nbsp;
<a class="btn btn-sm btn-default" <?php echo loadpage('contabilidad-referencias'); ?>>REFERENCIAS</a> &nbsp;&nbsp;
<?php if (acceso_cod('adm-contable-adm')) { ?>
<a class="btn btn-sm btn-default" <?php echo loadpage('contabilidad-reporte-general'); ?>>REPORTE GENERAL</a> &nbsp;&nbsp;
<?php } ?>