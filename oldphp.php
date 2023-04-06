<?php
        //GEt all temp accounting out data and put into new table
        $accounting_array = accounting_temp_out();
        $dataInput = [];
        $string = '
            <table class="table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Supplier</th>
                        <th>Total</th>
                        <th>National Insurance</th>
                        <th>Tool Hire</th>
                        <th>Drawings</th>
                        <th>Petty Cash</th>
                        <th>Travel and Motor</th>
                        <th>Phone</th>
                        <th>Protective Clothing</th>
                        <th>Material</th>
                        <th>Sundry</th>
                    </tr>
                </thead>
                <tbody>
        ';
        foreach($accounting_array as $acarr){
            $string .= '
                <tr>
                    <td>'.$acarr[0].'</td>
                    <td>'.$acarr[1].'</td>
                    <td>'.$acarr[2].'</td>
                    <td>'.$acarr[3].'</td>
                    <td>'.$acarr[4].'</td>
                    <td>'.$acarr[5].'</td>
                    <td>'.$acarr[6].'</td>
                    <td>'.$acarr[7].'</td>
                    <td>'.$acarr[8].'</td>
                    <td>'.$acarr[9].'</td>
                    <td>'.$acarr[10].'</td>
                    <td>'.$acarr[11].'</td>
                    <td>'.$acarr[12].'</td>
                </tr>
            ';
            $temparr = [];
            if($acarr[4] > 0){
                $temparr = ['National Insurance', $acarr[4]];
            } elseif($acarr[5] > 0){
                $temparr = ['Tool Hire', $acarr[5]];
            } elseif($acarr[6] > 0){
                $temparr = ['Drawings', $acarr[6]];
            } elseif($acarr[7] > 0){
                $temparr = ['Petty Cash', $acarr[7]];
            } elseif($acarr[8] > 0){
                $temparr = ['Travel and Motor', $acarr[8]];
            } elseif($acarr[9] > 0){
                $temparr = ['Phone', $acarr[9]];
            } elseif($acarr[10] > 0){
                $temparr = ['Protective Clothing', $acarr[10]];
            } elseif($acarr[11] > 0){
                $temparr = ['Material', $acarr[11]];
            } elseif($acarr[12] > 0){
                $temparr = ['Sundry', $acarr[12]];
            }
            array_push($dataInput, [$acarr[0], $acarr[1], $acarr[2], $acarr[3], $temparr[0]]);
        }
        $string .= '</tbody></table>';
        print_r($dataInput);
        echo($string);

        $database = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'books';
        $connection = new mysqli($database, $username, $password, $dbname);
        if($connection->connect_error){
            return "connection error";
        } else{ 
            foreach($dataInput as $dataIn){
                //$sql = "INSERT INTO accounting_out (ID, Date, Supplier, Total, Type) VALUES (1, 2022-10-10, 'wafeafg', 17, 'wagewasg')";
                $sql = "INSERT INTO accounting_out (ID, Date, Supplier, Total, Type) VALUES (".$dataIn[0].",".$dataIn[1].",'".$dataIn[2]."',".$dataIn[3].",'".$dataIn[4]."')";
                echo($sql);
                if($connection->query($sql) === true){
                    echo("new record for ".$dataIn[0]);
                } else {
                    echo("error for ".$dataIn[0]);
                }
            }
        }
        $connection->close();

        
//Get all data from Temp Accounting Out Table
function accounting_temp_out(){
    $database = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'books';
    $connection = new mysqli($database, $username, $password, $dbname);
    if($connection->connect_error){
        return "Connection error";
    } else {
        $sql = 'SELECT * FROM accounting_out_temp';
        $result = $connection->query($sql);
        $array = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, [$row['ID'], $row['Date'], $row['Supplier'], $row['Total'], $row['National Insurance'], $row['Tool Hire'], $row['Drawings'], $row['Petty Cash'], $row['Travel and Motor'], $row['Phone'], $row['Protective Clothing'], $row['Material'], $row['Sundry']]);
            }
        }
        $connection->close();
        return $array;
    }
}



        //Get all Data from Balances Table
        $database = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'books';
        $connection = new mysqli($database, $username, $password, $dbname);
        if($connection->connect_error){
            return "connection error";
        } else{ 
            $sql = 'SELECT * FROM temp_cash_type';
            $result = $connection->query($sql);
            $array = [];
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    array_push($array, [$row['ID'], $row['ReferenceID'], $row['Material'], $row['Phone'], $row['Postage'], $row['Stationary'], $row['Tools'], $row['Sundry'], $row['Work Clothing'], $row['Tool Hire']]);
                }
            }
            print_r($array);
            $string = '
                <table class="table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ReferenceID</th>
                            <th>Material</th>
                            <th>Phone</th>
                            <th>Postage</th>
                            <th>Stationary</th>
                            <th>Tools</th>
                            <th>Sundry</th>
                            <th>Work Clothing</th>
                            <th>Tool Hire</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
            $dataInput = [];
            foreach($array as $arr){
                $string .= '
                    <tr>
                        <td>'.$arr[0].'</td>
                        <td>'.$arr[1].'</td>
                        <td>'.$arr[2].'</td>
                        <td>'.$arr[3].'</td>
                        <td>'.$arr[4].'</td>
                        <td>'.$arr[5].'</td>
                        <td>'.$arr[6].'</td>
                        <td>'.$arr[7].'</td>
                        <td>'.$arr[8].'</td>
                        <td>'.$arr[9].'</td>
                    </tr>
                ';
                $tempval = [];
                if($arr[2] > 0){
                    $tempval = ['Material', $arr[2]];
                } elseif($arr[3] > 0){
                    $tempval = ['Phone', $arr[3]];
                } elseif($arr[4] > 0){
                    $tempval = ['Postage', $arr[4]];
                } elseif($arr[5] > 0){
                    $tempval = ['Stationary', $arr[5]];
                } elseif($arr[6] > 0){
                    $tempval = ['Tools', $arr[6]];
                } elseif($arr[7] > 0){
                    $tempval = ['Sundry', $arr[7]];
                } elseif($arr[8] > 0){
                    $tempval = ['Work Clothing', $arr[8]];
                } elseif($arr[9] > 0){
                    $tempval = ['Tool Hire', $arr[9]];
                }
                array_push($dataInput, [$arr[0], $arr[1], $tempval[0], $tempval[1]]);
            }
            $string .= '</tbody></table>';
            echo($string);
            print_r($dataInput);
            foreach($dataInput as $dataIn){
                $sql = "INSERT INTO petty_cash_type (ID, ReferenceID, Type, Total) VALUES (".$dataIn[0].",".$dataIn[1].",'".$dataIn[2]."',".$dataIn[3].")";
                echo($sql);
                if($connection->query($sql) === true){
                    echo("true");
                } else {
                    echo("false");
                }
            }
        }
        $connection->close();