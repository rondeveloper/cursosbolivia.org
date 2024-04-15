<?php
/* mensaje */
$mensaje = '';

/* usuario */
$id_usuario = usuario('id');

/* verif usuario */
if (!isset_usuario()) {
    echo "<br/><br/><br/>Acceso denegado!";
    exit;
}

/* data usuario */
$rqdu1 = query("SELECT id_departamento,nombres,apellidos,celular,email FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
$rqdu2 = fetch($rqdu1);
$nombre_usuario = $rqdu2['nombres'];
$celular_usuario = $rqdu2['celular'];
$email_usuario = $rqdu2['email'];
if ($email_usuario == 'no-email-data') {
    $email_usuario = '';
}
$id_departamento_usuario = $rqdu2['id_departamento'];
?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div class="row" style="background: #f6f5f5;">
            <div class="col-md-2 hidden-xs">
                <?php
                include_once 'contenido/paginas/items/item.d.menu_usuario.php';
                ?>
            </div>
            <div class="col-md-10" style="background:#FFF;padding: 0px 15px;">

                <?php echo $mensaje; ?>


                <?php
                if ($id_departamento_usuario !== '0') {
                    $rqddw1 = query("SELECT nombre,id_whatsapp_grupo FROM departamentos WHERE id='$id_departamento_usuario' LIMIT 1 ");
                    $rqddw2 = fetch($rqddw1);
                    $nombre_departamento = $rqddw2['nombre'];
                    $id_whatsapp_grupo = $rqddw2['id_whatsapp_grupo'];

                    if ($id_whatsapp_grupo !== '0') {
                        $rqdgwd1 = query("SELECT enlace_ingreso FROM whatsapp_grupos WHERE id='$id_whatsapp_grupo' ORDER BY id DESC limit 1 ");
                        $rqdgwd2 = fetch($rqdgwd1);
                        $enlace_ingreso_gw = $rqdgwd2['enlace_ingreso'];
                        ?>
                        <div class="TituloArea">
                            <h3>UNETE AL GRUPO DE WHATSAPP</h3>
                        </div>
                        <div class="Titulo_texto1">
                            <p>
                                Enterate de los cursos de tu inter&eacute;s mediante el grupo de difusion de cursos en 'Whatsapp'. Ingresa al siguiente link o presiona el boton 'whatsapp' para ingresar al grupo de difusion de cursos de capacitaci&oacute;n en el departamento de '<?php echo $nombre_departamento; ?>'.
                            </p>
                            <div class="text-center">
                                <a href="<?php echo $enlace_ingreso_gw; ?>" target="_blank">
                                    <img src='https://www.infosiscon.com/contenido/imagenes/paginas/1510747809whatsapp__.png' style="width:40%;"/>
                                </a>
                                <br/>
                                <br/>
                                <i>Enlace de ingreso al grupo:</i>
                                <br/>
                                <br/>
                                <a href="<?php echo $enlace_ingreso_gw; ?>" style="color: #0f90dc;text-decoration: underline;" target="_blank"><?php echo $enlace_ingreso_gw; ?></a>
                            </div>
                        </div>
                        <hr/>
                        <?php
                    } else {
                        echo "<hr/><p>Por el momento no hay alg&uacute;n grupo de whatsapp creado para su ciudad.</p>";
                    }
                } else {
                    ?>



                    <div class="TituloArea">
                        <h3>UNETE AL GRUPO DE WHATSAPP</h3>
                    </div>
                    <div class="Titulo_texto1">
                        <p>
                            Enterate de los cursos de tu inter&eacute;s mediante el grupo de difusion de cursos en 'Whatsapp'. Ingresa al link o presiona el boton 'whatsapp' para ingresar al grupo de difusion de cursos de capacitaci&oacute;n del departamento correspondiente.
                        </p>
                    </div>

                    <table class='table table-striped table-bordered table-hover'>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                Departamento
                            </th>
                            <th>
                                Ingreso
                            </th>
                            <th>
                                Url de ingreso
                            </th>
                        </tr>
                        <?php
                        $qrdcu1 = query("SELECT * FROM departamentos WHERE id_whatsapp_grupo<>'0' ORDER BY orden ASC ");
                        $cnt = 0;
                        while ($qrdcu2 = fetch($qrdcu1)) {
                            $id_whatsapp_grupo = $qrdcu2['id_whatsapp_grupo'];
                            $rqdgwd1 = query("SELECT enlace_ingreso FROM whatsapp_grupos WHERE id='$id_whatsapp_grupo' ORDER BY id DESC limit 1 ");
                            $rqdgwd2 = fetch($rqdgwd1);
                            $enlace_ingreso_gw = $rqdgwd2['enlace_ingreso'];
                            ?>
                            <tr>
                                <td>
                                    <?php echo ++$cnt; ?>
                                </td>
                                <td>
                                    <?php echo $qrdcu2['nombre']; ?>
                                </td>
                                <td>
                                    <a href="<?php echo $enlace_ingreso_gw; ?>" target="_blank">
                                        <img src='https://www.infosiscon.com/contenido/imagenes/paginas/1510747809whatsapp__.png' style="width:120px;"/>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo $enlace_ingreso_gw; ?>" style="color: #0f90dc;text-decoration: underline;" target="_blank"><?php echo $enlace_ingreso_gw; ?></a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>


                    <?php
                }
                ?>



                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>

                <hr/>




            </div>

        </div>

    </section>
</div>                     




<?php
if(isset_administrador() && false){
?>

<style>
.whatsappme {
    position: fixed;
    z-index: 1000;
    right: 20px;
    bottom: 20px;
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Open Sans","Helvetica Neue",sans-serif;
    font-size: 16px;
    line-height: 26px;
    color: #262626;
    transform: scale3d(0,0,0);
    transition: transform .3s ease-in-out;
    user-select: none;
    -ms-user-select: none;
    -moz-user-select: none;
    -webkit-user-select: none;
}
.whatsappme--left {
    right: auto;
    left: 20px;
}
.whatsappme--show {
    transform: scale3d(1,1,1);
    transition: transform .5s cubic-bezier(.18,.89,.32,1.28);
}
.whatsappme--left .whatsappme__button {
    right: auto;
    left: 8px;
}
.whatsappme--dialog .whatsappme__button {
    box-shadow: 0 1px 2px 0 rgba(0,0,0,.3);
}
.whatsappme--dialog .whatsappme__button {
    background-color: #128c7e;
    transition: background-color .2s linear;
}

.whatsappme__button {
    position: absolute;
    z-index: 2;
    bottom: 8px;
    right: 8px;
    height: 60px;
    min-width: 60px;
    max-width: 95vw;
    background-color: #25d366;
    color: #fff;
    border-radius: 30px;
    box-shadow: 1px 6px 24px 0 rgba(7,94,84,.24);
    cursor: pointer;
    transition: background-color .2s linear;
    -webkit-tap-highlight-color: transparent;
}
.whatsappme__button svg {
    width: 36px;
    height: 36px;
    margin: 12px 12px;
}
.whatsappme__badge.whatsappme__badge--in{
    animation:badge--in .5s cubic-bezier(.27,.9,.41,1.28) 1 both
}
.whatsappme__badge.whatsappme__badge--out{
    animation:badge--out .4s cubic-bezier(.215,.61,.355,1) 1 both
}
.whatsappme__badge {
    position: absolute;
    top: -4px;
    right: -4px;
    width: 20px;
    height: 20px;
    border: none;
    border-radius: 50%;
    background: #e82c0c;
    font-size: 12px;
    font-weight: 600;
    line-height: 20px;
    text-align: center;
    box-shadow: none;
    opacity: 0;
    pointer-events: none;
}
.whatsappme--left .whatsappme__box {
    right: auto;
    left: 0;
}
.whatsappme--dialog .whatsappme__box {
    opacity: 1;
    transform: scale3d(1,1,1);
    transition: opacity .2s ease-out,transform 0s linear;
}
.whatsappme__box {
    position: absolute;
    bottom: 0;
    right: 0;
    z-index: 1;
    width: calc(100vw - 40px);
    max-width: 400px;
    min-height: 280px;
    padding-bottom: 60px;
    border-radius: 32px;
    background: #ede4dd url(//www.runbenguo.com/wp-content/plugins/creame-whatsapp-me/public/css/../images/background.webp) center repeat-y;
    background-size: 100% auto;
    box-shadow: 0 2px 6px 0 rgba(0,0,0,.5);
    overflow: hidden;
    transform: scale3d(0,0,0);
    opacity: 0;
    transition: opacity .4s ease-out,transform 0s linear .3s;
}
.whatsappme__header {
    float: none;
    display: block;
    position: static;
    width: 100%;
    height: 70px;
    padding: 0 26px;
    margin: 0;
    background-color: #2e8c7d;
    color: rgba(255,255,255,.5);
}
.whatsappme__message {
    position: relative;
    min-height: 80px;
    padding: 20px 2px 20px 0;
    margin: 34px 26px;
    border-radius: 32px;
    background-color: #fff;
    color: #4a4a4a;
    box-shadow: 0 1px 2px 0 rgba(0,0,0,.3);
}
.whatsappme__close {
    display: flex;
    position: absolute;
    top: 18px;
    right: 24px;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #000;
    color: #fff;
    text-align: center;
    opacity: .4;
    cursor: pointer;
    transition: opacity .3s ease-out;
    -webkit-tap-highlight-color: transparent;
}
.whatsappme svg path {
    fill: currentColor!important;
}
.whatsappme__header svg {
    width: 120px;
    height: 100%;
}
.whatsappme__close svg {
    display: block;
    width: 12px;
    height: 12px;
    margin: auto;
}
.whatsappme__message__wrap {
    max-height: calc(100vh - 270px);
    padding: 0 20px 0 22px;
    overflow: auto;
}
.whatsappme__message:before {
    content: '';
    display: block;
    position: absolute;
    bottom: 30px;
    left: -18px;
    width: 18px;
    height: 18px;
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADcAAAA1CAYAAADlE3NNAAAEr0lEQVRo3t2aT0gjVxzHf++9mcn8zWhW6bpELWzcogFNaRar7a4tBNy2WATbHpacpdZ6redeZE+9CL02B1ktXsRD/xwsilhoSwsqag/xYK09hCQlmCiTf28vGRnGmZhE183MFx5vmGQy7zO/P/P7PYLAHUIAQCqDAwDPxMREG3IpHL+zs/MZcgkYAgAMAIwOl8lkYm6xGgYAFgAEAGgZHx9/vVwun7nJJTkAEAGgdW9v73NKKXWLSzIA4AEAGQDazs/P/3ALnNEl1a2trY9oRW6wmu6SEgC0ZrPZn9wCp2dIHgCU1dXVtymlZafDIatEksvlfqYGueG9xgOAcnBw8JSa5GR3vIi1aDTaUSwWj5wOZ3RHPUN6U6nUN9RCTnZHDwDI+/v745TSkpPhrOpHcWlpqbdcLieojZwGpseZMDo66svn87/RKnIaGAsAfCAQ8J6dnX1Pr5DjwABAzmazMVqDHAd2enr6La1RjgGLRCJqLpeL0TrUjFDGrMgCAD8/P38vn8//QutUs1pLT/fC5ubmQKFQOKANqNmspbuhBwDEZDL5BaX0lDaoZoK62NxZXFzs1DRthV5TrxrKGFue/v5+KZ1Of1kul5P0BtQUUAAgxOPx9wuFwl/0BvWq3O8C6vDw8F1N036gL0G3ZaVLUEdHRxFN036kL1E3DWMHxAEAPzc3dyedTk+XSqUdegu6CRijy5mBPLOzs2oikfhU07RFSmmG3qKuaxkdxuhy/MzMjDeRSHyiadrz2wYyClUBMh9bzRfAu7u7PX6//z1RFB9zHBcBALUZKoRqUMgEgyvHeHt7+353d/cjQRBGWJZ9jBDqaMYKHKpYBAEAXltbawsGg2FFUd7iOO4hIeQhQuiOEzpdOzCSTCaftLS0fEUIGXbiHiBjU5njVCr1sc/nW6wkDcduS1u1HKRUKv2KMR4ABwvbAWKMA+BwYbsasFgs/uMWuEtxd3x8/J3b4C4Ag8Hg83g8/iyfz//n5IRi1eZzla00HgA8oijyDMNwlFJCCGH0axiGQQAAXq+XyLLMeL1eRlEURpZlRpIkhud5oigK297eLvl8Prm1tVVSFEWSJEkWRVESBMGrqupriqLcFQThLsaYu612n6vUip4KMFv5HJssjhooEi5laoZh0NjYWNvw8PC9np6ejkAg8MDv9w+oqnrfxsNqhgNTh2wE1MGYChyyWGA9RYJVFWTM3MhwjMPhsDw9PT0QDocHOjs731RV9Y1rv+cMlb4Oiy3garWW1b2sPMfceZgHmZqa6pycnPywr6/vA47jfPXAWbU0xOCOqE44u2K8Wl9oBUfMa+rq6hIWFhbGBwcHn9pBohogcRWwRiCRTUiACQ6ZYpxY9JAkFAopy8vLM4FAYKyRrgA1GGf1JperLGgEM4cNG4vF3olGo18TQkT9JsRmAdQw66NsGlbn7Ibdd0um2XzOblz6/ZWVlX8JIb8PDQ090gFJDU+e2sBeZ1hBU9NcqvIQzDMFALq+vp7GGP85MjLyBCHE1tPO1LP4eq4FG/hqnlGyeSiwsbHxfygUOu7t7Y00059JUY3ZHFm8k1lT0cGfnJw8c0ojepWFzd6CMpnM3y8AJPEkZ9khO4IAAAAASUVORK5CYII=);
    background-size: 100%;
}


.whatsappme--dialog .whatsappme__button svg {
    margin: 12px 11px 12px 13px;
}
.whatsappme--dialog .whatsappme__button__open, .whatsappme__button__send {
    display: none;
}

.whatsappme--dialog .whatsappme__button__send, .whatsappme__button__open {
    display: block;
}

.whatsappme .whatsappme__button__send path {
    fill: none!important;
    stroke: #fff!important;
    animation: wame_plain 6s 0s ease-in-out infinite;
}
.whatsappme .whatsappme__button__send path.wame_chat {
    animation-name: wame_chat;
}
@keyframes wame_plain{
    5%{stroke-dashoffset:0;}
    45%{stroke-dashoffset:0;}
    50%{stroke-dashoffset:1096.67;}
    100%{stroke-dashoffset:1096.67;}
}
@keyframes wame_chat{
    50%{stroke-dashoffset:1019.22}
    55%{stroke-dashoffset:0}
    95%{stroke-dashoffset:0}
}

@keyframes badge--in{
    from{opacity:0;transform:translateY(50px)}
    to{opacity:1;transform:translateY(0)}
}
@keyframes badge--out{
    from{opacity:1;transform:translateY(0)}
    to{opacity:0;transform:translateY(-20px)}
}

.bxtxt-selfpage{
    width: 125px;
    height: 100%;
    float: right;
    padding: 23px 0px;
    font-weight: bold;
    font-size: 10pt;
}

</style>
<div id="box-wamsm" class="whatsappme whatsappme--left" data-settings="{&quot;telephone&quot;:&quot;34722518077&quot;,&quot;mobile_only&quot;:false,&quot;button_delay&quot;:3,&quot;whatsapp_web&quot;:false,&quot;message_text&quot;:&quot;Hola\n\u00bfEn qu\u00e9 podemos ayudarte?&quot;,&quot;message_delay&quot;:0,&quot;message_badge&quot;:true,&quot;message_send&quot;:&quot;Hola, necesito informaci\u00f3n sobre Runbenguo&quot;}">
    <div class="whatsappme__button" onclick="show_wamsm();">
<svg class="whatsappme__button__open" viewBox="0 0 24 24">
<path fill="#fff" d="M3.516 3.516c4.686-4.686 12.284-4.686 16.97 0 4.686 4.686 4.686 12.283 0 16.97a12.004 12.004 0 0 1-13.754 2.299l-5.814.735a.392.392 0 0 1-.438-.44l.748-5.788A12.002 12.002 0 0 1 3.517 3.517zm3.61 17.043l.3.158a9.846 9.846 0 0 0 11.534-1.758c3.843-3.843 3.843-10.074 0-13.918-3.843-3.843-10.075-3.843-13.918 0a9.846 9.846 0 0 0-1.747 11.554l.16.303-.51 3.942a.196.196 0 0 0 .219.22l3.961-.501zm6.534-7.003l-.933 1.164a9.843 9.843 0 0 1-3.497-3.495l1.166-.933a.792.792 0 0 0 .23-.94L9.561 6.96a.793.793 0 0 0-.924-.445 1291.6 1291.6 0 0 0-2.023.524.797.797 0 0 0-.588.88 11.754 11.754 0 0 0 10.005 10.005.797.797 0 0 0 .88-.587l.525-2.023a.793.793 0 0 0-.445-.923L14.6 13.327a.792.792 0 0 0-.94.23z"></path>
</svg>
<svg class="whatsappme__button__send" viewBox="0 0 400 400" fill="none" fill-rule="evenodd" stroke="#fff" stroke-linecap="round" stroke-width="33" onclick="window.open('https://api.whatsapp.com/send?phone=59169714008&text=Tengo%20interes%20en%20los%20cursos');">
<path class="wame_plain" stroke-dasharray="1096.67" stroke-dashoffset="1096.67" d="M168.83 200.504H79.218L33.04 44.284a1 1 0 0 1 1.386-1.188L365.083 199.04a1 1 0 0 1 .003 1.808L34.432 357.903a1 1 0 0 1-1.388-1.187l29.42-99.427"></path>
<path class="wame_chat" stroke-dasharray="1019.22" stroke-dashoffset="1019.22" d="M318.087 318.087c-52.982 52.982-132.708 62.922-195.725 29.82l-80.449 10.18 10.358-80.112C18.956 214.905 28.836 134.99 81.913 81.913c65.218-65.217 170.956-65.217 236.174 0 42.661 42.661 57.416 102.661 44.265 157.316"></path>
</svg>
<div id="badge-wamsm" class="whatsappme__badge whatsappme__badge--in">1</div>
</div>
<div class="whatsappme__box">
<div class="whatsappme__header">
<svg viewBox="0 0 120 28"><path fill="#fff" fill-rule="evenodd" d="M117.2 17c0 .4-.2.7-.4 1-.1.3-.4.5-.7.7l-1 .2c-.5 0-.9 0-1.2-.2l-.7-.7a3 3 0 0 1-.4-1 5.4 5.4 0 0 1 0-2.3c0-.4.2-.7.4-1l.7-.7a2 2 0 0 1 1.1-.3 2 2 0 0 1 1.8 1l.4 1a5.3 5.3 0 0 1 0 2.3zm2.5-3c-.1-.7-.4-1.3-.8-1.7a4 4 0 0 0-1.3-1.2c-.6-.3-1.3-.4-2-.4-.6 0-1.2.1-1.7.4a3 3 0 0 0-1.2 1.1V11H110v13h2.7v-4.5c.4.4.8.8 1.3 1 .5.3 1 .4 1.6.4a4 4 0 0 0 3.2-1.5c.4-.5.7-1 .8-1.6.2-.6.3-1.2.3-1.9s0-1.3-.3-2zm-13.1 3c0 .4-.2.7-.4 1l-.7.7-1.1.2c-.4 0-.8 0-1-.2-.4-.2-.6-.4-.8-.7a3 3 0 0 1-.4-1 5.4 5.4 0 0 1 0-2.3c0-.4.2-.7.4-1 .1-.3.4-.5.7-.7a2 2 0 0 1 1-.3 2 2 0 0 1 1.9 1l.4 1a5.4 5.4 0 0 1 0 2.3zm1.7-4.7a4 4 0 0 0-3.3-1.6c-.6 0-1.2.1-1.7.4a3 3 0 0 0-1.2 1.1V11h-2.6v13h2.7v-4.5c.3.4.7.8 1.2 1 .6.3 1.1.4 1.7.4a4 4 0 0 0 3.2-1.5c.4-.5.6-1 .8-1.6.2-.6.3-1.2.3-1.9s-.1-1.3-.3-2c-.2-.6-.4-1.2-.8-1.6zm-17.5 3.2l1.7-5 1.7 5h-3.4zm.2-8.2l-5 13.4h3l1-3h5l1 3h3L94 7.3h-3zm-5.3 9.1l-.6-.8-1-.5a11.6 11.6 0 0 0-2.3-.5l-1-.3a2 2 0 0 1-.6-.3.7.7 0 0 1-.3-.6c0-.2 0-.4.2-.5l.3-.3h.5l.5-.1c.5 0 .9 0 1.2.3.4.1.6.5.6 1h2.5c0-.6-.2-1.1-.4-1.5a3 3 0 0 0-1-1 4 4 0 0 0-1.3-.5 7.7 7.7 0 0 0-3 0c-.6.1-1 .3-1.4.5l-1 1a3 3 0 0 0-.4 1.5 2 2 0 0 0 1 1.8l1 .5 1.1.3 2.2.6c.6.2.8.5.8 1l-.1.5-.4.4a2 2 0 0 1-.6.2 2.8 2.8 0 0 1-1.4 0 2 2 0 0 1-.6-.3l-.5-.5-.2-.8H77c0 .7.2 1.2.5 1.6.2.5.6.8 1 1 .4.3.9.5 1.4.6a8 8 0 0 0 3.3 0c.5 0 1-.2 1.4-.5a3 3 0 0 0 1-1c.3-.5.4-1 .4-1.6 0-.5 0-.9-.3-1.2zM74.7 8h-2.6v3h-1.7v1.7h1.7v5.8c0 .5 0 .9.2 1.2l.7.7 1 .3a7.8 7.8 0 0 0 2 0h.7v-2.1a3.4 3.4 0 0 1-.8 0l-1-.1-.2-1v-4.8h2V11h-2V8zm-7.6 9v.5l-.3.8-.7.6c-.2.2-.7.2-1.2.2h-.6l-.5-.2a1 1 0 0 1-.4-.4l-.1-.6.1-.6.4-.4.5-.3a4.8 4.8 0 0 1 1.2-.2 8.3 8.3 0 0 0 1.2-.2l.4-.3v1zm2.6 1.5v-5c0-.6 0-1.1-.3-1.5l-1-.8-1.4-.4a10.9 10.9 0 0 0-3.1 0l-1.5.6c-.4.2-.7.6-1 1a3 3 0 0 0-.5 1.5h2.7c0-.5.2-.9.5-1a2 2 0 0 1 1.3-.4h.6l.6.2.3.4.2.7c0 .3 0 .5-.3.6-.1.2-.4.3-.7.4l-1 .1a21.9 21.9 0 0 0-2.4.4l-1 .5c-.3.2-.6.5-.8.9-.2.3-.3.8-.3 1.3s.1 1 .3 1.3c.1.4.4.7.7 1l1 .4c.4.2.9.2 1.3.2a6 6 0 0 0 1.8-.2c.6-.2 1-.5 1.5-1a4 4 0 0 0 .2 1H70l-.3-1v-1.2zm-11-6.7c-.2-.4-.6-.6-1-.8-.5-.2-1-.3-1.8-.3-.5 0-1 .1-1.5.4a3 3 0 0 0-1.3 1.2v-5h-2.7v13.4H53v-5.1c0-1 .2-1.7.5-2.2.3-.4.9-.6 1.6-.6.6 0 1 .2 1.3.6.3.4.4 1 .4 1.8v5.5h2.7v-6c0-.6 0-1.2-.2-1.6 0-.5-.3-1-.5-1.3zm-14 4.7l-2.3-9.2h-2.8l-2.3 9-2.2-9h-3l3.6 13.4h3l2.2-9.2 2.3 9.2h3l3.6-13.4h-3l-2.1 9.2zm-24.5.2L18 15.6c-.3-.1-.6-.2-.8.2A20 20 0 0 1 16 17c-.2.2-.4.3-.7.1-.4-.2-1.5-.5-2.8-1.7-1-1-1.7-2-2-2.4-.1-.4 0-.5.2-.7l.5-.6.4-.6v-.6L10.4 8c-.3-.6-.6-.5-.8-.6H9c-.2 0-.6.1-.9.5C7.8 8.2 7 9 7 10.7c0 1.7 1.3 3.4 1.4 3.6.2.3 2.5 3.7 6 5.2l1.9.8c.8.2 1.6.2 2.2.1.6-.1 2-.8 2.3-1.6.3-.9.3-1.5.2-1.7l-.7-.4zM14 25.3c-2 0-4-.5-5.8-1.6l-.4-.2-4.4 1.1 1.2-4.2-.3-.5A11.5 11.5 0 0 1 22.1 5.7 11.5 11.5 0 0 1 14 25.3zM14 0A13.8 13.8 0 0 0 2 20.7L0 28l7.3-2A13.8 13.8 0 1 0 14 0z"></path></svg>
<div class="bxtxt-selfpage"><?php echo $___nombre_del_sitio; ?></div>
<div class="whatsappme__close" onclick="close_wamsm();">
    <svg viewBox="0 0 24 24"><path fill="#fff" d="M24 2.4L21.6 0 12 9.6 2.4 0 0 2.4 9.6 12 0 21.6 2.4 24l9.6-9.6 9.6 9.6 2.4-2.4-9.6-9.6L24 2.4z"></path></svg>
</div>
</div>
    <div class="whatsappme__message"><div class="whatsappme__message__wrap"><div class="whatsappme__message__content">Hola<br>&iquest;En qu&eacute; podemos ayudarte?</div></div></div>

</div>
</div>

<script>
function start_wamsm(){
    $("#box-wamsm").addClass("whatsappme--show");
}
function show_wamsm(){
    $("#box-wamsm").addClass("whatsappme--dialog");
    $("#badge-wamsm").removeClass("whatsappme__badge--in");
    $("#badge-wamsm").addClass("whatsappme__badge--out");
}
function close_wamsm(){
    $("#box-wamsm").removeClass("whatsappme--dialog");
}
setTimeout('start_wamsm()',3000);
</script>
<?php
}
?>



<?php
function fecha_aux($dat) {
    $meses = array('None', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $d1 = date("d", strtotime($dat));
    $d2 = $meses[(int) (date("m", strtotime($dat)))];
    $d3 = date("Y", strtotime($dat));
    return "$d1 de $d2 de $d3";
}
