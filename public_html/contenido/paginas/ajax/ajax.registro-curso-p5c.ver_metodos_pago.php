<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

?>
<h3 style="background: #efefef;padding: 10px;border: 1px solid #d4d4d4;">TIGO MONEY</h3> 
<div style="font-size: 17pt;
     border: 1px dashed #c7c7c7;
     padding: 30px;
     margin-bottom: 40px;line-height: 1.5;">
    A la linea <b style="color: #2371e4;">69714008</b> el costo sin recargo (Titular Edgar Aliaga)
</div>
     <?php
     $rqbn1 = query("SELECT id,nombre FROM bancos WHERE estado=1 ORDER BY id ASC ");
     while ($rqbn2 = fetch($rqbn1)) {
         $id_banco = $rqbn2['id'];
         ?>
    <h3 style="background: #efefef;padding: 10px;border: 1px solid #d4d4d4;">
        <?php echo $rqbn2['nombre']; ?>
    </h3>
    <div style="margin-bottom: 40px;">
        <?php
        $rqdcb1 = query("SELECT * FROM cuentas_de_banco WHERE id_banco='$id_banco' AND estado=1 ");
        while ($rqdcb2 = fetch($rqdcb1)) {
            ?>
            <div style="font-size: 17pt;
                 border: 1px dashed #c7c7c7;
                 padding: 30px;margin-bottom: 5px;line-height: 1.5;">
                 <?php echo $rqdcb2['numero_cuenta'] . ' &nbsp; ' . $rqdcb2['titular']; ?>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
?>
<hr>
Consultas Whatsapp : <a href="https://wa.me/59169794724" style="color: #0095ff;text-decoration: underline;">https://wa.me/59169794724</a>