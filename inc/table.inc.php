<?php
session_start();
if(!isset($_SESSION['currentUser'])){
    exit();
}
//Ouput table as json and validate input
require_once('./../lib.php');
$response = new stdClass();
if(!isset($_POST['type'])){
    $response->error = 'No type provided';
} else {
    $type = $_POST['type'];
    if($type === 'Balance'){
        $response->data = Balances_all();
    } elseif($type === 'Accounting In'){
        $response->data = Accounting_in_all();
    } elseif($type === 'Accounting Out'){
        $response->data = Accounting_out_all();
    } elseif($type === 'Petty Cash ID'){
        $response->data = Petty_cash_id_all();
    } elseif($type == 'Petty Cash Type'){
        $response->data = Petty_cash_type_all();
    } else {
        $response->error = 'Invalid type provided';
    }
}
echo(json_encode($response));