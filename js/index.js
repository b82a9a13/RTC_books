let headers;
fetch("./json/headers.json")
    .then((response) => response.json())
    .then(function(json){ 
        headers = json;
    })
function table(type,number){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./inc/table.inc.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
    xhr.onreadystatechange = function() {
        if(this.status == 200){
            let buttons = document.querySelectorAll('.table-btn')
            for(let i = 0; i < buttons.length; i++){
                if(buttons[i].innerText.includes('Refresh')){
                    buttons[i].innerText = buttons[i].innerText.replace('Refresh','Show')
                }
            }
            let text = JSON.parse(this.responseText)
            let newTable = "<h2 class='text-center'>"+type+" Table</h2><table class='table-striped' style='margin-left:auto;margin-right:auto;'><thead><tr>"
            for(let i = 0; i < headers[number].length; i++){
                newTable += '<th>'+headers[number][i].heading+'</th>'
            }
            newTable += "</tr></thead><tbody>"
            for(let i = 0; i < text.length; i++){
                newTable += '<tr>'
                for(let y = 0; y < text[i].length; y++){
                    if(headers[number][y].type === 'Currency'){
                        newTable += '<td>£'+text[i][y]+'</td>'
                    } else {
                        newTable += '<td>'+text[i][y]+'</td>'
                    }
                }
                newTable += '</tr>'
            }
            newTable += "</tbody></table>"
            document.getElementById('table_div').innerHTML = newTable
            document.getElementById(type.toLowerCase()).innerText = document.getElementById(type.toLowerCase()).innerText.replace('Show','Refresh')
        }   
    }
    xhr.send(`type=${type}`);
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
    let date = document.getElementById('incoive_date').value
    let supplier = document.getElementById('invoice_supplier').value
    let reference = document.getElementById('invoice_reference').value
    let total = document.getElementById('invoice_total').value
    const errorText = document.getElementById('invoice_form_error')
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./inc/add_invoice.inc.php", true)
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
    xhr.onreadystatechange = function(){
        if(this.status == 200){
            let text = JSON.parse(this.responseText)
            if(text['success'] == true){
                document.getElementById('incoive_date').value = ''
                document.getElementById('invoice_supplier').value = ''
                document.getElementById('invoice_reference').value = ''
                document.getElementById('invoice_total').value = ''
                errorText.innerText = 'Success'
                errorText.style.display = 'block'
                errorText.className = 'text-success'
            }
        }
    }
    xhr.send(`date=${date}&supplier=${supplier}&reference=${reference}&total=${total}`)
})
document.getElementById('add receipt form').addEventListener("submit", (e) => {
    e.preventDefault();
    let date = document.getElementById('receipt_date').value;
    let item = document.getElementById('receipt_item').value;
    let total = document.getElementById('receipt_total').value;
    let type = document.getElementById('receipt_type').value;
    const errorText = document.getElementById('receipt_form_error');
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./inc/add_receipt.inc.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function(){
        if(this.status == 200){
            let text = JSON.parse(this.responseText);
            if(text['success'] == true){
                document.getElementById('receipt_date').value = '';
                document.getElementById('receipt_item').value = '';
                document.getElementById('receipt_total').value = '';
                document.getElementById('receipt_type').value = '';
                errorText.innerText = 'Success';
                errorText.style.display = 'block';
                errorText.className = 'text-success';
            }
        }
    };
    xhr.send(`date=${date}&item=${item}&total=${total}&type=${type}`);
});
document.getElementById('add bank transaction form').addEventListener("submit", (e) => {
    e.preventDefault();
    let date = document.getElementById('banktransaction_date').value;
    let supplier = document.getElementById('banktransaction_supplier').value;
    let total = document.getElementById('banktransaction_total').value;
    let type = document.getElementById('banktransaction_type').value;
    const errorText = document.getElementById('banktransaction_form_error');
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./inc/add_banktransaction.inc.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function(){
        if(this.status == 200){
            let text = JSON.parse(this.responseText);
            if(text['success'] == true){
                document.getElementById('banktransaction_date').value = '';
                document.getElementById('banktransaction_supplier').value = '';
                document.getElementById('banktransaction_total').value = '';
                document.getElementById('banktransaction_type').value = '';
                errorText.innerText = 'Success';
                errorText.style.display = 'block';
                errorText.className = 'text-success';
            }
        }
    };
    xhr.send(`date=${date}&supplier=${supplier}&total=${total}&type=${type}`);
})