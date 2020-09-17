<?php

class PDODB {

    private $link = null;
    private static $inst = null;
    private $debug = 0;

    public function __construct($username, $pwd, $db, $host, $port) {
        try {
            $this->link = new PDO("mysql:host=$host;prot=$port;dbname=$db", $username, $pwd,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="TRADITIONAL"') );
            $this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            if ($this->debug) {
                echo 'Unable to connect to database -> error: ' . $e->getMessage();
            } else {
                exit('Unable to connect to database -> error: ' . $e->getMessage());
            } 
        }
    }

    public function __destruct() {
        if ($this->link) {
            $this->link = null;
        }
    }

    public function setDebug($d) {
        $this->debug = $d;
    }
    
    static function getInstance($username, $pwd, $db, $host, $port) {
        if (self::$inst == null) {
            self::$inst = new PDODB($username, $pwd, $db, $host, $port);
        }
        return self::$inst;
    }

    public function disconnect() {
        $this->link = null;
    }

    public function querySelect($sql) {
        try {
            $stmt = $this->link->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $ex) {
            // magari scrivo in un file, per ora faccio un echo in fase di debug, in produzione ritorno -1
            if ($this->debug) {
                echo "An Error has occurred -> " . $ex->getMessage();
            }
            return -1;
        }
    }

}
