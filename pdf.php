<?php
include("./require_login.php");
require('./tcpdf/tcpdf.php');
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

$pdf->AddPage('L',"A4");
//validate get and include the related php file
$filename = 'pdf';
if(isset($_GET['t'])){
    $t = $_GET['t'];
    if($t === 'a'){
        include('./inc/pdf_accounting.inc.php');
    } elseif($t === 'p'){
        include('./inc/pdf_pettycash.inc.php');
    } elseif($t === 'y'){
        include('./inc/pdf_yearoverview.inc.php');
    }
}
$pdf->Output($filename.'.pdf', 'D');
