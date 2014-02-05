<?php

class mDbConn {
    
    private $con = null;
    
    public function __construct() {
        $this->con = new mysqli('localhost', 'root', 'z41nul', 'kjks_mawar_simo');
    }
    
    public function runQuery($query) {
        if($this->con->query($query)){
            return true;
        } else {
            return false;
        }
    }

    public function getError() {
//        $db = new mysqli('localhost', 'root', 'z41nul', 'kjks_mawar_simo');
        return $this->con->error;
    }

    public function fetchQuery($query) {
//        $db = new mysqli('localhost', 'root', 'z41nul', 'kjks_mawar_simo');
        $res = $this->con->query($query);
        $data = array();
        while ($row = $res->fetch_assoc()) {
            array_push($data, $row);
        }
        return $data;
    }
}
