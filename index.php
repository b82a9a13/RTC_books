<?php //Index for books page ?>
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
    <h1 class="text-center">Books</h1>
    <div id="tables" class="section">
        <h2 class="text-center">Tables</h2>
        <div class="text-center">
            <button id="balance" class="btn-primary btn table-btn" onclick="table('Balance',0)">Show Balance</button>
            <button id="accounting in" class="btn-primary btn table-btn" onclick="table('Accounting In',1)">Show Accounting In</button>
            <button id="accounting out" class="btn-primary btn table-btn" onclick="table('Accounting Out',2)">Show Accounting Out</button>
            <button id="petty cash id" class="btn-primary btn table-btn" onclick="table('Petty Cash ID',3)">Show Petty Cash ID</button>
            <button id="petty cash type" class="btn-primary btn table-btn" onclick="table('Petty Cash Type',4)">Show Petty Cash Type</button>
        </div>
        <div id="table_div">

        </div>
    </div>
    <br>
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
                    <p class="text-center form-title-p">Add Invoice</p>                
                    <div class="input-div"><p class="input-p">Date:</p><input type="date" class="input-input" id="incoive_date"></div>
                    <div class="input-div"><p class="input-p">Supplier:</p><input type="text" class="input-input" id="invoice_supplier"></div>
                    <div class="input-div"><p class="input-p">Reference:</p><input type="number" class="input-input" id="invoice_reference"></div>
                    <div class="input-div"><p class="input-p">Total:</p><input type="number" class="input-input" id="invoice_total"></div>
                    <p style="display: none;font-size:25px;" id="invoice_form_error"></p>
                    <button type="submit" id="add invoice form submit" class="input-btn btn-primary btn">Submit</button>
                </form>
            </div>
            <div class="input-form-div">
                <form action="" id="add receipt form" class="input-form-div-form input-form text-center">
                    <p class="text-center form-title-p">Add Receipt</p>
                    <div class="input-div"><p class="input-p">Date:</p><input type="date" class="input-input" id="receipt_date"></div>
                    <div class="input-div"><p class="input-p">Item:</p><input type="text" class="input-input" id="receipt_item"></div>
                    <div class="input-div"><p class="input-p">Total:</p><input type="number" class="input-input" id="receipt_total"></div>
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
                    <p class="text-center form-title-p">Add Bank Transaction</p>
                    <div class="input-div"><p class="input-p">Date:</p><input type="date" class="input-input" id="banktransaction_date"></div>
                    <div class="input-div"><p class="input-p">Supplier:</p><input type="text" class="input-input" id="banktransaction_supplier"></div>
                    <div class="input-div"><p class="input-p">Total:</p><input type="text" class="input-input" id="banktransaction_total"></div>
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
    <div id="statistics" class="section">
        <h2 class="text-center">Statistics</h2>
        <div class="statistics-inner-div">
            <div class="statistics-border">
                <p class="statistics-title-p">Bank</p>
                <p class="statistics-p">Total In: £
                <?php 
                    require_once('./lib.php');
                    $totalInput = total_money_input();
                    echo($totalInput);
                ?>
                </p>
                <p class="statistics-p">Total Out: £
                <?php
                    $totalOutput = total_money_output();
                    echo($totalOutput);
                ?>
                </p>
                <p class="statistics-p">Bank Balanace: £
                    <?php
                    echo((initial_bank_balance()+$totalInput)-$totalOutput);
                    ?>
                </p>
            </div>
            <div class="statistics-border">
                <p class="statistics-title-p">Petty Cash</p>
                <p class="statistics-p">Total In: £
                    <?php 
                    $totalPettycashInput = get_pettycash_in();
                    echo($totalPettycashInput);
                    ?>
                </p>
                <p class="statistics-p">Total Out: £
                    <?php
                    $totalPettycashOutput = get_pettycash_out();
                    echo($totalPettycashOutput);
                    ?>
                </p>
                <p class="statistics-p">Petty Cash Balance: £
                    <?php 
                    echo((initial_pettycash_balance()+$totalPettycashInput)-$totalPettycashOutput);
                    ?>
                </p>
            </div>
        </div>
    </div>
    <br>
    <div id="output" class="section">
        <h2 class="text-center">Output</h2>
        <div class="output-inner-div">
            <div class="output-border">
                <p class="output-title-p">Accounting</p>
                <form action="./pdf.php?t=a" method="POST" class="output-form">
                    <select name="month" id="month">
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                    <button type="submit">Download PDF</button>
                </form>
            </div>
            <div class="output-border">
                <p class="output-title-p">Petty Cash</p>
                <form action="./pdf.php?t=p" method="POST" class="output-form">
                    <select name="month" id="month">
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                    <button type="submit">Download PDF</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="./js/index.js"></script>
</html>