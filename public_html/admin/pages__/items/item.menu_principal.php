<?php
/* datos de configuracion */
$sw_facturacion_modulos = $__CONFIG_MANAGER->getSw('sw_facturacion_modulos');
?>
<ul class="sidebar-nav">
    <li>
        <a <?php echo loadpage('inicio'); ?> class=" active"><i class="gi gi-stopwatch sidebar-nav-icon"></i>Panel principal</a>
    </li>
    <li>
        <a href="<?php echo $dominio; ?>" target="_blank"><i class="fa fa-paper-plane-o sidebar-nav-icon"></i>Ir a <?php echo $___nombre_del_sitio; ?></a>
    </li>
    <li class="sidebar-header">
        <span class="sidebar-header-options clearfix">
            <a  data-toggle="tooltip" title="" data-original-title="Contenido de la Plataforma"><i class="gi gi-lightbulb"></i></a>
        </span>
        <span class="sidebar-header-title">CONTROL PRINCIPAL</span>
    </li>
    <?php if (acceso_cod('adm-cursos')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-university sidebar-nav-icon"></i>Cursos</a>
            <ul>
                <li>
                    <a <?php echo loadpage('enlaces-cursos'); ?>>ENLACES A CURSOS</a>
                </li>
                <li>
                    <a <?php echo loadpage('cursos-facebook-post'); ?>>Post FACEBOOK</a>
                </li>
                <li>
                    <a <?php echo loadpage('cursos-listar'); ?>>Listado general</a>
                </li>
                <li>
                    <a <?php echo loadpage('cursos-listar/1/no-search/hoy'); ?>>Listado de hoy</a>
                </li>
                <li>
                    <a <?php echo loadpage('cursos-listar/1/no-search/activos'); ?>>Listado activos</a>
                </li>
                <li>
                    <a <?php echo loadpage('cursos-listar/1/no-search/temporales'); ?>>Listado temporales</a>
                </li>
                <li>
                    <a <?php echo loadpage('tematicas-cursos'); ?>>Tematicas</a>
                </li>
                <li>
                    <a <?php echo loadpage('cursos-abreviaciones'); ?>>Abreviaciones</a>
                </li>
                <li>
                    <a <?php echo loadpage('cursos-material'); ?>>Material digital</a>
                </li>
                <li>
                    <a onclick="cambio_fecha_cursos();" style="cursor:pointer;">Cambiar Fechas multiple</a>
                </li>
                <li>
                    <a <?php echo loadpage('cursos-crear'); ?>>Crear curso</a>
                </li>
                <li>
                    <a <?php echo loadpage('examenes'); ?>>EXAMENES</a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if (acceso_cod('adm-cursos')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-university sidebar-nav-icon"></i>Tienda</a>
            <ul>
                <li>
                    <a <?php echo loadpage('tienda-cursos-listar'); ?>>CURSOS</a>
                </li>
                <li>
                    <a <?php echo loadpage('tienda-cursos-registros-listar'); ?>>REGISTROS</a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <li>
        <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-send sidebar-nav-icon"></i>Departamentos</a>
        <ul>
            <li>
                <a <?php echo loadpage('cursos-departamentos'); ?>>Listar departamentos</a>
            </li>
        </ul>
    </li>
    <?php if (acceso_cod('adm-contable')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-money sidebar-nav-icon"></i>Contabilidad</a>
            <ul>
                <li>
                    <a <?php echo loadpage('contabilidad-ingresos'); ?>>Registro de INGRESO</a>
                </li>
                <li>
                    <a <?php echo loadpage('contabilidad-egresos'); ?>>Registro de SALIDA</a>
                </li>
                <li>
                    <a <?php echo loadpage('contabilidad-mis-registros'); ?>>MIS REGISTROS</a>
                </li>
                <li>
                    <a <?php echo loadpage('contabilidad-referencias'); ?>>REFERENCIAS</a>
                </li>
                <?php if (acceso_cod('adm-contable-adm')) { ?>
                    <li>
                    <a <?php echo loadpage('contabilidad-reporte-general'); ?>>REPORTE GENERAL</a>
                </li>
                <?php } ?>
                <li>
                    <a <?php echo loadpage('contabilidad-caja'); ?>>CAJA</a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if (acceso_cod('adm-cursos-cierre')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-university sidebar-nav-icon"></i>Cierre de cursos</a>
            <ul>
                <li>
                    <a <?php echo loadpage('cursos-cierres'); ?>>Listado general</a>
                </li>
                <li>
                    <a <?php echo loadpage('cursos-cierres/1/no-search/modificados'); ?>>Modificados</a>
                </li>
                <li>
                    <a <?php echo loadpage('cursos-listar/1/no-search/sincierre'); ?>>Sin cierre</a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if (acceso_cod('adm-cursos-virtual')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-university sidebar-nav-icon"></i>Cursos virtuales</a>
            <ul>
                <li>
                    <a <?php echo loadpage('cursos-virtuales-listar'); ?>>Listado</a>
                </li>
                <li>
                    <a <?php echo loadpage('cursos-virtuales-crear'); ?>>Crear curso</a>
                </li>
                <li>
                    <a <?php echo loadpage('cursos-grupos'); ?>>GRUPOS</a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if (acceso_cod('adm-cursos')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-university sidebar-nav-icon"></i>Participantes</a>
            <ul>
                <li>
                    <a <?php echo loadpage('participantes-ultimos-activados'); ?>>&Uacute;ltimos activados</a>
                </li>
                <li>
                    <a <?php echo loadpage('cursos-busca-participante'); ?>>Busqueda de participante</a>
                </li>
                <?php if (acceso_cod('adm-control')) { ?>
                    <li>
                        <a <?php echo loadpage('participantes-estadisticas'); ?>>Registros por fecha</a>
                    </li>
                    <li>
                        <a <?php echo loadpage('participantes-fuera-de-fecha'); ?>>Registrados fuera de fecha</a>
                    </li>
                    <li>
                        <a href="certificados-pdfs-generados.adm">PDF's fuera de fecha</a>
                    </li>
                    <li>
                        <a <?php echo loadpage('participantes-cambios-de-nombre'); ?>>Cambios de nombre</a>
                    </li>
                <?php } ?>
                <li>
                    <a <?php echo loadpage('cursos-reprogramacion-participantes'); ?>>Participantes reprogramados</a>
                </li>
                <li>
                    <a <?php echo loadpage('solicitudes-certificados'); ?>>Solicitudes de certificados</a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if (acceso_cod('adm-categorias')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-university sidebar-nav-icon"></i>Categorias</a>
            <ul>
                <li>
                    <a href="categorias-listar.adm">Listar categorias</a>
                    <a href="subcategorias-listar.adm">Listar SUB-categorias</a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if (true) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-university sidebar-nav-icon"></i>Sucursales</a>
            <ul>
                <li>
                    <a href="sucursales.adm">Listar Sucursales</a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <li>
        <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-money sidebar-nav-icon"></i>Tutoriales Empresa</a>
        <ul>
            <li><a href="tutoriales-empresa.adm">Tutoriales de la empresa</a></li>
        </ul>
    </li>
    <?php if (acceso_cod('adm-c-descuento')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-money sidebar-nav-icon"></i>C&oacute;digos descuento</a>
            <ul>
                <li><a href="codigos-descuento.adm">C&oacute;digo descuento</a></li>
            </ul>
        </li>
    <?php } ?>
    <?php if (acceso_cod('adm-organizadores')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-university sidebar-nav-icon"></i>Organizadores</a>
            <ul>
                <li>
                    <a href="organizadores-listar.adm">Listar organizadores</a>
                </li>
            </ul>
        </li>
    <?php } ?>

    <?php if (acceso_cod('adm-ciudades')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-show_big_thumbnails sidebar-nav-icon"></i>Ciudades</a>
            <ul>
                <li><a href="ciudades-listar.adm">Listar ciudades</a></li>
            </ul>
        </li>
    <?php } ?>

    <?php if (acceso_cod('adm-lugares')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-show_big_thumbnails sidebar-nav-icon"></i>Lugares</a>
            <ul>
                <li><a href="lugares-listar.adm">Listar lugares</a></li>
            </ul>
        </li>
    <?php } ?>
        
    <li>
        <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-list sidebar-nav-icon"></i>Blog</a>
        <ul>
            <li><a href="blog-listar.adm">Listar publicaciones</a></li>
        </ul>
    </li>
        
        
    <li>
        <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-envelope sidebar-nav-icon"></i>Depurar correos</a>
        <ul>
            <li><a href="depurar-correos.adm">Depurar correos</a></li>
        </ul>
    </li>


    <?php if (acceso_cod('adm-docentes')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-show_big_thumbnails sidebar-nav-icon"></i>Docentes</a>
            <ul>
                <li><a href="docentes-listar.adm">Listar docentes</a></li>
                <li><a <?php echo loadpage('docentes-cursos-dictados'); ?>>Cursos dictados</a></li>
            </ul>
        </li>
    <?php } ?>
        
    <?php if (acceso_cod('adm-whatsapp')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-show_big_thumbnails sidebar-nav-icon"></i>Whatsapp</a>
            <ul>
                <li><a <?php echo loadpage('whatsapp-numeros'); ?>>Listar numeros</a></li>
            </ul>
        </li>
    <?php } ?>
        
        <?php if (acceso_cod('adm-bancos')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-show_big_thumbnails sidebar-nav-icon"></i>Cuentas Bancarias</a>
            <ul>
                <li><a href="cuentas-bancarias.adm">Listar cuentas</a></li>
            </ul>
        </li>
        <?php } ?>
        
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-show_big_thumbnails sidebar-nav-icon"></i>Base celulares</a>
            <ul>
                <li><a <?php echo loadpage('celulares-listar'); ?>>Listar numeros</a></li>
            </ul>
        </li>
        
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-notes_2 sidebar-nav-icon"></i>PIXEL</a>
            <ul>
                <li>
                    <a <?php echo loadpage('pixel-edicion'); ?>>Pixel facebook</a>
                </li>
            </ul>
        </li>

    <?php if (acceso_cod('adm-cursos')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-certificate sidebar-nav-icon"></i>Certificados</a>
            <ul>
                <li>
                    <a <?php echo loadpage('certificados-participante'); ?>>Certificados de participante</a>
                </li>
                <?php if (acceso_cod('adm-fondo-certs')) { ?>
                <li>
                    <a href="certificados-fondos.adm">FONDOS DE CERTIFICADO</a>
                </li>
                <?php } ?>
                <li>
                    <a href="certificados-emisiones.adm">Certificados emitidos</a>
                </li>
                <li>
                    <a href="certificados-pdfs-generados.adm">PDF's fuera de fecha</a>
                </li>
                <li>
                    <a href="certificados-firmas-listar.adm">Firmas para certificados</a>
                </li>
            </ul>
        </li>
    <?php } ?>

    <?php if (acceso_cod('adm-estadisticas')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-list sidebar-nav-icon"></i>Estadisticas</a>
            <ul>
                <li>
                    <a <?php echo loadpage('calendario-cursos'); ?>>Calendario de cursos</a>
                </li>
                <li>
                    <a <?php echo loadpage('estadisticas-cursos'); ?>>Reporte por fechas</a>
                </li>
            </ul>
        </li>
    <?php } ?>

    <?php if (acceso_cod('adm-banners')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-show_big_thumbnails sidebar-nav-icon"></i>G-Htmls</a>
            <ul>
                <?php
                $sw_ghtmls = false;
                if (file_exists($___path_raiz."contenido/configuracion/sw_ghtmls.dat")) {
                    $fp = fopen($___path_raiz."contenido/configuracion/sw_ghtmls.dat", "r");
                    $linea = (int) fgets($fp);
                    fclose($fp);
                    if ($linea == 1) {
                        $sw_ghtmls = true;
                    } else {
                        $sw_ghtmls = false;
                    }
                }
                ?>
                <li id="box-ghtmls-R">
                    <?php if ($sw_ghtmls) { ?>
                        <a ><span class="btn btn-xs btn-success active">HABILITADO</span></a>
                        <a >
                            Estado: 
                            <span id="box-ghtmls-d"></span>
                            <span class="btn btn-xs btn-default pull-right" style="margin-top: 5px;" onclick="ghtmls_deshabilitar();">Deshabilitar</span>
                        </a>
                    <?php } else { ?>
                        <a ><span class="btn btn-xs btn-default active">NO HABILITADO</span></a>
                        <a >
                            Estado: 
                            <span id="box-ghtmls-h"></span>
                            <span class="btn btn-xs btn-default pull-right" style="margin-top: 5px;" onclick="ghtmls_habilitar();">Habilitar</span>
                        </a>
                    <?php } ?>
                </li>
                <li>
                    <a >INICIO <span id="box-ghtmls-a1"></span> <span class="btn btn-xs btn-default pull-right" style="margin-top: 5px;" onclick="ghtmls_actualizar_inicio();">ACTUALIZAR</span></a>
                </li>
                <li>
                    <a >ACTIVADOS <span id="box-ghtmls-a2"></span> <span class="btn btn-xs btn-default pull-right" style="margin-top: 5px;" onclick="ghtmls_actualizar_activados();">ACTUALIZAR</span></a>
                </li>
            </ul>
        </li>
    <?php } ?>
        
    <?php if (acceso_cod('adm-estadisticas')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-notes_2 sidebar-nav-icon"></i>Actualizadores</a>
            <ul>
                <li>
                    <a onclick="actualizador_cursos_infosicoes();" style="cursor:pointer;">
                        CURSOS Infosicoes <span id="AJAXBOX-actualizador_cursos_infosicoes"></span>
                    </a>
                </li>
                <li>
                    <a onclick="actualizador_ids_bloques();" style="cursor:pointer;">
                        IDs Bloques <span id="AJAXBOX-actualizador_ids_bloques"></span>
                    </a>
                </li>
            </ul>
        </li>
    <?php } ?>

    <?php if (acceso_cod('adm-tags')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-show_big_thumbnails sidebar-nav-icon"></i>Etiquetas</a>
            <ul>
                <li><a href="tags-listar.adm">Listar etiquetas</a></li>
            </ul>
        </li>
    <?php } ?>

    <?php if (acceso_cod('adm-banners')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-show_big_thumbnails sidebar-nav-icon"></i>Banners</a>
            <ul>
                <li><a href="banners-listar.adm">Listar Banners</a></li>
            </ul>
        </li>
    <?php } ?>

    <?php if (acceso_cod('adm-paginas')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-show_big_thumbnails sidebar-nav-icon"></i>Paginas</a>
            <ul>
                <li><a href="paginas-listar.adm">Listar paginas</a></li>
            </ul>
        </li>
    <?php } ?>
        
    <?php if (acceso_cod('adm-paginas')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-university sidebar-nav-icon"></i>Noticias</a>
            <ul>
                <li>
                    <a <?php echo loadpage('noticias-listar'); ?>>Listado de noticias</a>
                </li>
                <li>
                    <a <?php echo loadpage('noticias-crear'); ?>>Crear noticia</a>
                </li>
            </ul>
        </li>
    <?php } ?>
        
    <?php if (acceso_cod('adm-cursos-busq')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-show_big_thumbnails sidebar-nav-icon"></i>Busquedas</a>
            <ul>
                <li><a href="busquedas.adm">Listar busquedas</a></li>
            </ul>
        </li>
    <?php } ?>

    <?php if (acceso_cod('adm-usuarios')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-show_big_thumbnails sidebar-nav-icon"></i>Usuarios</a>
            <ul>
                <li><a href="usuarios-listar.adm">Usuarios registrados</a></li>
                <li><a href="userpush-listar.adm">Usuarios NavPush</a></li>
            </ul>
        </li>
    <?php } ?>
    <li>
        <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-show_big_thumbnails sidebar-nav-icon"></i>Quejas y reclamos</a>
        <ul>
            <li><a href="quejas-reclamos.adm">Listar</a></li>
        </ul>
    </li>
    <?php if (acceso_cod('adm-facturas') && $sw_facturacion_modulos) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-file-text sidebar-nav-icon"></i>Facturaciones</a>
            <ul>
                <li>
                    <a href="facturas-listar.adm">Listar Facturas</a>
                </li>
                <li>
                    <a href="facturas-reporte.adm">Generar reporte</a>
                </li>
                <li>
                    <a href="facturas-emitir.adm">Emitir Factura</a>
                </li>
                <li>
                    <a href="facturas-manuales-listar.adm">Facturas manuales (N.C.)</a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <li>
        <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-file-text sidebar-nav-icon"></i>Recibos</a>
        <ul>
            <li>
                <a href="recibos-listar.adm">Listar recibos</a>
            </li>
            <li>
                <a href="recibos-emitir.adm">Emitir recibo</a>
            </li>
        </ul>
    </li>
    <?php if (acceso_cod('adm-cpagos')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-list sidebar-nav-icon"></i>Comprobantes de pago</a>
            <ul>
                <li>
                    <a href="comprobantes-pago-listar.adm">Listar comprobantes</a>
                </li>
                <li>
                    <a href="comprobantes-pago-emitir.adm">Emitir comprobante</a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <li>
        <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-money sidebar-nav-icon"></i>Devoluciones</a>
        <ul>
            <li>
                <a href="devoluciones-listar.adm">Listar solicitudes</a>
            </li>
        </ul>
    </li>
    <?php if (acceso_cod('adm-administradores')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-user sidebar-nav-icon"></i>Administradores</a>
            <ul>
                <li>
                    <a href="administradores-listar.adm">Listar Administradores</a>
                </li>
                <li>
                    <a href="administradores-crear.adm">Crear Administrador</a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if (isset_organizador()) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-user sidebar-nav-icon"></i>Mi cuenta organizador</a>
            <ul>
                <li><a href="mi-cuenta-organizador.adm">Datos de organizador</a></li>
            </ul>
        </li>
    <?php } else { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-user sidebar-nav-icon"></i>Mi cuenta</a>
            <ul>
                <li><a href="mi-cuenta.adm">Mis datos</a></li>
                <li><a href="mi-cuenta-vincular.adm">Vincular cuentas</a></li>
                <li><a href="mi-cuenta-editar.adm">Configurar mi cuenta</a></li>
            </ul>
        </li>
    <?php } ?>
    <?php if (acceso_cod('adm-login-log')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-user sidebar-nav-icon"></i>LOGIN LOG</a>
            <ul>
                <li>
                    <a <?php echo loadpage('login-log'); ?>>LOGIN LOG</a>
                </li>
            </ul>
        </li>
    <?php } ?>
    <?php if (acceso_cod('adm-mainconfig')) { ?>
        <li>
            <a tec="-" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-cog sidebar-nav-icon"></i>CONFIGURACI&Oacute;N</a>
            <ul>
                <li>
                    <a href="configuracion-del-sistema.adm">Configuraci&oacute;n del sistema</a>
                </li>
            </ul>
        </li>
    <?php } ?>
</ul>

<script>
    function ghtmls_deshabilitar() {
        $("#box-ghtmls-d").html('... <i class="fa fa-circle-o-notch fa-spin"></i>');
        $.ajax({
            url: '<?php echo $dominio_procesamiento; ?>admin/process.menu.ghtmls_deshabilitar.php',
            type: 'POST',
            success: function(data) {
                $("#box-ghtmls-R").html(data);
            }
        });
    }
    function ghtmls_habilitar() {
        $("#box-ghtmls-h").html('... <i class="fa fa-circle-o-notch fa-spin"></i>');
        $.ajax({
            url: '<?php echo $dominio_procesamiento; ?>admin/process.menu.ghtmls_habilitar.php',
            type: 'POST',
            success: function(data) {
                $("#box-ghtmls-R").html(data);
            }
        });
    }
    function ghtmls_actualizar_inicio() {
        $("#box-ghtmls-a1").html('... <i class="fa fa-circle-o-notch fa-spin"></i>');
        $.ajax({
            url: '<?php echo $dominio_procesamiento; ?>admin/process.menu.ghtmls_actualizar_inicio.php',
            type: 'POST',
            success: function(data) {
                $("#box-ghtmls-a1").html(data);
            }
        });
    }
    function ghtmls_actualizar_activados() {
        $("#box-ghtmls-a2").html('... <i class="fa fa-circle-o-notch fa-spin"></i>');
        $.ajax({
            url: '<?php echo $dominio_procesamiento; ?>admin/process.menu.ghtmls_actualizar_activados.php',
            type: 'POST',
            success: function(data) {
                $("#box-ghtmls-a2").html(data);
            }
        });
    }
</script>

<script>
    function actualizador_cursos_infosicoes() {
        $("#AJAXBOX-actualizador_cursos_infosicoes").html("...");
        $.ajax({
            url: '<?php echo $dominio_procesamiento; ?>admin/process.menu.actualizador_cursos_infosicoes.php',
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXBOX-actualizador_cursos_infosicoes").html(data);
            }
        });
    }
</script>

<script>
    function actualizador_ids_bloques() {
        $("#AJAXBOX-actualizador_ids_bloques").html("...");
        $.ajax({
            url: '<?php echo $dominio_procesamiento; ?>admin/process.menu.actualizador_ids_bloques.php',
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXBOX-actualizador_ids_bloques").html(data);
            }
        });
    }
</script>


<!-- cambio_fecha_cursos -->
<script>
    function cambio_fecha_cursos() {
        $("#TITLE-modgeneral").html('CAMBIO DE FECHA MULTIPLE');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.inicio.cambio_fecha_cursos.php',
            data: {},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
