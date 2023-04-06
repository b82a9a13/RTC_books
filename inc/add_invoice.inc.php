<?php
require_once('./../lib.php');

if(isset($_POST['date']) && isset($_POST['supplier']) && isset($_POST['reference']) && isset($_POST['total'])){
    $date = $_POST['date'];
    $supplier = $_POST['supplier'];
    $reference = $_POST['reference'];
    $total = $_POST['total'];
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
    if(!empty($supplier) && !preg_match("/^[a-z A-Z]*$/", $supplier) || empty($supplier)){
        $error = true;
        $errors['supplier'] = true;
    }
    if(!empty($reference) && !preg_match("/^[0-9]*$/", $reference) || empty($reference)){
        $error = true;
        $errors['reference'] = true;
    }
    if(!empty($total) && !preg_match("/^[0-9 .]*$/", $total) || empty($total)){
        $error = true;
        $errors['total'] = true;
    }
    if($error === false){
        add_invoice($date, $supplier, $reference, $total);
        $success['success'] = true;
        echo(json_encode($success));
    } elseif($error === true){
        echo(json_encode($errors));
    }
}