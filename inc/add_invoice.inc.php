<?php
if(session_status() !== PHP_SESSION_ACTIVE){session_start();}
if(!isset($_SESSION['currentUser'])){
    exit();
}
//Add invoice to data base and validate the input
require_once('./../lib.php');
if(isset($_POST['date']) && isset($_POST['supplier']) && isset($_POST['reference']) && isset($_POST['total'])){
    $date = $_POST['date'];
    $supplier = $_POST['supplier'];
    $reference = $_POST['reference'];
    $total = $_POST['total'];
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
    } elseif(!empty($supplier) && !preg_match("/^[a-z A-Z]*$/", $supplier)){
        $response['supplier'] = 'Invalid supplier values: '.preg_replace("/[a-z A-Z]/",'',$supplier);
    }
    if(empty($reference)){
        $response['reference'] = 'No reference';
    } elseif(!empty($reference) && !preg_match("/^[0-9]*$/", $reference)){
        $response['reference'] = 'Invalid reference values: '.preg_replace("/[0-9]/",'',$reference);
    }
    if(empty($total)){
        $response['total'] = 'No Total';
    } elseif(!empty($total) && !preg_match("/^[0-9.]*$/", $total) || empty($total)){
        $response['total'] = 'Invalid total values: '.preg_replace("/[0-9.]/",'',$total);
    }
    if($response === []){
        $return = add_invoice($date, $supplier, $reference, $total);
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