<?php
//Function which must contain the database, username, password and db name for it to work
function db_connect(){
    $database = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'books';
    return new mysqli($database, $username, $password, $dbname);
}

//Used to query a table and return an array
function query_table($table, $params){
    $connection = db_connect();
    if($connection->connect_error){
        return "connection error";
    } else{ 
        $sql = "SELECT * FROM ".$table;
        $result = $connection->query($sql);
        $array = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $tempoary = [];
                foreach($params as $param){
                    array_push($tempoary, $row[$param]);
                }
                array_push($array, $tempoary);
            }
        }
        $connection->close();
        return $array;
    }
}

//Get all Data from Balances Table
function Balances_all(){
    return query_table('balances', ["ID","Type","Date","Balance"]);
}

//Get all data in accounting in table
function Accounting_in_all(){
    return query_table('accounting_in', ['ID','Date','Supplier','Reference','Total']);
}


//Get all data in accounting out table
function Accounting_out_all(){
    return query_table('accounting_out', ['ID','Date','Supplier','Total','Type']);
}


//Get all data in petty cash id table
function Petty_cash_id_all(){
    return query_table('petty_cash_id', ['ReferenceID','Date','Item','Total']);
}


//Get all data in petty cash type table
function Petty_cash_type_all(){
    return query_table('petty_cash_type', ['ID', 'ReferenceID', 'Type', 'Total']);
}

//Adds a invoice to the database
function add_invoice($date, $supplier, $reference, $total){
    $connection = db_connect();
    $date = date('Y-m-d', $date);
    if($connection->connect_error){
        return "connection error";
    } else{ 
        $sql = "SELECT * FROM accounting_in WHERE Date='".$date."' AND Supplier='".$supplier."' AND Reference='".$reference."'AND Total='".$total."'";
        $result = $connection->query($sql);
        if($result->num_rows === 0){
            $sql = "SELECT MAX(ID) AS ID FROM accounting_in";
            $result = $connection->query($sql);
            $looped = false;
            $rowresult = '';
            while($row = $result->fetch_assoc()){
                if($looped == false){
                    $rowresult = $row['ID'] + 1;
                    $looped = true;
                }
            }
            $sql = "INSERT INTO accounting_in (ID, Date, Supplier, Reference, Total) VALUES ('".$rowresult."','".$date."','".$supplier."','".$reference."','".$total."')";
            $result = $connection->query($sql);
            $connection->close();
            return "success";
        } else {
            $connection->close();
            return "exists";
        }
    }
}

//Get the total amount put in
function total_money_input(){
    $connection = db_connect();
    if($connection->connect_error){
        return "connection error";
    } else{ 
        $sql = "SELECT SUM(Total) as Total FROM accounting_in";
        $result = $connection->query($sql);
        $looped = false;
        while($row = $result->fetch_assoc()){
            if($looped == false){
                $rowresult = $row['Total'];
                $looped = true;
            }
        }
        $connection->close();
        return $rowresult;
    }
}

//Get the total amount outputted
function total_money_output(){
    $connection = db_connect();
    if($connection->connect_error){
        return "connection error";
    } else{ 
        $sql = "SELECT SUM(Total) as Total FROM accounting_out";
        $result = $connection->query($sql);
        $looped = false;
        while($row = $result->fetch_assoc()){
            if($looped == false){
                $rowresult = $row['Total'];
                $looped = true;
            }
        }
        $connection->close();
        return $rowresult;
    }
}

//Output Initial Bank Balance
function initial_bank_balance(){
    $connection = db_connect();
    if($connection->connect_error){
        return "connection error";
    } else{ 
        $sql = "SELECT Balance FROM balances WHERE Type='Bank Balance' AND ID='2'";
        $result = $connection->query($sql);
        $looped = false;
        while($row = $result->fetch_assoc()){
            if($looped == false){
                $rowresult = $row['Balance'];
                $looped = true;
            }
        }
        $connection->close();
        return $rowresult;
    }
}

//Get accounting in for the month
function get_accounting_in_month($start, $end){
    $connection = db_connect();
    if($connection->connect_error){
        return "connection error";
    } else {
        $array = [];
        $sql = "SELECT * FROM accounting_in WHERE Date >= '".$start."' AND Date <= '".$end."'";
        $result = $connection->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array,[$row['ID'],date('d/m/Y',strtotime($row['Date'])),$row['Supplier'],$row['Reference'],$row['Total']]);
            }
        }
        $connection->close();
        return $array;
    }
}

//Get accounting out for the month
function get_accounting_out_month($start, $end){
    $connection = db_connect();
    if($connection->connect_error){
        return "connection error";
    } else {
        $array = [];
        $sql = "SELECT * FROM accounting_out WHERE Date >= '".$start."' AND Date <= '".$end."'";
        $result = $connection->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, [$row['ID'], date('d/m/Y',strtotime($row['Date'])), $row['Supplier'], $row['Total'], $row['Type']]);
            }
        }
        $connection->close();
        return $array;
    }
}

//Get accounting carry forward for the month
function get_accounting_cf_month($start, $end){
    $connection = db_connect();
    if($connection->connect_error){
        return "connection error";
    } else {
        if($start < '2022-04-05'){
            $sql = "SELECT Balance FROM balances WHERE ID = '2'";
            $result = $connection->query($sql);
            $total = 0;
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $total += $row['Balance'];
                }
            }
            $connection->close();
            return $total;
        } else {
            $total = 0;
            $sql = "SELECT Total FROM accounting_in WHERE Date < '".$start."'";
            $result = $connection->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $total += $row['Total'];
                }
            }
            $sql = "SELECT Total FROM accounting_out WHERE Date < '".$start."'";
            $result = $connection->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $total -= $row['Total'];
                }
            }
            $sql = "SELECT Balance FROM balances WHERE ID = '2'";
            $result = $connection->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $total += $row['Balance'];
                }
            }
            $connection->close();
            return $total;
        }
    }
}

//Get Total petty cash in
function get_pettycash_in(){
    $connection = db_connect();
    if($connection->connect_error){
        return "connection error";
    } else{ 
        $total = 0;
        $sql = "SELECT Total FROM accounting_out WHERE Type = 'Petty Cash'";
        $result = $connection->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $total += $row['Total'];
            }
        }
        $connection->close();
        return $total;
    }
}

//Get Total petty cash out
function get_pettycash_out(){
    $connection = db_connect();
    if($connection->connect_error){
        return "connection error";
    } else{ 
        $total = 0;
        $sql = "SELECT Total FROM petty_cash_id";
        $result = $connection->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $total += $row['Total'];
            }
        }
        $connection->close();
        return $total;
    }
}

//Get initial petty cash balance
function initial_pettycash_balance(){
    $connection = db_connect();
    if($connection->connect_error){
        return "connection error";
    } else{ 
        $sql = "SELECT Balance FROM balances WHERE Type='Petty Cash' AND ID='1'";
        $result = $connection->query($sql);
        $looped = false;
        while($row = $result->fetch_assoc()){
            if($looped == false){
                $rowresult = $row['Balance'];
                $looped = true;
            }
        }
        $connection->close();
        return $rowresult;
    }
}

//Add receipt to database
function add_receipt($date, $item, $total, $type){
    $connection = db_connect();
    $date = date('Y-m-d', $date);
    if($connection->connect_error){
        return "connection error";
    } else{ 
        $sql = "SELECT * FROM petty_cash_id INNER JOIN petty_cash_type ON petty_cash_id.ReferenceID = petty_cash_type.ReferenceID WHERE petty_cash_id.Date = '".$date."' AND petty_cash_id.Item = '".$item."' AND petty_cash_id.Total = '".$total."' AND petty_cash_type.Type = '".$type."'";
        $result = $connection->query($sql);
        $rowresult = '';
        if($result->num_rows === 0){
            $sql = "SELECT MAX(ID) AS ID FROM petty_cash_type";
            $result = $connection->query($sql);
            while($row = $result->fetch_assoc()){
                $rowresult = $row['ID'] + 1;
            }
            $sql = "INSERT INTO petty_cash_id (ReferenceID, Date, Item, Total) VALUES ('".$rowresult."','".$date."','".$item."','".$total."')";
            $result = $connection->query($sql);
            $sql = "INSERT INTO petty_cash_type (ID, ReferenceID, Type, Total) VALUES ('".$rowresult."','".$rowresult."','".$type."','".$total."')";
            $result = $connection->query($sql);
            $connection->close();
            return "success";
        } else {
            $connection->close();
            return "exists";
        }
    }
}

//Add bank transaction to database
function add_banktransaction($date, $supplier, $total, $type){
    $connection = db_connect();
    $date = date('Y-m-d', $date);
    if($connection->connect_error){
        return "connection error";
    } else{ 
        $sql = "SELECT * FROM accounting_out WHERE Date = '".$date."' AND Supplier = '".$supplier."' AND Total = '".$total."' AND Type = '".$type."'";
        $result = $connection->query($sql);
        $rowresult = '';
        if($result->num_rows === 0){
            $sql = "SELECT MAX(ID) AS ID FROM accounting_out";
            $result = $connection->query($sql);
            while($row = $result->fetch_assoc()){
                $rowresult = $row['ID']+1;
            }
            $sql = "INSERT INTO accounting_out (ID, Date, Supplier, Total, Type) VALUES ('".$rowresult."','".$date."','".$supplier."','".$total."','".$type."')";
            $result = $connection->query($sql);
            $connection->close();
            return "success";
        } else {
            $connection->close();
            return "exists";
        }
    }
}

//Get petty cash in for the month
function get_pettycash_in_month($start, $end){
    $connection = db_connect();
    if($connection->connect_error){
        return "connection error";
    } else{
        $array = [];
        $sql = "SELECT * FROM petty_cash_id WHERE Date >= '".$start."' AND Date <= '".$end."'";
        $result = $connection->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, [date('d/m/Y',strtotime($row['Date'])), $row['Item'], $row['ReferenceID'],  $row['Total']]);
            }
        }
        $connection->close();
        return $array;
    }
}

//Gett petty cash in type for the values provided
function get_pettycash_in_type($values){
    $connection = db_connect();
    if($connection->connect_error){
        return "connection error";
    } else{ 
        $array = [];
        $sql = "SELECT * FROM petty_cash_type WHERE ReferenceID IN (";
        $first = true;
        foreach($values as $value){
            if($first === true){
                $sql .= "'".$value."'";
                $first = false;
            } else {
                $sql .= ",'".$value."'";
            }
        }
        $sql .= ")";
        $result = $connection->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, [$row["ReferenceID"], $row["Type"], $row["Total"]]);
            }
        }
        $connection->close();
        return $array;
    }
}

//Get petty cash in from accounting for the month
function get_pettycash_into_month($start, $end){
    $connection = db_connect();
    if($connection->connect_error){
        return "connection error";
    } else{ 
        $array = [];
        $sql = "SELECT ID, Date, Total FROM accounting_out WHERE TYPE = 'Petty Cash' AND Date >= '".$start."' AND Date <= '".$end."'";
        $result = $connection->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, [$row['ID'], date('d/m/Y',strtotime($row['Date'])), $row['Total']]);
            }
        }
        $connection->close();
        return $array;
    }
}

//Get petty cash in from accounting before the provided date
function get_pettycash_in_before($date){
    $connection = db_connect();
    if($connection->connect_error){
        return "connection error";
    } else{ 
        $balance = 0;
        $sql = "SELECT Balance FROM balances WHERE Type = 'Petty Cash'";
        $result = $connection->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $balance = $row['Balance'];
            }
        }
        $totalIn = 0;
        $sql = "SELECT Total FROM accounting_out WHERE Type = 'Petty Cash' AND Date < '".$date."'";
        $result = $connection->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $totalIn += $row['Total'];
            }
        }
        $totalOut = 0;
        $sql = "SELECT Total FROM petty_cash_id WHERE Date < '".$date."'";
        $result = $connection->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $totalOut += $row['Total'];
            }
        }
        $connection->close();
        return (($balance + $totalIn) - $totalOut);
    }
}

//Get year overview data for Year & Month table in year overview pdf (Petty Cash)
function get_pc_yearoverview_ym_data($year){
    $connection = db_connect();
    if($connection->connect_error){
        return "connection error";
    } else{ 
        $finArray = [];
        $m = 4;
        $next = false;
        for($i = 0; $i < 12; $i++){
            $tm1 = "04";
            $tm2 = "05";
            if($m == 13){
                $m = 1;
                $next = true;
            }
            $ty1 = ($next) ? $year+1 : $year;
            $ty2 = ($next) ? $year+1 : $year;
            if($m < 9){
                $tm1 = "0".$m;               
                $tm2 = "0".$m+1;
            } elseif($m === 9){
                $tm1 = "0".$m;
                $tm2 = "10";
            } elseif($m === 12){
                $tm1 = $m;
                $tm2 = '01';
                $ty2 = $year+1;
            } elseif($m > 9){
                $tm1 = $m;
                $tm2 = $m+1;
            }
            $sql = "SELECT petty_cash_id.Total, petty_cash_type.Type FROM petty_cash_id INNER JOIN petty_cash_type ON petty_cash_id.ReferenceID = petty_cash_type.ReferenceID WHERE petty_cash_id.Date >= '".$ty1."-".$tm1."-01' AND petty_cash_id.Date < '".$ty2."-".$tm2."-01'";
            $result = $connection->query($sql);
            $array = [];
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if(!isset($array[$row['Type']])){
                        $array[$row['Type']] = 0;
                    }
                    $array[$row['Type']] += $row['Total'];
                }
            }
            array_push($finArray, [$ty1."-".$tm1,$array]);
            $m++;
        }
        return $finArray;
    }
}

//Get end of year for Year & Month table in the year overview pdf (Accounting)
function get_ac_yearoverview_ym_data($year){
    $connection = db_connect();
    if($connection->connect_error){
        return "connection error";
    } else{ 
        $finArray = [];
        $m = 4;
        $next = false;
        for($i = 0; $i < 12; $i++){
            $tm1 = "04";
            $tm2 = "05";
            if($m == 13){
                $m = 1;
                $next = true;
            }
            $ty1 = ($next) ? $year+1 : $year;
            $ty2 = ($next) ? $year+1 : $year;
            if($m < 9){
                $tm1 = "0".$m;               
                $tm2 = "0".$m+1;
            } elseif($m === 9){
                $tm1 = "0".$m;
                $tm2 = "10";
            } elseif($m === 12){
                $tm1 = $m;
                $tm2 = '01';
                $ty2 = $year+1;
            } elseif($m > 9){
                $tm1 = $m;
                $tm2 = $m+1;
            }
            $sql = "SELECT Total, Type FROM accounting_out WHERE Date >= '".$ty1."-".$tm1."-01' AND Date < '".$ty2."-".$tm2."-01'";
            $result = $connection->query($sql);
            $array = [];
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if(!isset($array[$row['Type']])){
                        $array[$row['Type']] = 0;
                    }
                    $array[$row['Type']] += $row['Total'];
                }
            }
            array_push($finArray, [$ty1."-".$tm1,$array]);
            $m++;
        }
        return $finArray;
    }
}

//Check if a user account exists
function user_exists(){
    $connection = db_connect();
    if($connection->connect_error){
        return "connection error";
    } else{ 
        $sql = 'SELECT * FROM user';
        $result = $connection->query($sql);
        if($result->num_rows > 0){
            $connection->close();
            return true;
        } else {
            $connection->close();
            return false;
        }
    }
}

//Create a new user
function create_user($array){
    if(user_exists() == false){
        $database = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'books';
        $connection = new mysqli($database, $username, $password, $dbname);
        if($connection->connect_error){
            return false;
        } else{ 
            $sql = "INSERT INTO user (username, password, firstname, lastname, email) VALUES ('".$array[0]."','".$array[1]."','".$array[2]."','".$array[3]."','".$array[4]."')";
            $result = $connection->query($sql);
            $connection->close();
            return true;
        }
    } else {
        return false;
    }
}

//Login a existing user
function login_user($array){
    if(user_exists() == true){
        $database = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'books';
        $connection = new mysqli($database, $username, $password, $dbname);
        if($connection->connect_error){
            return false;
        } else{ 
            $sql = 'SELECT password FROM user WHERE username = "'.$array[0].'"';
            $result = $connection->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if(password_verify($array[1], $row['password'])){
                        $connection->close();
                        return true;
                    } else {
                        $connection->close();
                        return false;
                    }
                }
                return true;
            } else {
                $connection->close();
                return false;
            }
        }
    } else {
        return false;
    }
}