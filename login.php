<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/login.css">
    <title>Login</title>
</head>
<body>
    <h1 class='text-center'>Login</h1>
    <div class='text-center'>
        <form action="" id="login_form">
            <div id="login_feilds">
                <p>
                    <span>Username</span>
                    <input type="text" placeholder="Username" id="username">
                </p>
                <p>
                    <span>Password</span>
                    <input type="password" placeholder="Password" id="password">
                </p>
            </div>
            <h2 style='display:none;color:red;' id="login_error"></h2>
            <button type="submit" class='btn btn-primary'>Submit</button>
        </form>
        <a id='create_account' href=''>Create account</a>
    </div>
</body>
<script defer src='./js/login.js'></script>
</html>
