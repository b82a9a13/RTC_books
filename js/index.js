let headers;
fetch("./json/headers.json")
    .then((response) => response.json())
    .then(function(json){ 
        headers = json;
    })
function table(type,number){
    if((type == 'Balance' && number == 0) || (type == 'Accounting In' && number == 1) || (type == 'Accounting Out' && number == 2) || (type == 'Petty Cash ID' && number == 3) || (type == 'Petty Cash Type' && number == 4)){
        const errorText = document.getElementById('table_error');
        errorText.style.display = 'none';
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "./inc/table.inc.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function(){
            if(this.status == 200){
                const response = JSON.parse(this.responseText);
                if(response['error']){
                    errorText.innerText = response['error'];
                    errorText.style.display = 'block';
                } else if(response['data']){
                    let buttons = document.querySelectorAll('.table-btn');
                    for(let i = 0; i < buttons.length; i++){
                        if(buttons[i].innerText.includes('Refresh')){
                            buttons[i].innerText = buttons[i].innerText.replace('Refresh','Show');
                        }
                    }
                    let data = response['data'];
                    let newTable = `<h2 class='text-center'>${type} Table</h2><table id='current_table' tval='${type}' pval='${number}' class='table-striped' style='margin-left:auto;margin-right:auto;'><thead><tr>`;
                    for(let i = 0; i < headers[number].length; i++){
                        newTable += '<th>'+headers[number][i].heading+'</th>';
                    }
                    newTable += "</tr></thead><tbody>";
                    for(let i = 0; i < data.length; i++){
                        newTable += '<tr>';
                        for(let y = 0; y < data[i].length; y++){
                            if(headers[number][y].type === 'Currency'){
                                newTable += '<td>£'+data[i][y]+'</td>';
                            } else {
                                newTable += '<td>'+data[i][y]+'</td>';
                            }
                        }
                        newTable += '</tr>';
                    }
                    newTable += "</tbody></table>";
                    document.getElementById('table_div').innerHTML = newTable;
                    document.getElementById(type.toLowerCase()).innerText = document.getElementById(type.toLowerCase()).innerText.replace('Show','Refresh');
                } else {
                    errorText.innerText = 'Loading error';
                    errorText.style.display = 'block';
                }

            }   
        }
        xhr.send(`type=${type}`);
    }
}
function input(type){
    if(document.getElementById(type.toLowerCase()+" form").style.display == 'block'){
        document.getElementById(type.toLowerCase()+" form").style.display = 'none'
    } else{
        document.getElementById(type.toLowerCase()+" form").style.display = 'block'
    }
}
document.getElementById('add invoice form').addEventListener("submit", (e) => {
    e.preventDefault();
    let date = document.getElementById('incoive_date')
    let supplier = document.getElementById('invoice_supplier')
    let reference = document.getElementById('invoice_reference')
    let total = document.getElementById('invoice_total')
    date.style.border = ''
    supplier.style.border = ''
    reference.style.border = ''
    total.style.border = ''
    const errorText = document.getElementById('invoice_form_error')
    errorText.innerText = ''
    errorText.style.display = 'none'
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./inc/add_invoice.inc.php", true)
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
    xhr.onreadystatechange = function(){
        if(this.status == 200){
            let text = JSON.parse(this.responseText)
            errorText.innerText = ''
            if(text['date']){
                date.style.border = '2px solid red'
                errorText.innerText += text['date']+' |'
            }
            if(text['supplier']){
                supplier.style.border = '2px solid red'
                errorText.innerText += text['supplier']+' |'
            }
            if(text['reference']){
                reference.style.border = '2px solid red'
                errorText.innerText += text['reference']+' |'
            }
            if(text['total']){
                total.style.border = '2px solid red'
                errorText.innerText += text['total']+' |'
            }
            if(text['success'] == true){
                date.value = ''
                supplier.value = ''
                reference.value = ''
                total.value = ''
                errorText.innerText = 'Success'
                errorText.style.display = 'block'
                errorText.className = 'text-success'
                errorText.style.color = ''
                if(document.getElementById('current_table')){
                    const ctable = document.getElementById('current_table');
                    const value1 = ctable.getAttribute('tval');
                    const value2 = ctable.getAttribute('pval');
                    if('Accounting In' === value1 && '1' === value2){
                        table(value1, value2);
                    }
                }
                update_stats();
            } else {
                errorText.innerText = errorText.innerText.slice(0, -1)
                errorText.style.display = 'block'
                errorText.className = ''
                errorText.style.color = 'red'
            }
        }
    }
    xhr.send(`date=${date.value}&supplier=${supplier.value}&reference=${reference.value}&total=${total.value}`)
})
document.getElementById('add receipt form').addEventListener("submit", (e) => {
    e.preventDefault();
    let date = document.getElementById('receipt_date');
    let item = document.getElementById('receipt_item');
    let total = document.getElementById('receipt_total');
    let type = document.getElementById('receipt_type');
    date.style.border = '';
    item.style.border = '';
    total.style.border = '';
    type.style.border = '';
    const errorText = document.getElementById('receipt_form_error');
    errorText.innerText = '';
    errorText.style.display = '';
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./inc/add_receipt.inc.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function(){
        if(this.status == 200){
            let text = JSON.parse(this.responseText);
            errorText.innerText = "";
            if(text['date']){
                date.style.border = '2px solid red';
                errorText.innerText += text['date'] + " |";
            }
            if(text['item']){
                item.style.border = '2px solid red';
                errorText.innerText += text['item'] + " |";
            }
            if(text['total']){
                total.style.border = '2px solid red';
                errorText.innerText += text['total'] + " |";
            }
            if(text['type']){
                type.style.border = '2px solid red';
                errorText.innerText += text['type'] + " |";
            }
            if(text['success'] == true){
                date.value = '';
                item.value = '';
                total.value = '';
                type.value = '';
                errorText.innerText = 'Success';
                errorText.style.display = 'block';
                errorText.className = 'text-success';
                errorText.style.color = ''
                if(document.getElementById('current_table')){
                    const ctable = document.getElementById('current_table');
                    const value1 = ctable.getAttribute('tval');
                    const value2 = ctable.getAttribute('pval');
                    if(('Petty Cash ID' === value1 && '3' === value2) || ('Petty Cash Type' === value1 && '4' === value2)){
                        table(value1, value2);
                    }
                }
                update_stats();
            } else {
                errorText.innerText = errorText.innerText.slice(0, -1)
                errorText.style.display = 'block'
                errorText.className = ''
                errorText.style.color = 'red'
            }
        }
    };
    xhr.send(`date=${date.value}&item=${item.value}&total=${total.value}&type=${type.value}`);
});
document.getElementById('add bank transaction form').addEventListener("submit", (e) => {
    e.preventDefault();
    let date = document.getElementById('banktransaction_date');
    let supplier = document.getElementById('banktransaction_supplier');
    let total = document.getElementById('banktransaction_total');
    let type = document.getElementById('banktransaction_type');
    date.style.border = '';
    supplier.style.border = '';
    total.style.border = '';
    type.style.border = '';
    const errorText = document.getElementById('banktransaction_form_error');
    errorText.innerText = '';
    errorText.style.display = '';
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./inc/add_banktransaction.inc.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function(){
        if(this.status == 200){
            let text = JSON.parse(this.responseText);
            errorText.innerText = "";
            if(text['date']){
                date.style.border = '2px solid red';
                errorText.innerText += text['date'] + ' |';
            }
            if(text['supplier']){
                supplier.style.border = '2px solid red';
                errorText.innerText += text['supplier'] + ' |';
            }
            if(text['total']){
                total.style.border = '2px solid red';
                errorText.innerText += text['total'] + ' |';
            }
            if(text['type']){
                type.style.border = '2px solid red';
                errorText.innerText += text['type'] + ' |';
            }
            if(text['success'] == true){
                date.value = '';
                supplier.value = '';
                total.value = '';
                type.value = '';
                errorText.innerText = 'Success';
                errorText.style.display = 'block';
                errorText.className = 'text-success';
                if(document.getElementById('current_table')){
                    const ctable = document.getElementById('current_table');
                    const value1 = ctable.getAttribute('tval');
                    const value2 = ctable.getAttribute('pval');
                    if('Accounting Out' === value1 && '2' === value2){
                        table(value1, value2);
                    }
                }
                update_stats();
            } else {
                errorText.innerText = errorText.innerText.slice(0, -1)
                errorText.style.display = 'block'
                errorText.className = ''
                errorText.style.color = 'red'
            }
        }
    };
    xhr.send(`date=${date.value}&supplier=${supplier.value}&total=${total.value}&type=${type.value}`);
})
document.getElementById('logout_btn').addEventListener('click', ()=>{
    const xhr = new XMLHttpRequest();
    xhr.open('POST', './inc/logout.inc.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function(){
        if(this.status == 200){
            let text = JSON.parse(this.responseText);
            if(text['return']){
                window.location.reload();
            }
        }
    }
    xhr.send();
})
function update_stats(){
    update_stat('bank');
    update_stat('petty');
    update_table();
}
function update_stat(type){
    if(type == 'bank' || type == 'petty'){
        const errorText = document.getElementById(`${type}_stat_error`);
        errorText.style.display = 'none';
        const xhr = new XMLHttpRequest();
        xhr.open('POST', './inc/update_stat.inc.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function(){
            if(this.status == 200){
                const text = JSON.parse(this.responseText);
                if(text['error']){
                    errorText.innerText = text['error'];
                    errorText.style.display = 'block';
                } else if(text['data']){
                    document.getElementById(`${type}_stat_in`).innerText = text['data'][0];
                    document.getElementById(`${type}_stat_out`).innerText = text['data'][1];
                    document.getElementById(`${type}_stat_bal`).innerText = text['data'][2];
                } else {
                    errorText.innerText = 'Loading error';
                    errorText.style.display = 'block';
                }
            } else {
                errorText.innerText = 'Connection error';
                errorText.style.display = 'block';
            }
        }
        xhr.send(`t=${type}`);
    }
}
function update_table(){
    const type = document.getElementById('current_table').getAttribute('tval');
    switch(type){
        case 'Balance':
            table(type, 0);
            break;
        case 'Accounting In':
            table(type, 1);
            break;
        case 'Accounting Out':
            table(type, 2);
            break;
        case 'Petty Cash ID':
            table(type, 3);
            break;
        case 'Petty Cash Type':
            table(type, 4);
            break;
    }
}