<?php
//Add reciept to database and validate input
require_once('./../lib.php');
if(isset($_POST['date']) && isset($_POST['item']) && isset($_POST['total']) && isset($_POST['type'])){
    $date = $_POST['date'];
    $item = $_POST['item'];
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
    if(!empty($item) && !preg_match("/^[a-z A-Z 0-9]*$/", $item) || empty($item)){
        $error = true;
        $errors['item'] = true;
    }
    if(!empty($total) && !preg_match("/^[0-9 .]*$/", $total) || empty($total)){
        $error = true;
        $errors['total'] = true;
    }
    $typeOpt = ['Material','Phone','Postage','Stationary and Printing','Tools','Sundry','Work Clothing','Tool Hire'];
    if(!empty($type) && !in_array($type, $typeOpt) || empty($type)){
        $error = true;
        $errors['type'] = true;
    }
    if($error === false){
        add_receipt($date, $item, $total, $type);
        $success['success'] = true;
        echo(json_encode($success));
    } elseif($error === true){
        echo(json_encode($errors));
    }
}