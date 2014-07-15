<?php
session_start();
$p = isset($_GET['p']) ? filter_input(INPUT_GET,'p', FILTER_SANITIZE_STRING) : '';
if($p == ''){
    die('page route is blank!');
} else {
    include_once dirname(__FILE__) . '/classes/mDbConn.php';
    include "page/$p.php";
}

function createUrl(){
    return "ajaxroute.php?p=" . filter_input(INPUT_GET,'p', FILTER_SANITIZE_STRING);
}