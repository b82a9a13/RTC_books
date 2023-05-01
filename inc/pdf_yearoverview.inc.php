<?php
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
        </tr></thead><tbody>';
        $html .= '</tbody></table>';
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
        </tr></thead><tbody>';
        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, false, false, '');
    }
}