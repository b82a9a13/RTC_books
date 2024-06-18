<?php
if(session_status() !== PHP_SESSION_ACTIVE){session_start();}
if(!isset($_SESSION['currentUser'])){
    exit();
}
require_once('./../lib.php');
$response = new stdClass();
if(!isset($_POST['t'])){
    $response->error = 'No type provided';
} else {
    $type = $_POST['t'];
    if(!in_array($type, ['bank', 'petty'])){
        $response->error = 'Invalid type provided';
    } else {
        switch($type){
            case 'bank':
                $totalOutput = total_money_output();
                $totalInput = total_money_input();
                $response->data = [$totalInput, $totalOutput, number_format((initial_bank_balance()+$totalInput)-$totalOutput, 2)];
                break;
            case 'petty':
                $totalOutput = get_pettycash_out();
                $totalInput = get_pettycash_in();
                $response->data = [$totalInput, $totalOutput, number_format((initial_pettycash_balance()+$totalInput)-$totalOutput, 2)];
                break;
        }
    }
}
echo(json_encode($response));