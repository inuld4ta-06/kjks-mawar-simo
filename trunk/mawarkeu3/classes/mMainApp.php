<?php

class mMainApp extends Login{

    private function fetchQuery($param) {
        $dbcon = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$dbcon->connect_errno) {

            // database query, getting all the info of the selected user
            $qryres = $dbcon->query($param);

            // if this user exists
            if ($qryres->num_rows == 1) {

                // get result row (as an object)
                $result_row = $qryres->fetch_object();
                
                return $result_row;

            } else {
                return array("No Data.");
            }
        } else {
            return array("Database connection problem.");
        }
    }

    public function getStudentList($param) {
        
    }

}
