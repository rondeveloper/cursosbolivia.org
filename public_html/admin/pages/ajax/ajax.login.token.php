<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

$CLIENT_ID = "1057058715628-dh3id1ccgk88m9a27362bpgg4m2u9h4k.apps.googleusercontent.com";
$id_token = post('token');
//$id_token = "eyJhbGciOiJSUzI1NiIsImtpZCI6IjE4MmU0NTBhMzVhMjA4MWZhYTFkOWFlMWQyZDc1YTBmMjNkOTFkZjgiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJuYmYiOjE2NDQzNDI0OTEsImF1ZCI6IjEwNTcwNTg3MTU2MjgtZGgzaWQxY2Nnazg4bTlhMjczNjJicGdnNG0ydTloNGsuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMDUyMTcxOTgzMzkyMTA1ODU0MTEiLCJlbWFpbCI6ImJyYXlhbi5kZXN0ZWNvQGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJhenAiOiIxMDU3MDU4NzE1NjI4LWRoM2lkMWNjZ2s4OG05YTI3MzYyYnBnZzRtMnU5aDRrLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwibmFtZSI6IkJyYXlhbiBBbGNvbiIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS0vQU9oMTRHallHNGFKV0xWWFpkMlFsWXB2VW5mTi1NZlpoSzA1TzBwM3FHVnI9czk2LWMiLCJnaXZlbl9uYW1lIjoiQnJheWFuIiwiZmFtaWx5X25hbWUiOiJBbGNvbiIsImlhdCI6MTY0NDM0Mjc5MSwiZXhwIjoxNjQ0MzQ2MzkxLCJqdGkiOiJmNTZjYjIwZWQ0MzhmNWU0MTg0M2Y0ZGFlZDZjODA4N2QxZDBjYTExIn0.VTAHwK1YVAr6VVH9a4OITS4n3b8XzlEDUFwZGdkpeASTpMuCSWHZc09AYmEVVfAQvlKYPHoY3CHwGTourpZdoKQIJ4Rv9-1-QSyu3z0d7h2Sebz6CFKtt5N6zsuQNykNjKDRpI337u49niRdtjWZv3t1rbaGn8AwYGF69CgcqNotWkbPztSfxPJPsHn8z5uhm0Ir_P0nJvE8RP9joomRJJoOobdoqg0ZAe4C2DWkoqUgAabBdd6J3AOHcJi-HrQYTzET2SkpuBS41Ut8kddOb65rKCU61Gei9N8MGk06whumwlmB_eHEyd87X6DokUu8Z3IpC3BfnWIqU27UBetQKw";

$client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
$payload = $client->verifyIdToken($id_token);

$response = array('estado' => 0, 'mensaje' => 'Sin resultados');
if ($payload) {
    $userid = $payload['sub'];
    $email = $payload['email'];
    $given_name = $payload['given_name'];
    $family_name = $payload['family_name'];

    /* BUSQUEDA DE ADMINISTRADOR */
    $rqvu1 = query("SELECT * FROM administradores WHERE email='$email' AND sw_cursosbo='1' AND estado IN (1) ORDER BY id DESC limit 1 ");
    if (num_rows($rqvu1) > 0) {
        $administrador = fetch($rqvu1);
        administradorSet('id', $administrador['id']);
        logcursos('Ingreso de administrador [por google]', 'ingreso-administrador', 'administrador', $administrador['id']);
        
        $numero_aleatorio = substr(md5(rand(100, 1000)), 1, 9);
        query("UPDATE administradores SET cookie='$numero_aleatorio' WHERE id='" . $administrador['id'] . "' ");
        setcookie("hsygbaj", $administrador['nick'], time() + (60 * 60 * 24 * 30));
        setcookie("stedfyc", $numero_aleatorio, time() + (60 * 60 * 24 * 30));

        $response['estado'] = 1;
        $response['mensaje'] = 'Login a administrador existente';
    } else {
        $response['estado'] = 2;
        $response['mensaje'] = 'No existe administrador con el correo: ' . $email;
    }

}

echo json_encode($response);
