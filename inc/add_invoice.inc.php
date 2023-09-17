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
    $errors = [];
    if(!empty($date)){
        $date = strtotime($date);
        if(!preg_match("/^[0-9]*$/", $date) || empty($date)){
            $errors['date'] = 'Invalid date';
        }
    } else {
        $errors['date'] = 'No date';
    }
    if(empty($supplier)){
        $errors['supplier'] = 'No supplier';
    } elseif(!empty($supplier) && !preg_match("/^[a-z A-Z]*$/", $supplier)){
        $errors['supplier'] = 'Invalid supplier values: '.preg_replace("/[a-z A-Z]/",'',$supplier);
    }
    if(empty($reference)){
        $errors['reference'] = 'No reference';
    } elseif(!empty($reference) && !preg_match("/^[0-9]*$/", $reference)){
        $errors['reference'] = 'Invalid reference values: '.preg_replace("/[0-9]/",'',$reference);
    }
    if(empty($total)){
        $errors['total'] = 'No Total';
    } elseif(!empty($total) && !preg_match("/^[0-9.]*$/", $total) || empty($total)){
        $errors['total'] = 'Invalid total values: '.preg_replace("/[0-9.]/",'',$total);
    }
    if($errors === []){
        add_invoice($date, $supplier, $reference, $total);
        $success['success'] = true;
        echo(json_encode($success));
    } elseif($errors != []){
        echo(json_encode($errors));
    }
}