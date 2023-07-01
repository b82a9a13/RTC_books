<?php
session_start();
if(!isset($_SESSION['currentUser'])){
    exit();
}
$returnText = new stdClass();
session_start();
if($_SESSION['currentUser']){
    unset($_SESSION['currentUser']);
    $returnText->return = true;
} else {
    $returnText->return = false;
}
echo(json_encode($returnText));