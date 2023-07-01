<?php
session_start();
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
    } elseif(!empty($supplier) && !preg_match("/^[a-z A-Z0-9]*$/", $supplier)){
        $errors['supplier'] = 'Invalid supplier values: '.preg_replace("/[a-z A-Z0-9]/",'',$supplier);
    }
    if(empty($total)){
        $errors['total'] = 'No total';
    } elseif(!empty($total) && !preg_match("/^[0-9.]*$/", $total)){
        $errors['total'] = 'Invalid total values: '.preg_replace("/[0-9.]/",'',$total);
    }
    $typeOpt = ['National Insurance','Tool Hire','Drawings','Petty Cash','Travel and Motor Exp','Phone','Protective Clothing','Material','Sundry'];
    if(!empty($type) && !in_array($type, $typeOpt) || empty($type)){
        $errors['type'] = 'Invalid type';
    }
    if($errors === []){
        add_banktransaction($date, $supplier, $total, $type);
        $success['success'] = true;
        echo(json_encode($success));
    } elseif($errors != []){
        echo(json_encode($errors));
    }
}