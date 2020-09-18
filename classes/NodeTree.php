<?php
/*
 * Classe che si occupa di preparare le sql e formatta i dati in un array
 */

require 'classes/PDODB.php';

class NodeTree {
    private $db_obj = null;
    private $debug = false;
    
    public function __construct($user_db, $password_db, $name_db, $server_db, $port_db) {
        $this->db_obj = PDODB::getInstance($user_db, $password_db, $name_db, $server_db, $port_db);
    }
    
    public function setDebug($d = true) {
        $this->debug = $d;
    }

    public function setDebugDB($d = true) {
        $this->db_obj->setDebug($d);
    }
    
    /*
     funzione che mi seleziona tutti i nodi presenti dato un node_id e una lingua
     se passata anche una stringa, questa viene utilizzata dalla funzione privata che formatta i dati prelevati dal DB
     */
    
    public function getTreeFromNodeID($node_id, $language,$search_str = null) {    
        
        // preparo la query
        $sql = "
            SELECT  n.idNode,ntn.NodeName as name,n.level
            FROM node_tree AS n, node_tree AS p
            JOIN node_tree_names AS ntn
            WHERE  n.idNode = ntn.idNode
            AND p.idNode = $node_id 
            AND (n.iLeft BETWEEN p.iLeft AND p.iRight) 
            AND language = '$language' 
            GROUP by name
            ORDER BY n.iLeft;            
                "; 
        
        if ($this->debug) {
            echo "<br>getTreeFromNodeID: $sql<br>";
        }
        $result = $this->db_obj->querySelect($sql);
        // verifico se ho avuto un errore durante la sql
        if ($result != -1) {
            // tutto bene, posso costruire l'albero con i valori da ritornare
            $tree_array = $this->createTree($result, $search_str);
            return $tree_array;
        } else {
            // errore sql, a livello 
            return -1;
        }
    }
             
    private function createTree($result, $search_str) {
            $tree = array();
            $level_old = -1;
            $i = 0;
            $parent_index = array();

            foreach($result as $row) {
                if ($level_old > -1 && $i > 0) {
                    if ($row->level > $level_old) {
                        // visto che sono salito di livello,allora mi trovo nel figlio del nodo precedente
                        array_push($parent_index, $i-1); // aggiungo il riferimento del padre ad un array che mi tiene il riferimento dell'indice
                        $tree[$i-1]['children_count']++; // aumento il contatore del child
                        //echo "## TROVATO FIGLIO level >  PADRE i=$parent_i: " . json_encode($tree[$parent_i]) . "<br>";
                    } else if ($row->level == $level_old) {
                        // sono in un fratello del nodo precedente visto che il livello e' uguale al nodo precedente
                        $parent_i = end($parent_index); // prelevo l'indice del padre
                        $tree[$parent_i]['children_count']++; // aumento il contantore
                        //echo "## TROVATO FRATELLO level ==  PADRE i=$parent_i: " . json_encode($tree[$i-1]) . "<br>";
                    } else {
                        // siamo nel caso in cui i figli sono finiti, quindi ritorno su di un livello
                        array_pop($parent_index);// tolgo l'ultimo riferimento del padre
                        $parent_i = end($parent_index); // prelevo l'indice del padre
                        $tree[$parent_i]['children_count']++;// aumento il contantore
                        //echo "##STIAMO RISALENDO level <  PADRE i=$parent_i " . json_encode($tree[$parent_i]) . "<br>";
                    }
                }
                if ($search_str == null || strripos($row->name, $search_str) !== false) {
                    $tree[$i] = array('node_id' => $row->idNode,'name' => $row->name, 'level' => $row->level, 'children_count' => 0);
                    $i++;
                }
                $level_old = $row->level;

            }
                      
        return $tree;
    }    
        
}
