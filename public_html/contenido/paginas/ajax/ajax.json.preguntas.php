<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


?>{
    "info": {
        "name":    "Evaluaci&oacute;n de conocimientos adquiridos en la lecci&oacute;n",
        "main":    "<p>Crees que pudiste asimilar la lecci&oacute;n? descubrelo con este examen de repaso.</p>",
        "results": "<h5>Aprende m&aacute;s</h5><p>Etiam scelerisque, nunc ac egestas consequat, odio nibh euismod nulla, eget auctor orci nibh vel nisi. Aliquam erat volutpat. Mauris vel neque sit amet nunc gravida congue sed sit amet purus.</p>",
        "level1":  "Usuario Listo",
        "level2":  "Usuario Competidor",
        "level3":  "Usuario Amateur",
        "level4":  "Usuario Novato",
        "level5":  "Continua en la escuela, ni&ntilde;o..." // no comma here
    },
    "questions": [
        { // Question 1 - Multiple Choice, Single True Answer
            "q": "Que n&uacute;mero es la letra A en el alfabeto?",
            "a": [
                {"option": "8",      "correct": false},
                {"option": "14",     "correct": false},
                {"option": "1",      "correct": true},
                {"option": "23",     "correct": false} // no comma here
            ],
            "correct": "<p><span>Correcto!</span> La letra A esta en la posici&oacute;n n&uacute;mero 1 en el alfabeto!</p>",
            "incorrect": "<p><span>Incorrecto.</span> Es la primera letra del alfabeto. En verdad <em>asististe</em> a primaria?</p>" // no comma here
        },
        { // Question 2 - Multiple Choice, Multiple True Answers, Select Any
            "q": "Cual de los siguientes se concidera un desayuno saludable?",
            "a": [
                {"option": "Jugo y huevos",               "correct": false},
                {"option": "Fruta, avena y yogurt.",   "correct": true},
                {"option": "Pizza sobrante",               "correct": false},
                {"option": "Huevos, fruta, tostadas, y leche", "correct": true} // no comma here
            ],
            "select_any": true,
            "correct": "<p><span>Bien!</span> tu nivel de colesterol probablemente este bien.</p>",
            "incorrect": "<p><span>Hmmm.</span> tal vez deberias reconciderar tus opciones.</p>" // no comma here
        },
        { // Question 3 - Multiple Choice, Multiple True Answers, Select All
            "q": "Donde estas ahora? Selecciona todas las que apliquen.",
            "a": [
                {"option": "Planeta tierra",           "correct": true},
                {"option": "Pluton",                  "correct": false},
                {"option": "En la computadora",  "correct": true},
                {"option": "En la Via Lactea",          "correct": true} // no comma here
            ],
            "correct": "<p><span>Brillante!</span> en verdad eres un genio.</p>",
            "incorrect": "<p><span>No exactamente.</span> Tu realmente estas en el planeta tierra, en la via lactea y en la computadora. pero buen intento.</p>" // no comma here
        },
        { // Question 4
            "q": "Cu&aacute;ntas pulgadas de lluvia recibe Michigan en promedio por a&ntilde;o?",
            "a": [
                {"option": "149",    "correct": false},
                {"option": "32",     "correct": true},
                {"option": "3",      "correct": false},
                {"option": "1291",   "correct": false} // no comma here
            ],
            "correct": "<p><span>Santo cielo!</span> Realmente no esperaba que supieras eso! Correcto!</p>",
            "incorrect": "<p><span>Fallaste.</span> Lo siento. Perdiste. realmente llueve aproximadamente 32 pulgadas por a&ntilde;o en Michigan.</p>" // no comma here
        },
        { // Question 5
            "q": "Es la tierra m&aacute;s grande que el basketball?",
            "a": [
                {"option": "Si",    "correct": true},
                {"option": "No",     "correct": false} // no comma here
            ],
            "correct": "<p><span>Buen trabajo!</span> debes ser muy observador!</p>",
            "incorrect": "<p><span>Error!</span> En que planeta tierra <em>tu</em> estas viviendo?!?</p>" // no comma here
        } // no comma here
    ]
}