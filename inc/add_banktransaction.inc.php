<?php
require_once('./../lib.php');

if(isset($_POST['date']) && isset($_POST['supplier']) && isset($_POST['total']) && isset($_POST['type'])){
    $date = $_POST['date'];
    $supplier = $_POST['supplier'];
    $total = $_POST['total'];
    $type = $_POST['type'];
    $error = false;
    $errors = [];
    if(!empty($date)){
        $date = strtotime($date);
        if(!preg_match("/^[0-9]*$/", $date) || empty($date)){
            $error = true;
            $errors['date'] = true;
        }
    } else {
        $error = true;
        $errors['date'] = true;
    }
    if(!empty($supplier) && !preg_match("/^[a-z A-Z 0-9]*$/", $supplier) || empty($supplier)){
        $error = true;
        $errors['supplier'] = true;
    }
    if(!empty($total) && !preg_match("/^[0-9 .]*$/", $total) || empty($total)){
        $error = true;
        $errors['total'] = true;
    }
    $typeOpt = ['National Insurance','Tool Hire','Drawings','Petty Cash','Travel and Motor Exp','Phone','Protective Clothing','Material','Sundry'];
    if(!empty($type) && !in_array($type, $typeOpt) || empty($type)){
        $error = true;
        $errors['type'] = true;
    }
    if($error === false){
        add_banktransaction($date, $supplier, $total, $type);
        $success['success'] = true;
        echo(json_encode($success));
    } elseif($error === true){
        echo(json_encode($errors));
    }
}