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
    if($type === 'Balance' || $type === 'Accounting In' || $type === 'Accounting Out' || $type === 'Petty Cash ID' || $type == 'Petty Cash Type'){
        $data = 'No Data';
        switch ($type){
            case 'Balance':
                $data = Balances_all();
                break;
            case 'Accounting In':
                $data = Accounting_in_all();
                break;
            case 'Accounting Out':
                $data = Accounting_out_all();
                break;
            case 'Petty Cash ID':
                $data = Petty_cash_id_all();
                break;
            case 'Petty Cash Type':
                $data = Petty_cash_type_all();
                break;
        }
        if(is_string($data)){
            $response->error = $data;
        } else {
            $response->data = $data;
        }
    } else {
        $response->error = 'Invalid type provided';
    }
}
echo(json_encode($response));