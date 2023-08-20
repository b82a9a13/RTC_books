<?php
if(!isset($_SESSION)){
    session_start();
}
if(!isset($_SESSION['currentUser'])){
    exit();
}
//Output year overview dependant on year
if(isset($_POST['year'])){
    $year = $_POST['year'];
    if(!preg_match("/^[0-9]*$/", $year) || empty($year)){
        $pdf->writeHTML('<p>Invalid year</p>', true, false, false, false, '');
    } else {
        require_once('./lib.php');
        //Accounting Tables
        $pdf->Cell(0,0,"Accounting ".$year." - ".$year+1,0,0,'C',0,'',0);
        $pdf->Ln();
        $html = '<table border="1" cellpadding="2"><thead><tr>
            <th><b>Year & Month</b></th>
            <th><b>National Insurance Tax</b></th>
            <th><b>Tool Hire</b></th>
            <th><b>Drawings</b></th>
            <th><b>Petty Cash</b></th>
            <th><b>Travel & Motor exp</b></th>
            <th><b>Phone</b></th>
            <th><b>Protective Clothing</b></th>
            <th><b>Material</b></th>
            <th><b>Sundry</b></th>
        </tr></thead><tbody>';
        $acMonthlyData = get_ac_yearoverview_ym_data($year);
        $nIT = 0;
        $tH = 0;
        $d = 0;
        $pC = 0;
        $tME = 0;
        $p = 0;
        $pCl = 0;
        $m = 0;
        $s = 0;
        foreach($acMonthlyData as $acmd){
            $html .= '<tr>';
            $html .= '<td>'.$acmd[0].'</td>';
            $html .= (isset($acmd[1]['National Insurance'])) ? '<td>£'.$acmd[1]['National Insurance'].'</td>' : '<td></td>';
            $nIT += (isset($acmd[1]['National Insurance'])) ? $acmd[1]['National Insurance'] : 0;
            $html .= (isset($acmd[1]['Tool Hire'])) ? '<td>£'.$acmd[1]['Tool Hire'].'</td>' : '<td></td>';
            $tH += (isset($acmd[1]['Tool Hire'])) ? $acmd[1]['Tool Hire'] : 0;
            $html .= (isset($acmd[1]['Drawings'])) ? '<td>£'.$acmd[1]['Drawings'].'</td>' : '<td></td>';
            $d += (isset($acmd[1]['Drawings'])) ? $acmd[1]['Drawings'] : 0;
            $html .= (isset($acmd[1]['Petty Cash'])) ? '<td>£'.$acmd[1]['Petty Cash'].'</td>' : '<td></td>';
            $pC += (isset($acmd[1]['Petty Cash'])) ? $acmd[1]['Petty Cash'] : 0;
            $html .= (isset($acmd[1]['Travel and Motor'])) ? '<td>£'.$acmd[1]['Travel and Motor'].'</td>' : '<td></td>';
            $tME += (isset($acmd[1]['Travel and Motor'])) ? $acmd[1]['Travel and Motor'] : 0;
            $html .= (isset($acmd[1]['Phone'])) ? '<td>£'.$acmd[1]['Phone'].'</td>' : '<td></td>';
            $p += (isset($acmd[1]['Phone'])) ? $acmd[1]['Phone'] : 0;
            $html .= (isset($acmd[1]['Protective Clothing'])) ? '<td>£'.$acmd[1]['Protective Clothing'].'</td>' : '<td></td>';
            $pCl += (isset($acmd[1]['Protective Clothing'])) ? $acmd[1]['Protective Clothing'] : 0;
            $html .= (isset($acmd[1]['Material'])) ? '<td>£'.$acmd[1]['Material'].'</td>' : '<td></td>';
            $m += (isset($acmd[1]['Material'])) ? $acmd[1]['Material'] : 0;
            $html .= (isset($acmd[1]['Sundry'])) ? '<td>£'.$acmd[1]['Sundry'].'</td>' : '<td></td>';
            $s += (isset($acmd[1]['Sundry'])) ? $acmd[1]['Sundry'] : 0;
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, false, false, '');

        $html = '<table border="1" cellpadding="2"><thead><tr>
            <th><b>Total</b></th>
            <th><b>National Insurance Tax (Total)</b></th>
            <th><b>Tool Hire (Total)</b></th>
            <th><b>Drawings (Total)</b></th>
            <th><b>Petty Cash (Total)</b></th>
            <th><b>Travel & Motor exp (Total)</b></th>
            <th><b>Phone (Total)</b></th>
            <th><b>Protective Clothing (Total)</b></th>
            <th><b>Material (Total)</b></th>
            <th><b>Sundry (Total)</b></th>
        </tr></thead><tbody><tr>';
        $html .= '<td>£'.($nIT + $tH + $d + $pC + $tME + $p + $pCl + $m + $s).'</td>';
        $html .= ($nIT > 0) ? '<td>£'.$nIT.'</td>' : '<td></td>';
        $html .= ($tH > 0) ? '<td>£'.$tH.'</td>' : '<td></td>';
        $html .= ($d > 0) ? '<td>£'.$d.'</td>' : '<td></td>';
        $html .= ($pC > 0) ? '<td>£'.$pC.'</td>' : '<td></td>';
        $html .= ($tME > 0) ? '<td>£'.$tME.'</td>' : '<td></td>';
        $html .= ($p > 0) ? '<td>£'.$p.'</td>' : '<td></td>';
        $html .= ($pCl > 0) ? '<td>£'.$pCl.'</td>' : '<td></td>';
        $html .= ($m > 0) ? '<td>£'.$m.'</td>' : '<td></td>';
        $html .= ($s > 0) ? '<td>£'.$s.'</td>' : '<td></td>';
        $html .= '</tr></tbody></table>';
        $pdf->writeHTML($html, true, false, false, false, '');
        
        //Petty Cash Tables
        $pdf->AddPage();
        $pdf->Cell(0,0,"Petty Cash ".$year." - ".$year+1,0,0,'C',0,'',0);
        $pdf->Ln();
        $html = '<table border="1" cellpadding="2"><thead><tr>
            <th><b>Year & Month</b></th>
            <th><b>Material</b></th>
            <th><b>Phone</b></th>
            <th><b>Stationary & Phone</b></th>
            <th><b>Tools</b></th>
            <th><b>Sundry</b></th>
            <th><b>Work Clothing</b></th>
            <th><b>Tool Hire</b></th>
        </tr></thead><tbody>';
        $pcMonthlyData = get_pc_yearoverview_ym_data($year);
        $material = 0;
        $phone = 0;
        $statandprint = 0;
        $tools = 0;
        $sundry = 0;
        $workclothing = 0;
        $toolhire = 0;
        foreach($pcMonthlyData as $pcmd){
            $html .= '<tr><td>'.$pcmd[0].'</td>';
            $html .= (isset($pcmd[1]['Material'])) ? '<td>£'.$pcmd[1]['Material'].'</td>' : '<td></td>';
            $material += (isset($pcmd[1]['Material'])) ? $pcmd[1]['Material'] : 0;
            $html .= (isset($pcmd[1]['Phone'])) ? '<td>£'.$pcmd[1]['Phone'].'</td>' : '<td></td>';
            $phone += (isset($pcmd[1]['Phone'])) ? $pcmd[1]['Phone'] : 0;
            $html .= (isset($pcmd[1]['Stationary and Printing'])) ? '<td>£'.$pcmd[1]['Stationary and Printing'].'</td>' : '<td></td>';
            $statandprint += (isset($pcmd[1]['Stationary and Printing'])) ? $pcmd[1]['Stationary and Printing'] : 0;
            $html .= (isset($pcmd[1]['Tools'])) ? '<td>£'.$pcmd[1]['Tools'].'</td>' : '<td></td>';
            $tools += (isset($pcmd[1]['Tools'])) ? $pcmd[1]['Tools'] : 0;
            $html .= (isset($pcmd[1]['Sundry'])) ? '<td>£'.$pcmd[1]['Sundry'].'</td>' : '<td></td>';
            $sundry += (isset($pcmd[1]['Sundry'])) ? $pcmd[1]['Sundry'] : 0;
            $html .= (isset($pcmd[1]['Work Clothing'])) ? '<td>£'.$pcmd[1]['Work Clothing'].'</td>' : '<td></td>';
            $workclothing += (isset($pcmd[1]['Work Clothing'])) ? $pcmd[1]['Work Clothing'] : 0;
            $html .= (isset($pcmd[1]['Tool Hire'])) ? '<td>£'.$pcmd[1]['Tool Hire'].'</td>' : '<td></td>';
            $toolhire += (isset($pcmd[1]['Tool Hire'])) ? $pcmd[1]['Tool Hire'] : 0;
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        
        $pdf->writeHTML($html, true, false, false, false, '');

        $html = '<table border="1" cellpadding="2"><thead><tr>
            <th><b>Total</b></th>
            <th><b>Material (Total)</b></th>
            <th><b>Phone (Total)</b></th>
            <th><b>Stationary & Phone (Total)</b></th>
            <th><b>Tools (Total)</b></th>
            <th><b>Sundry (Total)</b></th>
            <th><b>Work Clothing (Total)</b></th>
            <th><b>Tool Hire (Total)</b></th>
        </tr></thead><tbody><tr>';
        $html .= '<td>£'.($material + $phone + $statandprint + $tools + $sundry + $workclothing + $toolhire).'</td>';
        $html .= ($material > 0) ? '<td>£'.$material.'</td>' : '<td></td>';
        $html .= ($phone > 0) ? '<td>£'.$phone.'</td>' : '<td></td>';
        $html .= ($statandprint > 0) ? '<td>£'.$statandprint.'</td>' : '<td></td>';
        $html .= ($tools > 0) ? '<td>£'.$tools.'</td>' : '<td></td>';
        $html .= ($sundry > 0) ? '<td>£'.$sundry.'</td>' : '<td></td>';
        $html .= ($workclothing > 0) ? '<td>£'.$workclothing.'</td>' : '<td></td>';
        $html .= ($toolhire > 0) ? '<td>£'.$toolhire.'</td>' : '<td></td>';
        $html .= '</tr></tbody></table>';
        $pdf->writeHTML($html, true, false, false, false, '');
        $filename = 'End Of Year '.$year.' - '.$year+1.;
    }
}