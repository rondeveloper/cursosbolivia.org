<div style="padding:70px;">

    <?php
    /* SEND CONVERTION API */

    $event_data['email'] = hash('sha256', 'test@gmail.com');
    $event_data['value_monto'] = 7.75;
    $event_data['urlpage'] = $dominio;
    $event_data['idproducto'] = "test 5";

    $response = sendConvertionAPI($event_data);

    $res = json_decode($response);

    echo '[[' . $response . ']]';

    echo "<hr> >> ".$res->events_received;
    echo "<hr> >> ".$res->fbtrace_id;

    
    ?>

</div>