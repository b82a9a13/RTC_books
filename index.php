<?php //Index for books page 
include("./require_login.php");
//Define the current year for the tax year
$taxYear = (date("n") >= 4) ? date("Y") : date("Y")-1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>
    <div class='text-center'>
        <button class='btn btn-primary' id="logout_btn">Log out</button>
    </div>
    <h1 class="text-center" id="page title"><?php echo $taxYear; ?> Books</h1>
    <?php //Section for selecting what years data to display for the page ?>
    <div class="section text-center">
        <h2 class="text-center">Year</h2>
        <input id="year input" type="number" min="2023" max="<?php echo $taxYear; ?>" id="year" name="year" value="<?php echo $taxYear; ?>" onchange="change_year()"><br>
    </div>
    <br>
    <?php //Section for displaying data in tables ?>
    <div id="tables" class="section">
        <h2 class="text-center">Tables</h2>
        <div class="text-center">
            <button id="balance" class="btn-primary btn table-btn" onclick="table('Balance',0)">Show Balance</button>
            <button id="accounting in" class="btn-primary btn table-btn" onclick="table('Accounting In',1)">Show Accounting In</button>
            <button id="accounting out" class="btn-primary btn table-btn" onclick="table('Accounting Out',2)">Show Accounting Out</button>
            <button id="petty cash id" class="btn-primary btn table-btn" onclick="table('Petty Cash ID',3)">Show Petty Cash ID</button>
            <button id="petty cash type" class="btn-primary btn table-btn" onclick="table('Petty Cash Type',4)">Show Petty Cash Type</button>
        </div>
        <h2 class='text-center' id="table_error" style='display:none;color:red;'></h2>
        <div id="table_div">

        </div>
    </div>
    <br>
    <?php //Section for inputting data into the database ?>
    <div id="input" class="section">
        <h2 class="text-center">Input</h2>
        <div class="text-center">
            <button id="add invoice" class="btn-primary btn" onclick="input('Add Invoice')">Add Invoice</button>
            <button id="add receipt" class="btn-primary btn" onclick="input('Add Receipt')">Add Receipt</button>
            <button id="add bank transaction" class="btn-primary btn" onclick="input('Add Bank Transaction')">Add Bank Transaction</button>
        </div><br>
        <div class="input-forms">
            <div class="input-form-div">
                <form action="" id="add invoice form" class="text-center input-form input-form-div-form">
                    <p class="text-center title-p">Add Invoice</p>                
                    <div class="input-div"><p class="input-p">Date:</p><input type="date" class="input-input" id="incoive_date"></div>
                    <div class="input-div"><p class="input-p">Supplier:</p><input type="text" class="input-input" id="invoice_supplier"></div>
                    <div class="input-div"><p class="input-p">Reference:</p><input type="number" class="input-input" id="invoice_reference"></div>
                    <div class="input-div"><p class="input-p">Total:</p><input type="number" class="input-input" id="invoice_total" step="0.01"></div>
                    <p style="display: none;font-size:25px;" id="invoice_form_error"></p>
                    <button type="submit" id="add invoice form submit" class="input-btn btn-primary btn">Submit</button>
                </form>
            </div>
            <div class="input-form-div">
                <form action="" id="add receipt form" class="input-form-div-form input-form text-center">
                    <p class="text-center title-p">Add Receipt</p>
                    <div class="input-div"><p class="input-p">Date:</p><input type="date" class="input-input" id="receipt_date"></div>
                    <div class="input-div"><p class="input-p">Item:</p><input type="text" class="input-input" id="receipt_item"></div>
                    <div class="input-div"><p class="input-p">Total:</p><input type="number" class="input-input" id="receipt_total" step="0.01"></div>
                    <div class="input-div"><p class="input-p">Type:</p>
                        <select id="receipt_type">
                            <option value="" selected disabled hidden>Choose a type</option>
                            <option value="Material">Material</option>
                            <option value="Phone">Phone</option>
                            <option value="Postage">Postage</option>
                            <option value="Stationary and Printing">Stationary and Printing</option>
                            <option value="Tools">Tools</option>
                            <option value="Sundry">Sundry</option>
                            <option value="Work Clothing">Work Clothing</option>
                            <option value="Tool Hire">Tool Hire</option>
                        </select>
                    </div>
                    <p style="display: none;font-size:25px;" id="receipt_form_error"></p>
                    <button type="submit" id="add receipt form submit" class="input-btn btn-primary btn">Submit</button>
                </form>
            </div>
            <div class="input-form-div">
                <form action="" id="add bank transaction form" class="input-form-div-form input-form text-center">
                    <p class="text-center title-p">Add Bank Transaction</p>
                    <div class="input-div"><p class="input-p">Date:</p><input type="date" class="input-input" id="banktransaction_date"></div>
                    <div class="input-div"><p class="input-p">Supplier:</p><input type="text" class="input-input" id="banktransaction_supplier"></div>
                    <div class="input-div"><p class="input-p">Total:</p><input type="number" step="0.01" class="input-input" id="banktransaction_total"></div>
                    <div class="input-div"><p class="input-p">Type:</p>
                        <select id="banktransaction_type">
                            <option value="" selected disabled hidden>Choose a type</option>
                            <option value="National Insurance">National Insurance</option>
                            <option value="Tool Hire">Tool Hire</option>
                            <option value="Drawings">Drawings</option>
                            <option value="Petty Cash">Petty Cash</option>
                            <option value="Travel and Motor Exp">Travel and Motor Exp</option>
                            <option value="Phone">Phone</option>
                            <option value="Protective Clothing">Protective Clothing</option>
                            <option value="Material">Material</option>
                            <option value="Sundry">Sundry</option>
                        </select>
                    </div>
                    <p style="display: none;font-size:25px;" id="banktransaction_form_error"></p>
                    <button type="submit" id="add bank transaction form submit" class="input-btn btn-primary btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <br>
    <?php //Section for displaying statistics for the year ?>
    <div id="statistics" class="section">
        <h2 class="text-center">Statistics</h2>
        <div class="inner-div">
            <div class="div-border" id="bank_stat_div">
                <p class="title-p">Bank</p>
                <p class="statistics-p">Total In: £
                    <span id="bank_stat_in">
                        <?php 
                            require_once('./lib.php');
                            $totalInput = total_money_input();
                            echo($totalInput);
                        ?>
                    </span>
                </p>
                <p class="statistics-p">Total Out: £
                    <span id="bank_stat_out">
                        <?php
                            $totalOutput = total_money_output();
                            echo($totalOutput);
                        ?>
                    </span>
                </p>
                <p class="statistics-p">Bank Balanace: £
                    <span id="bank_stat_bal">
                        <?php
                        echo((initial_bank_balance()+$totalInput)-$totalOutput);
                        ?>
                    </span>
                </p>
                <h2 class='text-center' style='display:none;color:red;' id="bank_stat_error"></h2>
            </div>
            <div class="div-border" id="petty_stat_div">
                <p class="title-p">Petty Cash</p>
                <p class="statistics-p">Total In: £
                    <span id="petty_stat_in">
                        <?php 
                        $totalPettycashInput = get_pettycash_in();
                        echo($totalPettycashInput);
                        ?>
                    </span>
                </p>
                <p class="statistics-p">Total Out: £
                    <span id="petty_stat_out">
                        <?php
                        $totalPettycashOutput = get_pettycash_out();
                        echo($totalPettycashOutput);
                        ?>
                    </span>
                </p>
                <p class="statistics-p">Petty Cash Balance: £
                    <span id="petty_stat_bal">
                        <?php 
                        echo((initial_pettycash_balance()+$totalPettycashInput)-$totalPettycashOutput);
                        ?>
                    </span>
                </p>
                <h2 class='text-center' style='display:none;color:red;' id="petty_stat_error"></h2>
            </div>
        </div>
    </div>
    <br>
    <?php //Section for outputting data into a PDF ?>
    <div id="output" class="section">
        <h2 class="text-center">Output</h2>
        <div class="inner-div">
            <div class="div-border">
                <p class="title-p">Accounting</p>
                <form action="./pdf.php?t=a" method="POST" class="output-form" id="a_form">
                    <?php 
                        //Create month selection and download, then output to page
                        $monthSelect = '<select name="month" id="month">';
                        $msInt = 1;
                        foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $msTxt){
                            $monthSelect .= '<option value="'.$msInt.'">'.$msTxt.'</option>';
                            $msInt++;
                        }
                        $monthSelect .= '</select><button type="submit" class="btn-primary btn">Download PDF</button>';
                        echo($monthSelect);
                    ?>
                    <input type="number" name="year" id="a_year" hidden>
                </form>
            </div>
            <div class="div-border">
                <p class="title-p">Petty Cash</p>
                <form action="./pdf.php?t=p" method="POST" class="output-form" id="p_form">
                    <?php 
                        echo($monthSelect);
                    ?>
                    <input type="number" name="year" id="p_year" hidden>
                </form>
            </div>
            <div class="div-border">
                <p class="title-p">Year Overview</p>
                <button type="button" class="btn-primary btn" onclick="output_pdf('y')">Download PDF</button>
            </div>
        </div>
    </div>
    <br>
    <?php //Section for adding the first bank balance and the first petty cash balance. It is also used to create the initial bank balance and petty cash balance for the year?>
    <div id="balances" class="section">
        <h2 class="text-center">Balances</h2>
        <div class="inner-div"></div>
    </div>
</body>
<?php //Include JavaScript ?>
<script src="./js/index.js"></script>
</html>