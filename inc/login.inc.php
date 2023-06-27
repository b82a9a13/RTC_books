<?php
//login form validation
require_once('./../lib.php');

$returnText = new stdClass();
$errorArray = [];
$array = [];
if(isset($_POST['username'])){
    $array[0] = $_POST['username'];
    if(!preg_match("/^[a-zA-Z0-9]*/", $array[0])){
        array_push($errorArray, ['username', 'Username:'.preg_replace("/[a-zA-Z0-9]/",'', $array[0])]);
    } elseif(empty($array[0])){
        array_push($errorArray, ['username', 'Username=input required']);
    } elseif(strlen($array[0]) > 255){
        array_push($errorArray, ['username', 'Username=character limit is 255']);
    }
}
if(isset($_POST['password'])){
    $array[1] = $_POST['password'];
    if(strlen($array[1]) > 255){
        array_push($errorArray, ['password', 'Password=character limit is 255']);
    } elseif(strlen($array[1]) < 9){
        array_push($errorArray, ['password', 'Password=minimum 9 characters']);
    }
}
$name = "/^[a-zA-Z]*$/";
$nameR = "/[a-zA-Z]/";
if(isset($_POST['firstname'])){
    $array[2] = $_POST['firstname'];
    if(!preg_match($name,$array[2])){
        array_push($errorArray, ['firstname', 'Firstname:'.preg_replace($nameR,'', $array[2])]);
    } elseif(empty($array[2])){
        array_push($errorArray, ['firstname', 'Firstname=input required']);
    } elseif(strlen($array[2]) > 255){
        array_push($errorArray, ['firstname', 'Firstname=character limit is 255']);    
    }
}
if(isset($_POST['lastname'])){
    $array[3] = $_POST['lastname'];
    if(!preg_match($name, $array[3])){
        array_push($errorArray, ['lastname', 'Lastname:'.preg_replace($nameR,'', $array[3])]);
    } elseif(empty($array[3])){
        array_push($errorArray, ['lastname', 'Lastname=input required']);
    } elseif(strlen($array[3]) > 255){
        array_push($errorArray, ['lastname', 'Lastname=character limit is 255']);    
    }
}
if(isset($_POST['email'])){
    $array[4] = $_POST['email'];
    if(empty($array[4])){
        array_push($errorArray, ['email', 'Email=input required']);
    } elseif(!filter_var($array[4], FILTER_VALIDATE_EMAIL)){
        array_push($errorArray, ['email', 'Email=Invalid email']);
    }
}
if($errorArray != []){
    $returnText->error = $errorArray;
} else {
    if(count($array) == 2){
        if(login_user($array)){
            session_start();
            $_SESSION['currentUser'] = true;
            $returnText->return = true;
        } else {
            $returnText->return = false;
        }
    } else {
        $array[1] = password_hash($array[1], PASSWORD_DEFAULT);
        if(create_user($array)){
            session_start();
            $_SESSION['currentUser'] = true;
            $returnText->return = true;
        } else {
            $returnText->return = false;
        }
    }
}
echo(json_encode($returnText));