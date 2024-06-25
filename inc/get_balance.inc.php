<?php
if(session_status() !== PHP_SESSION_ACTIVE){session_start();}
if(!isset($_SESSION['currentUser'])){
    exit();
}
//Get balance from database and validate input
require_once('./../lib.php');
$response = new stdClass();
if(!isset($_POST['type']) || !isset($_POST['year'])){
    $response->error = 'Missing Required Value(s)';
} elseif(isset($_POST['type']) && isset($_POST['year'])){
    $type = $_POST['type'];
    $year = $_POST['year'];
    if(!preg_match("/^[0-9]*/", $year)){
        $response->error = 'Invalid year value(s): '.preg_replace("/[0-9]/", $year);
    } elseif($type != 'Bank Balance' && $type != 'Petty Cash'){
        $response->error = 'Invalid type provided.';
    } elseif($year > date("Y")){
        $response->error = 'The year provided is higher than the current year.';
    } elseif($year < 2023){
        $response->error = 'The year provided is lower than 2023.';
    }else{
        $response->data = get_balance($type, $year);
    }
}
echo(json_encode($response));