<?php
//Output petty cash pdf dependant on month
if(isset($_POST['month'])){
    $month = $_POST['month'];
    if(!preg_match("/^[0-9]*$/", $month) || empty($month)){
        $pdf->writeHTML('<p>Invalid month<p>', true, false, false, false, '');
    } else {
        include('./inc/pdf_date.inc.php');
    }
}
?>