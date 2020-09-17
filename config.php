<?php

/* 
File che contiene le configurazioni dell'API
Dal settaggio delle variabili accettate, alla configurazione della connessione vs il DB
*/

/*
Impostare le variabili che vengono utilizzate nel parametro GET della richiesta
Ogni chiave identifica, appunto, il nome della variabile
Abbiamo 5 settaggi:
- mandatory: 1 se la variabile e' obbligatoria, 2 se e' opzionale
- type: identifica il tipo, i->integer o intero (definire il min e il max), e->enum (definire quali stringhe sono accettate) e  s->stringa (definire la regex di validita') 
- default_value: valore di default
- msg_err: stringa di errore che viene restituita alla pagina che richiama l'API in caso in cui almeno una varibile non ha il tipo/formato o range corretto 
 */

$api_settings = array(
    'node_id' => array( 'mandatory' => 1,
                        'type' => 'i',
                        'validation' => array(1, 50), 
                        'default_value' => -1, 
                        'msg_err' => 'Node ID non valido'),
    'language' => array( 'mandatory' => 1,
                        'type' => 'e',
                        'validation' => array('english', 'italian'), 
                        'default_value' => '', 
                        'msg_err' => "Lingua non esistente o non valida (stringhe accettate 'english' o 'italian')"),
    'search_keyword' => array( 'mandatory' => 0,
                        'type' => 's',
                        'validation' => '/^[a-zA-Z\d]+$/', 
                        'default_value' => '',  // valore di default
                        'msg_err' => 'La stringa passata contiene caratteri non validi'),
    'page_num' => array( 'mandatory' => 0,
                        'type' => 'i',
                        'validation' => array(1, 50), 
                        'default_value' => -1, //valore di default
                        'msg_err' => 'Numero di pagina non valido'),
    'page_size' => array( 'mandatory' => 0,
                        'type' => 'i',
                        'validation' => array(1, 1000), 
                        'default_value' => 100,  // valore di default
                        'msg_err' => 'page_size non valido'),
);

/*
 * Settaggio per la connessione al DB
 */

    $server_db = 'localhost';
    $port_db = '8889';
    $user_db = '0brand';
    $password_db = '0brand';
    $name_db= 'enricoferro';
?>