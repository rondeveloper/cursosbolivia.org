<div style="padding:70px;">

    <?php
    $arrNotificationMessage = array(
        'title' => 'TEST Curso SABS DS181 SICOES en Trinidad',
        'text' => "Curso de actualizacion del SABS D.S. 181, SICOES el nuevo DS 3548 Dirigido a EMPRESAS ,ESTUDIANTES y CONSULTORES a realizarse en la ciudad de Trinidad - Beni el Sabado 23 de Febrero de 09:15 a 12:15",
        'sound' => "mySound",
        'image' => $dominio."contenido/imagenes/paginas/1542241660curso-ley-1178-d.jpg",
        'icon' => $dominio."contenido/imagenes/paginas/1542241660curso-ley-1178-d.jpg",
        "click_action" => $dominio."curso-sabs-ds181-sicoes-en-trinidad.html",
        'url' => $dominio."curso-sabs-ds181-sicoes-en-trinidad.html",
        "vibrate" => [200, 100, 200, 100, 200, 100, 400],
        'priority' => "high"
    );

    echo "<hr/><b>CONTENIDO</b><br/>----->" . json_encode($arrNotificationMessage) . "<------<hr/>";




    

    $extraData = array(
        'any_extra_data' => "any data"
    );
    //$deviceToken = "cUCmr0ZSdS4:APA91bElZh6ZZhQ7v1NuLK7Xz053VQj4kAIScV_Gzr54leYv4sXIDTtlS14bzLYUrfJ5_xdsUoQiClsl-oLE_rnKq2QnoUuuzUCQTAUT1u_-iGDJCVaMkHRNLCox6jOdyEpalra8nGKt";
    //$deviceToken = "eM_s4Qrff6c:APA91bF82K_7S3XgefvI41nF6bvPQP-3Amj4xr9P9cDItEnDMOUWQxgqBuSGeM8fEasjWb0Q44b6EVeIN-Plv5CN3Fjk3pHdKsuxZ6iLJbeL7aJA5s6qsMyLq3dh4TQqxZKPhBczniHr";
    //$deviceToken = "cXeZ0uaZGw0:APA91bEBBtTEhOIask7AYfHpvPv2QzltyQG1MsU3iGvVLCWVARKNENhFTmCUeM_S8AEzo8m_7tTqQRi4zpKKsj04az3giwlMY0V0vpXuXs6ajC3Gh_-IbX5AL3xV7O00oDokC4dmvnYz";
    $deviceToken = "cUCmr0ZSdS4:APA91bElZh6ZZhQ7v1NuLK7Xz053VQj4kAIScV_Gzr54leYv4sXIDTtlS14bzLYUrfJ5_xdsUoQiClsl-oLE_rnKq2QnoUuuzUCQTAUT1u_-iGDJCVaMkHRNLCox6jOdyEpalra8nGKt";
    $ch = curl_init("https://fcm.googleapis.com/fcm/send");
    $header = array('Content-Type: application/json',
        "Authorization: key=AAAA9h2b9-w:APA91bEmp3dln24XQP9Ck6G920eL0_CDh66E9rlcFz3gsP0TX0myFQt-6gkeXas15dAa7YOzDlBz0XmferenRRajAjJkvPy8oTvKV3uvcWsNZ7z9sOaK1e0N55wWwYB7pUNkWy_0V0-x");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"notification\": " . json_encode($arrNotificationMessage) . ", \"data\":" . json_encode($extraData) . ", \"to\" : " . json_encode($deviceToken) . "}");

    $result = curl_exec($ch);
    curl_close($ch);
    
    
    /* id token */
    $rqsn1 = query("SELECT id FROM cursos_suscnav WHERE token='$deviceToken' LIMIT 1 ");
    $rqsn2 = fetch($rqsn1);
    $id_token = $rqsn2['id'];
    
    
    
    echo "DeviceToken:: $deviceToken<hr/>";

    echo "IdToken:: $id_token<hr/>";

    echo "<hr/>$result<hr/>";
    
    /* datos de recepcion */
    $result_json = json_decode($result,true);
    $multicast_id = $result_json['multicast_id'];
    $success = $result_json['success'];
    $failure = $result_json['failure'];
    $canonical_ids = $result_json['canonical_ids'];
    $results = $result_json['results'];
    $results_error = $result_json['results'][0]['error'];
    $results_message_id = $result_json['results'][0]['message_id'];
    
    echo "<hr/> multicast_id: $multicast_id <br/> success: $success <br/> failure: $failure <br/> canonical_ids: $canonical_ids <br/> results: $results <br/> results_error: $results_error <br/> results_message_id: $results_message_id <hr/>";
    
    /* tratamiento de recepcion */
    if($failure=='1'){
        
        /* no registrado */
        if($results_error=='NotRegistered'){
            query("UPDATE cursos_suscnav SET estado='2' WHERE id='$id_token' ");
            echo "<br/>ESTADO ACTUALIZADO<br/>";
        }
    }
    
    
    /* estados: 1:registrado, 2:des-registrado */

//if ($result === FALSE) {
//    //log_message("DEBUG", 'Curl failed: ' . curl_error($ch));
//} else {
//    $result = json_decode($result);
//    if ($result->success === 1) {
//        return true;
//    } else {
//        return false;
//    }
//}
    ?>


    <hr/>
    TOKEN:
    <div id="token"></div>
    <div id="msg"></div>
    <div id="notis"></div>
    <div id="err"></div>
    <hr/>

</div>

<?php
if(false){
?>

<!--
<script>
    function guardaToken(token) {
        alert('Token:' + token);
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.instant.saveToken.php',
            data: {token: token},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                alert('Token: guardado : ' + token);
            }
        });
    }
</script>
<script src="https://www.gstatic.com/firebasejs/4.6.2/firebase.js"></script>
<script>
    MsgElem = document.getElementById("msg");
    TokenElem = document.getElementById("token");
    NotisElem = document.getElementById("notis");
    ErrElem = document.getElementById("err");
    // Initialize Firebase
    // TODO: Replace with your project's customized code snippet
    var config = {
        apiKey: "AIzaSyDVaOYZYo_6QzuWqu9LHkgROukGGdlDL70",
        authDomain: "cursosbo-220916.firebaseapp.com",
        messagingSenderId: "1057058715628"
    };
    firebase.initializeApp(config);

    const messaging = firebase.messaging();
    messaging.requestPermission()
            .then(function() {
                MsgElem.innerHTML = "Notification permission granted.";
                console.log("Notification permission granted.");

                // get the token in the form of promise
                return messaging.getToken();
            })
            .then(function(token) {
                TokenElem.innerHTML = "token is : " + token;

                document.addEventListener('DOMContentLoaded', function() {
                    guardaToken(token);
                }, false);
            })
            .catch(function(err) {
                ErrElem.innerHTML = ErrElem.innerHTML + "; " + err;
                console.log("Unable to get permission to notify.", err);
            });

    messaging.onMessage(function(payload) {
        console.log("Message received. ", payload);
        NotisElem.innerHTML = NotisElem.innerHTML + JSON.stringify(payload);
        displayNotification(payload);
    });
</script>
<script>
    function displayNotification(payload) {
        if (Notification.permission === 'granted') {
            var options = {
                body: payload["data"]["gcm.notification.text"],
                icon: payload["data"]["gcm.notification.image"]
            };
            var notif = new Notification(payload["data"]["gcm.notification.text"], options);
            notif.onclick = function(event) {
                event.preventDefault(); // Previene al buscador de mover el foco a la pesta�a del Notification
                window.open(payload["data"]["gcm.notification.url"], '_blank');
                notif.close();
            };
            notif.show();
        }
    }
</script>

-->

<script>
    
    
importScripts('https://www.gstatic.com/firebasejs/3.9.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/3.9.0/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in the
// messagingSenderId.
firebase.initializeApp({
   'messagingSenderId': '1057058715628'
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
  console.log('Handling background message ', payload);

  return self.registration.showNotification(payload.data.title, {
    body: payload.data.body,
    icon: payload.data.icon,
    tag: payload.data.tag,
    data: payload.data.link
  });
});

self.addEventListener('notificationclick', function(event) {
  event.notification.close();
  event.waitUntil(self.clients.openWindow(event.notification.data));
});





self.addEventListener('notificationclicks', function(event) {
    let url = '<?php echo $dominio; ?>';
    
    clients.openWindow(url);
    
    event.notification.close(); // Android needs explicit close.
    event.waitUntil(
            
        clients.matchAll({type: 'window'}).then( windowClients => {
            // Check if there is already a window/tab open with the target URL
            for (var i = 0; i < windowClients.length; i++) {
                var client = windowClients[i];
                // If so, just focus it.
                if (client.url === url && 'focus' in client) {
                    return client.focus();
                }
            }
            // If not, then open the target URL in a new window/tab.
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
                
    );
});



//************************


// Initialize Firebase
var config = {
  apiKey: "YOUR_API_KEY",
  authDomain: "YOUR_AUTH_DOMAIN",
  databaseURL: "YOUR_DB_URL",
  projectId: "YOUR_PROJ_ID",
  storageBucket: "YOUR_STORAGE_BUCKET",
  messagingSenderId: "YOUR_SENDER_ID"
};
firebase.initializeApp(config);

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
  console.log('Handling background message ', payload);

  return self.registration.showNotification(payload.data.title, {
    body: payload.data.body,
    icon: payload.data.icon,
    tag: payload.data.tag,
    data: payload.data.link
  });
});

self.addEventListener('notificationclick', function(event) {
  event.notification.close();
  event.waitUntil(self.clients.openWindow(event.notification.data));
});


//***********************





/*
self.addEventListener('notificationclick', function(event) {

  switch(event.action){
    case 'open_url':
    clients.openWindow(event.notification.data.url); //which we got from above
    break;
    case 'any_other_action':
    clients.openWindow("https://www.example.com");
    break;
  }
}
, false);
*/

<?php
}
?>
