<?php
//Output accounting pdf dependant on month
if(isset($_POST['month'])){
    $month = $_POST['month'];
    if(!preg_match("/^[0-9]*$/", $month) || empty($month)){
        $pdf->writeHTML('<p>Invalid month<p>', true, false, false, false, '');
    } else {
        include('./inc/pdf_date.inc.php');
        $accounting = get_accounting_in_month($start, $end);
        $pdf->Ln();
        $html = '<table border="1" cellpadding="2"><thead><tr>
            <th width="20px">ID</th>
            <th width="100px">Date</th>
            <th width="100px">Supplier</th>
            <th width="75px">Reference</th>
            <th width="75px">Total</th>
        </tr></thead><tbody>';
        $totalAccountIn = 0;
        foreach($accounting as $account){
            $html .= '<tr><td width="20px">'.$account[0].'</td><td width="100px">'.$account[1].'</td><td width="100px">'.$account[2].'</td><td width="75px">'.$account[3].'</td><td width="75px">£'.$account[4].'</td></tr>';
            $totalAccountIn += $account[4];
        }
        $html .= '</tbody></table>';
        $totalCF = get_accounting_cf_month($start, $end);
        $totalInAccount = $totalCF+$totalAccountIn;
        $html .= '<br><br><table border="1" cellpadding="2">
            <thead>
                <tr>
                    <th width="100px">C,F</th>
                    <th width="100px">Total</th>
                    <th width="100px">Total In</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td width="100px">£'.$totalCF.'</td>
                    <td width="100px">£'.$totalAccountIn.'</td>
                    <td width="100px">£'.$totalInAccount.'</td>
                </th>   
            </tbody>
        </table>';
    
        $pdf->writeHTML($html, true, false, false, false, '');
        $accountingOut = get_accounting_out_month($start, $end);
        $html = '<table border="1" cellpadding="2"><thead><tr>
            <th colspan="1">ID</th>
            <th colspan="2">Date</th>
            <th colspan="2">Supplier</th>
            <th colspan="1">Total</th>
            <th colspan="1">Month ID</th>
            <th colspan="1">National Insurance</th>
            <th colspan="1">Tool Hire</th>
            <th colspan="1">Drawings</th>
            <th colspan="1">Petty Cash</th>
            <th colspan="1">Travel & Motor Exp</th>
            <th colspan="1">Phone</th>
            <th colspan="1">Protective Clothing</th>
            <th colspan="1">Material</th>
            <th colspan="1">Sundry</th>
        </tr></thead><tbody>';
        $int = 1;
        $totals = new stdClass();
        $totals->NationalInsurance = 0;
        $totals->ToolHire = 0;
        $totals->Drawings = 0;
        $totals->PettyCash = 0;
        $totals->TravelAndMotorExp = 0;
        $totals->Phone = 0;
        $totals->ProtectiveClothing = 0;
        $totals->Material = 0;
        $totals->Sundry = 0;
        $totals->TotalValue = 0;
        foreach($accountingOut as $acc){
            $html .= '<tr>';
            for($i = 0; $i < 4; $i++){
                if(!empty($acc[$i])){
                    if($i == 0){
                        $html .= '<td colspan="1">'.$acc[$i].'</td>';
                    } elseif($i == 3) {
                        $html .= '<td colspan="1">£'.$acc[$i].'</td>';
                        $totals->TotalValue += $acc[$i];
                    } else {
                        $html .= '<td colspan="2">'.$acc[$i].'</td>';
                    }
                } else {
                    if($i == 0){
                        $html .= '<td colspan="1"></td>';
                    } elseif($i == 3){
                        $html .= '<td colspan="1">£</td>';
                    } else {
                        $html .= '<td colspan="1"></td>';
                    }
                }
            }
            $html .= '<td colspan="1">'.$int.'</td>';
            $int++;
            $position = 0;
            if($acc[4] == 'National Insurance'){
                $position = 1;
                $totals->NationalInsurance += $acc[3];
            } elseif($acc[4] == 'Tool Hire'){
                $position = 2;
                $totals->ToolHire += $acc[3];
            } elseif($acc[4] == 'Drawings'){
                $position = 3;
                $totals->Drawings += $acc[3];
            } elseif($acc[4] == 'Petty Cash'){
                $position = 4;
                $totals->PettyCash += $acc[3];
            } elseif($acc[4] == 'Travel & Motor exp'){
                $position = 5;
                $totals->TravelAndMotorExp += $acc[3];
            } elseif($acc[4] == 'Phone'){
                $position = 6;
                $totals->Phone += $acc[3];
            } elseif($acc[4] == 'Protective Clothing'){
                $position = 7;
                $totals->ProtectiveClothing += $acc[3];
            } elseif($acc[4] == 'Material'){
                $position = 8;
                $totals->Material += $acc[3];
            } elseif($acc[4] == 'Sundry'){
                $position = 9;
                $totals->Sundry += $acc[3];
            }
            for($i = 1; $i < 10; $i++){
                if($position === $i){
                    $html .= '<td colspan="1">£'.$acc[3].'</td>';
                } else {
                    $html .= '<td></td>';
                }
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, false, false, '');
        $html = '<table border="1" cellpadding="2"><thead><tr>
            <th colspan="5">Totals</th>
            <th colspan="1">£'.$totals->TotalValue.'</th>
            <th colspan="1"></th>
        ';
        if($totals->NationalInsurance > 0){
            $html .= '<th colspan="1">£'.$totals->NationalInsurance.'</th>';
        } else {
            $html .= '<th colspan="1"></th>';
        }
        if($totals->ToolHire > 0){
            $html .= '<th colspan="1">£'.$totals->ToolHire.'</th>';
        } else {
            $html .= '<th colspan="1"></th>';
        }
        if($totals->Drawings > 0){
            $html .= '<th colspan="1">£'.$totals->Drawings.'</th>';
        } else {
            $html .= '<th colspan="1"></th>';
        }
        if($totals->PettyCash > 0){
            $html .= '<th colspan="1">£'.$totals->PettyCash.'</th>';
        } else {
            $html .= '<th colspan="1"></th>';
        }
        if($totals->TravelAndMotorExp > 0){
            $html .= '<th colspan="1">£'.$totals->TravelAndMotorExp.'</th>';
        } else {
            $html .= '<th colspan="1"></th>';
        }
        if($totals->Phone > 0){
            $html .= '<th colspan="1">£'.$totals->Phone.'</th>';
        } else {
            $html .= '<th colspan="1"></th>';
        }
        if($totals->ProtectiveClothing > 0){
            $html .= '<th colspan="1">£'.$totals->ProtectiveClothing.'</th>';
        } else {
            $html .= '<th colspan="1"></th>';
        }
        if($totals->Material > 0){
            $html .= '<th colspan="1">£'.$totals->Material.'</th>';
        } else {
            $html .= '<th colspan="1"></th>';
        }
        if($totals->Sundry > 0){
            $html .= '<th colspan="1">£'.$totals->Sundry.'</th>';
        } else {
            $html .= '<th colspan="1"></th>';
        }
        $html .= '</tr></thead></table><br><br>';
        $pdf->writeHTML($html, false, false, false, false, '');
        $html = '<table border="1" cellpadding="2"><thead><tr>
            <th width="100px">Remaining</th>
            <th width="100px">£'.($totalInAccount - $totals->TotalValue).'</th>
        </tr></thead></table>';
        $pdf->writeHTML($html, false, false, false, false, '');
    }
}
?>