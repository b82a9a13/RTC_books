<?php
if(session_status() !== PHP_SESSION_ACTIVE){session_start();}
if(!isset($_SESSION['currentUser'])){
    exit();
}
//Title date for pdf and start/end dates
if($month > 3){
    $month = new DateTime('2022-'.$month.'-1');
} else {
    $month = new DateTime('2023-'.$month.'-1');
}
$start = $month->format('Y-m-d');
$pdf->Cell(0,0,"From:".$month->format('d/m/Y')." To:".($month->modify('last day of this month'))->format('d/m/Y'),0,0,'C',0,'',0);
$end = $month->format('Y-m-d');
require_once('./lib.php');
?>