<?php
if(session_status() !== PHP_SESSION_ACTIVE){session_start();}
if(!isset($_SESSION['currentUser'])){
    exit();
}
//Add balance to the database, update the balance if it is still 2 months after the current tax year and if it isn't reject the update. Validate the input
require_once('./../lib.php');
$response = new stdClass();
if(!isset($_POST['type']) || !isset($_POST['year']) || !isset($_POST['balance'])){
    $response->error = 'Missing Required Value(s)';
} elseif(isset($_POST['type']) && isset($_POST['year']) && isset($_POST['balance'])){
    $type = $_POST['type'];
    $year = $_POST['year'];
    $balance = $_POST['balance'];
    if(!preg_match("/^[0-9]*/", $year)){
        $response->error = 'Invalid year value(s): '.preg_replace("/[0-9]/", '',$year);
    } elseif($type != 'Bank Balance' && $type != 'Petty Cash'){
        $response->error = 'Invalid type provided.';
    } elseif(!preg_match("/^[0-9.]*$/", $balance)){
        $response->error = 'Invalid balance value(s):'.preg_replace("/[0-9.]/", $balance);
    } elseif($year > date("Y")){
        $response->error = 'The year provided is higher than the current year.';
    } elseif($year < 2023){
        $response->error = 'The year provided is lower than 2023.';
    }else{
        $return = add_balance($type, $year, $balance);
        if($return === 'Success'){
            $response->success = $return;
        } else {
            $response->error = $return;
        }
    }
}
echo json_encode($response);