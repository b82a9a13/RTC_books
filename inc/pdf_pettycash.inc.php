<?php
if(!isset($_SESSION)){
    session_start();
}
if(!isset($_SESSION['currentUser'])){
    exit();
}
//Output petty cash pdf dependant on month
if(isset($_POST['month'])){
    $month = $_POST['month'];
    if(!preg_match("/^[0-9]*$/", $month) || empty($month)){
        $pdf->writeHTML('<p>Invalid month</p>', true, false, false, false, '');
    } else {
        include('./inc/pdf_date.inc.php');
        $pdf->Ln();
        $html = '<table border="1" cellpadding="2"><thead><tr>
            <th width="100px">Date</th>
            <th width="100px">Item</th>
            <th width="75px">Reference</th>
            <th width="75px">Total</th>
        </tr></thead><tbody>';
        $pettycashIn = get_pettycash_in_month($start, $end);
        $refId = [];
        foreach($pettycashIn as $pcIn){
            $html .= '<tr><td width="100px">'.$pcIn[0].'</td><td width="100px">'.$pcIn[1].'</td><td width="75px">'.$pcIn[2].'</td><td width="75px">£'.$pcIn[3].'</td></tr>';
            array_push($refId, $pcIn[2]);
        }
        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, false, false, '');
        $html = '<table border="1" cellpadding="2"><thead><tr>
            <th width="75px">Material</th>
            <th width="75px">Phone</th>
            <th width="75px">Postage</th>
            <th width="75px">Stationary & Printing</th>
            <th width="75px">Tools</th>
            <th width="75px">Sundry</th>
            <th width="75px">Work Clothing</th>
            <th width="75px">Tool Hire</th>
        </tr></thead><tbody>';
        $pettycashType = get_pettycash_in_type($refId);
        $totalOut = 0;
        foreach($pettycashType as $pcType){
            $html .= '<tr>';
            $position = 0;
            if($pcType[1] == 'Material'){
                $position = 1;
            } elseif($pcType[1] == 'Phone'){
                $position = 2;
            } elseif($pcType[1] == 'Postage'){
                $position = 3;
            } elseif($pcType[1] == 'Stationary and Printing'){
                $position = 4;
            } elseif($pcType[1] == 'Tools'){
                $position = 5;
            } elseif($pcType[1] == 'Sundry'){
                $position = 6;
            } elseif($pcType[1] == 'Work Clothing'){
                $position = 7;
            } elseif($pcType[1] == 'Tool Hire'){
                $position = 8;
            }
            for($i = 1; $i <= 8; $i++){
                if($position === $i){
                    $totalOut += $pcType[2];
                    $html .= '<td width="75px">£'.$pcType[2].'</td>';
                } else {
                    $html .= '<td width="75px"></td>';
                }
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, false, false, '');

        $html = '<table border="1" cellpadding="2"><thead><tr>
            <th width="75px">Total Out</th>
            <th width="150px">Previous Month Remaining</th>
            <th width="75px">Total Left</th>
        </tr></thead><tbody>';
        $balanceVals = get_pettycash_in_before($start);
        $html .= '<tr><td width="75px">£'.$totalOut.'</td><td width="150px">£'.$balanceVals.'</td><td width="75px">£'.$balanceVals-$totalOut.'</td></tr></tbody></table>';
        $pdf->writeHTML($html, true, false, false, false, '');

        $html = '<table border="1" cellpadding="2"><thead><tr>
            <th width="75px">Accounting ID</th>
            <th width="75px">Date</th>
            <th width="75px">Amount In</th>
            <th width="75px">C, F</th>
        </tr></thead><tbody>';
        $pettycashInA = get_pettycash_into_month($start, $end);
        if(!empty($pettycashInA)){
            foreach($pettycashInA as $pcInA){
                $html .= '<tr><td width="75px">'.$pcInA[0].'</td><td width="75px">'.$pcInA[1].'</td><td width="75px">£'.$pcInA[2].'</td><td width="75px">£'.(($balanceVals-$totalOut)+$pcInA[2]).'</td></tr>';
            }
        } else {
            $html .= '<tr><td width="75px"></td><td width="75px"></td><td width="75px"></td><td width="75px">£'.$balanceVals-$totalOut.'</td></tr>';
        }
        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, false, false, '');
        $filename = 'Petty Cash '.$month->format('m-Y');
    }
}
?>