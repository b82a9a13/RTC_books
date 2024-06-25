<?php
if(session_status() !== PHP_SESSION_ACTIVE){session_start();}
if(!isset($_SESSION['currentUser'])){
    exit();
}
//Add reciept to database and validate input
require_once('./../lib.php');
if(isset($_POST['date']) && isset($_POST['item']) && isset($_POST['total']) && isset($_POST['type'])){
    $date = $_POST['date'];
    $item = $_POST['item'];
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
    if(empty($item)){
        $response['item'] = 'No item';
    } elseif(!empty($item) && !preg_match("/^[a-z A-Z 0-9]*$/", $item)){
        $response['item'] = 'Invalid item values: '.preg_replace("/[a-z A-Z0-9]/",'',$item);
    }
    if(empty($total)){
        $response['total'] = 'No Total';
    } elseif(!empty($total) && !preg_match("/^[0-9 .]*$/", $total)){
        $response['total'] = 'Invalid total values: '.preg_replace("/[0-9 .]/",'',$total);
    }
    $typeOpt = ['Material','Phone','Postage','Stationary and Printing','Tools','Sundry','Work Clothing','Tool Hire'];
    if(!empty($type) && !in_array($type, $typeOpt) || empty($type)){
        $response['type'] = 'Invalid type';
    }
    if($response === []){
        $return = add_receipt($date, $item, $total, $type);
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