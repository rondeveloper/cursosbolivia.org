<?php

/* carga de variables de entorno */
require dirname(__FILE__)."/DotEnv.php";
(new DotEnv(dirname(__FILE__)."/../../../.env"))->load();

/* direccion de raiz publica */
$___path_raiz = $_ENV['APP_PUBLIC_PATH'];


/* domain */
if($_ENV['APP_MODE'] == 'production') {
    $https = 'https';
    $dominio = "$https://".$_ENV['APP_SINGLE_DOMAIN']."/";
    $dominio_www = "$https://www.".$_ENV['APP_SINGLE_DOMAIN']."/";
    $dominio_admin = "$https://admin.".$_ENV['APP_SINGLE_DOMAIN']."/";
    $dominio_procesamiento = "$https://procesamiento.".$_ENV['APP_SINGLE_DOMAIN']."/";
    $dominio_plataforma = "$https://plataforma.".$_ENV['APP_SINGLE_DOMAIN']."/";
} else {
    $https = 'http';
    $dominio = "$https://".$_ENV['APP_SINGLE_DOMAIN']."/";
    $dominio_www = "$https://www.".$_ENV['APP_SINGLE_DOMAIN']."/";
    $dominio_admin = "$https://".$_ENV['APP_SINGLE_DOMAIN']."/admin/";
    $dominio_procesamiento = "$https://procesamiento.".$_ENV['APP_SINGLE_DOMAIN']."/";
    $dominio_plataforma = "$https://plataforma.".$_ENV['APP_SINGLE_DOMAIN']."/";
}
/* database */
$env_hostname = $_ENV['DDBB_HOST'];
$env_username = $_ENV['DDBB_USER'];
$env_password = $_ENV['DDBB_PASSWORD'];
$env_database = $_ENV['DDBB_NAME'];

/* nombre del sitio */
$___nombre_del_sitio = $_ENV['APP_NAME'];

/* numero para reduccion de url corta */
$___num_reduccion_id_curso = $_ENV['AUX_REDUCCION_IDCURSO'];

/* conexion */
require dirname(__FILE__)."/Conexion.php";
//$__CONEXION_MANAGER  = new Conexion();

/* carga de variables de configuracion del sistema */
require dirname(__FILE__)."/ConfigManager.php";
$__CONFIG_MANAGER = new ConfigManager();

/* color base */
$___color_base = $__CONFIG_MANAGER->getData('color_base_front');

/* color menu admin */
$___color_menu_admin = $__CONFIG_MANAGER->getData('color_base_menuadmin');


