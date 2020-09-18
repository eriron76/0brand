<?php

// API richiamata per analizzare l'albero dell'organigramma memorizza nel DB

require 'config.php'; // file di configurazione che riguarda il db e i parametri accettati
require 'classes/Tools.php';
require 'classes/NodeTree.php';



$_DEBUG = false; // se messo a true, uso echo o print_r dei dati in questa pagina al posto del json, visualizza anche gli errori durante il check delle variabili 

// array generico di ritorno 
$return_value = array(
    'nodes' => null,
    'next_page' => 0,
    'prev_page' => 0,
);


// inizializzo le variabili con i valorid di default impostati nell'array della configurazione
$node_id = $api_settings['node_id']['default_value'];
$language =  $api_settings['language']['default_value'];
$search_keyword =  $api_settings['search_keyword']['default_value'];
$page_num =  $api_settings['page_num']['default_value']; // imposto 0 come valore di default (come da specifica)
$page_size =  $api_settings['page_size']['default_value']; // imposto 100 come valore di default (come da specifica)


// verifico se i parametri GET passati esistono e sono validi
$msg_err = '';
$err_no = 0;
foreach ($api_settings as $k => $settings) {
    if ($settings['mandatory'] == 1) {
        // siamo nel caso in cui il parametro e' obbligatorio
        if (!isset($_GET[$k])) {
            $err_no = 1;
            $msg_err = $settings['msg_err'];
            if ($_DEBUG) {
                echo "<br>Sezione obbligatoria: Chiave '$k' -> errore, non esiste<hr>";
            }             
            break;
        }
        $err_no = Tools::checkGETValue($k, $settings, $settings['type']);    
        
        if ($_DEBUG) {
            echo "<br>Sezione obbligatoria: Chiave '$k' -> checkGETValue return: $err_no<hr>";
        }        
            
        if ($err_no == 0) {
            ${$k} = $_GET[$k];
        } else {
            if ($err_no == 9) {
                // il tipo passato non esiste, quindi uso un errore generico
                $msg_err = "Errore generico nella configurazione, contattaci al info@0brand.com";
            } else {
                $msg_err = $settings['msg_err'];
            }
            break;
        }
    } else {
        // caso non obbligatorio, quindi verifico sono se esiste in GET
        if (isset($_GET[$k])) {
            $err_no = Tools::checkGETValue($k, $settings, $settings['type']);
        if ($_DEBUG) {
            echo "<br>Sezione opzionale: Chiave '$k' -> checkGETValue return: $err_no<hr>";
        }            
            if ($err_no == 0) {
                ${$k} = $_GET[$k];
            } else {
                if ($err_no == 9) {
                    // il tipo passato non esiste, quindi uso un errore generico
                    $msg_err = "Errore generico nella configurazione, contattaci al info@0brand.com";
                } else {
                    $msg_err = $settings['msg_err'];
                    echo "<br>Errore nella sezione opzionale $k";
                }
                break;
            }
        }
    }
}

if ($err_no > 0) {
    $return_value['error_no'] = $err_no;
    $return_value['error'] = $msg_err;
    $return_value_json = json_encode($return_value);
    if ($_DEBUG) {
        echo "<hr>$return_value_json<hr>";
        print_r($return_value);
    } else {
        header('Content-Type: application/json');
        echo $return_value_json;
    }
    exit;
}

//
if ($_DEBUG) {
    echo "<br>Node ID: $node_id";
    echo "<br>Language: $language";
    echo "<br>Search string: $search_keyword";
    echo "<br>page_num: $page_num";
    echo "<br>page_size: $page_size";
    echo '<hr>';
}

$node_tree_obj = new NodeTree($user_db, $password_db, $name_db, $server_db, $port_db);
//$node_tree_obj->setDebug(true); // se messo a true, va degli echo a video riferiti alla classe NodeTree, tipo mostra a video la sql
//$node_tree_obj->setDebugDB(true); // se messo a true, va degli echo a video riferiti alla classe PDODB, tipo errore sql


$tree_array = $node_tree_obj->getTreeFromNodeID($node_id,$language,$search_keyword);
//$tree_array = $node_tree_obj->getTreeFromNodeID(5,'italian');

if ($tree_array == -1) {
    // errore durante la query
    $return_value['error_no'] = 1099;
    $return_value['error'] = "Errore durante l'interrogazione del database";
    $return_value_json = json_encode($return_value);
    if ($_DEBUG) {
        echo "<hr>$return_value_json<hr>";
        print_r($return_value);
    } else {
        header('Content-Type: application/json');
        echo  json_encode($return_value_json);
    }
    exit;    
}

// assegno alla mia struttura dati, l'array che contiene i nodi
$return_value['nodes'] = $tree_array;

// trasformo la struttura dati in string json
$return_value_json = json_encode($return_value);

if ($_DEBUG) {
    // stampo a video
    echo '<pre>' . print_r($return_value,true) . '</pre>';
    echo $return_value_json;
} else {
    header('Content-Type: application/json');
    echo  json_encode($return_value_json);
}

exit;
     
?>
