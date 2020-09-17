<?php

class Tools {

    static public function checkGETValue($k, $params, $type) {
        global $_DEBUG;
        
        $err = 0;
        $v = $_GET[$k];
        
        if ($_DEBUG) {
            echo "<div>Chiave = $k, Valore: $v, Tipo: $type</div>";
        }
        
        switch ($type) {
            case 'i':
                $k = (int) $k; // forzo 
                if (!is_numeric($v) || ($v < $params['validation'][0]) || ($v > $params['validation'][1])) {
                    $err = 11;
                    //echo filter_input(INPUT_GET, 'node_js', FILTER_VALIDATE_INT);
                }
                break;
            case 's':
                // tipo string
                if (!preg_match($params['validation'], $v)) {
                    $err = 12;
                    break;
                }
                break;
            case 'e':
                // tipo enum
                $found = 0;
                foreach ($params['validation'] as $enum_value) {
                    if ($_DEBUG) {
                        echo "<br>$v == $enum_value";
                    }
                    if ($enum_value === strtolower($v)) {
                        $found = 1;
                        break;
                    }
                }
                if ($found == 0) {
                    $err = 13;
                }
                break;
            default:
                $err = 9;
                $msg_err = "Errore generico nella configurazione, contattaci al info@0brand.com";
                break;
        }
        return $err;
    }
/*
    static public function getValueGET($key, $defaultValue = false) {
        return (isset($_GET[$key]) ? $_GET[$key] : $defaultValue);
    }
*/
}

?>