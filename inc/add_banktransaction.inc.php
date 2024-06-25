<?php
if(session_status() !== PHP_SESSION_ACTIVE){session_start();}
if(!isset($_SESSION['currentUser'])){
    exit();
}
//Add a bank transaction to the database and validate input
require_once('./../lib.php');
if(isset($_POST['date']) && isset($_POST['supplier']) && isset($_POST['total']) && isset($_POST['type'])){
    $date = $_POST['date'];
    $supplier = $_POST['supplier'];
    $total = $_POST['total'];
    $type = $_POST['type'];
    $response = [];
    if(!empty($date)){
        $date = strtotime($date);
        if(!preg_match("/^[0-9]*$/", $date) || empty($date)){
            $response['date'] = 'Invalid date';
        }
    } else {
        $response['date'] = 'No date';
    }
    if(empty($supplier)){
        $response['supplier'] = 'No supplier';
    } elseif(!empty($supplier) && !preg_match("/^[a-z A-Z0-9]*$/", $supplier)){
        $response['supplier'] = 'Invalid supplier values: '.preg_replace("/[a-z A-Z0-9]/",'',$supplier);
    }
    if(empty($total)){
        $response['total'] = 'No total';
    } elseif(!empty($total) && !preg_match("/^[0-9.]*$/", $total)){
        $response['total'] = 'Invalid total values: '.preg_replace("/[0-9.]/",'',$total);
    }
    $typeOpt = ['National Insurance','Tool Hire','Drawings','Petty Cash','Travel and Motor Exp','Phone','Protective Clothing','Material','Sundry'];
    if(!empty($type) && !in_array($type, $typeOpt) || empty($type)){
        $response['type'] = 'Invalid type';
    }
    if($response === []){
        $return = add_banktransaction($date, $supplier, $total, $type);
        if($return === 'Success'){
            $response['success'] = true;
        } else {
            $response['error'] = $return;
        }
        echo json_encode($response);
    } elseif($response != []){
        echo json_encode($response);
    }
}