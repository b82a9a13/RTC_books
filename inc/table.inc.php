<?php
//Ouput table as json and validate input
require_once('./../lib.php');
if(isset($_POST['type'])){
    $type = $_POST['type'];
    if($type === 'Balance'){
        echo(json_encode(Balances_all()));
    } elseif($type === 'Accounting In'){
        echo(json_encode(Accounting_in_all()));
    } elseif($type === 'Accounting Out'){
        echo(json_encode(Accounting_out_all()));
    } elseif($type === 'Petty Cash ID'){
        echo(json_encode(Petty_cash_id_all()));
    } elseif($type == 'Petty Cash Type'){
        echo(json_encode(Petty_cash_type_all()));
    }
}