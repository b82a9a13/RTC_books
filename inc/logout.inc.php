<?php
if(!isset($_SESSION)){
    session_start();
}
if(!isset($_SESSION['currentUser'])){
    exit();
}
$returnText = new stdClass();
if($_SESSION['currentUser']){
    unset($_SESSION['currentUser']);
    $returnText->return = true;
} else {
    $returnText->return = false;
}
echo(json_encode($returnText));