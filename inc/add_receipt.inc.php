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
    $errors = [];
    if(!empty($date)){
        $date = strtotime($date);
        if(!preg_match("/^[0-9]*$/", $date) || empty($date)){
            $errors['date'] = 'Invalid date';
        }
    } else {
        $errors['date'] = 'No date';
    }
    if(empty($item)){
        $errors['item'] = 'No item';
    } elseif(!empty($item) && !preg_match("/^[a-z A-Z 0-9]*$/", $item)){
        $errors['item'] = 'Invalid item values: '.preg_replace("/[a-z A-Z0-9]/",'',$item);
    }
    if(empty($total)){
        $errors['total'] = 'No Total';
    } elseif(!empty($total) && !preg_match("/^[0-9 .]*$/", $total)){
        $errors['total'] = 'Invalid total values: '.preg_replace("/[0-9 .]/",'',$total);
    }
    $typeOpt = ['Material','Phone','Postage','Stationary and Printing','Tools','Sundry','Work Clothing','Tool Hire'];
    if(!empty($type) && !in_array($type, $typeOpt) || empty($type)){
        $errors['type'] = 'Invalid type';
    }
    if($errors === []){
        add_receipt($date, $item, $total, $type);
        $success['success'] = true;
        echo(json_encode($success));
    } elseif($errors != []){
        echo(json_encode($errors));
    }
}