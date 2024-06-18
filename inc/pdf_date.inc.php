<?php
if(session_status() !== PHP_SESSION_ACTIVE){session_start();}
if(!isset($_SESSION['currentUser'])){
    exit();
}
//Title date for pdf and start/end dates
if(isset($_POST['year'])){
    $year = $_POST['year'];
    if(!preg_match("/^[0-9]*$/", $year) || empty($year)){
        $pdf->writeHTML('<p>Invalid year</p>', true, false, false, false, '');
    } elseif($year < 2023 || $year > date("Y")){
        $pdf->writeHTML('<p>Invalid year</p>', true, false, false, false, '');
    }else {
        if($month > 3){
            $month = new DateTime("$year-$month-1");
        } else {
            $month = new DateTime(($year+1)."-$month-1");
        }
        $start = $month->format('Y-m-d');
        $pdf->Cell(0,0,"From:".$month->format('d/m/Y')." To:".($month->modify('last day of this month'))->format('d/m/Y'),0,0,'C',0,'',0);
        $end = $month->format('Y-m-d');
        require_once('./lib.php');
    }
}
?>