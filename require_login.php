<?php
session_start();
if(!isset($_SESSION['currentUser'])){
    include('./login.php');
    exit();
}