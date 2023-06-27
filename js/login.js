document.getElementById('login_form').addEventListener('submit', (e)=>{
    e.preventDefault();
    const errorText = document.getElementById('login_error');
    errorText.style.display = 'none';
    const array = ['username', 'password', 'firstname', 'lastname', 'email'];
    let params = '';
    array.forEach((item)=>{
        if(document.getElementById(item)){
            params += item+'='+document.getElementById(item).value+'&';
        }
    });
    params = params.slice(0, -1);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', './inc/login.inc.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if(this.status == 200){
            const text = JSON.parse(this.responseText);
            if(text['error']){
                errorText.innerText = 'Invalid: ';
                text['error'].forEach((item)=>{
                    if(array.includes(item[0])){
                        errorText.innerText += item[1]+'| '
                    }
                })
                errorText.innerText = errorText.innerText.slice(0, -2);
                errorText.style.display = 'block';
            } else {
                if(text['return']){
                    window.location.reload();
                } else {
                    errorText.innerText = 'Submit error.';
                    errorText.style.display = 'block';
                }
            }
        } else {
            errorText.innerText = 'Connection error.';
            errorText.style.display = 'block';
        }
    }
    xhr.send(params);
})
let create = false;
document.getElementById('create_account').addEventListener('click', (e)=>{
    e.preventDefault();
    if(!create){
        const array = ['Firstname', 'Lastname', 'Email'];
        const feilds = document.getElementById('login_feilds');
        array.forEach((item)=>{
            let input = document.createElement('input');
            input.type = 'text';
            input.placeholder = item;
            input.id = item.toLowerCase();
            let span = document.createElement('span');
            span.innerText = item;
            let p = document.createElement('p');
            p.appendChild(span);
            p.appendChild(input);
            feilds.appendChild(p);
        });
        create = true;
    }
})