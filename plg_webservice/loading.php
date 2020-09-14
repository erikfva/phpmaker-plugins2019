<?php
//echo "plg_webservice: loading.php\n";exit();

if (empty(CurrentPage()->PageObjName)) {
    CurrentPage()->PageObjName = "";
}

if (chkopt("webservice")) {

    $sessionid = @$_POST["session_key"] . @$_GET["session_key"];

    if (empty($sessionid) && CurrentPageName() != "login.php") {
        header('Access-Control-Allow-Origin: *'); //Permitir cross-domain
        header("Content-Type: application/json");
        echo '{"success":"0","msg":"' . DeniedMessage() . '"}';
        exit;
    }

    //Iniciando buffer Web Service Response WSR
    if (!empty($_SESSION[CurrentPage()->PageObjName . "_WSR"])) {
        unset($_SESSION[CurrentPage()->PageObjName . "_WSR"]); // = [];
    } else {
        $_SESSION[CurrentPage()->PageObjName . "_WSR"] = [];
    }

    global $_POST;
    //echo '{"msg":"' . CurrentPageID() . '"}';exit();
    switch (CurrentPageID()) {
        case 'login':
            CurrentPage()->CreateToken();
            $_POST[Config("TOKEN_NAME")] = CurrentPage()->Token;

            break;
        case "add":
            $_POST["t"] = CurrentPage()->TableName;
            $_POST["action"] = "insert";
            $_POST["modal"] = 0;
            CurrentPage()->CreateToken();
            $_POST[Config("TOKEN_NAME")] = CurrentPage()->Token;

            break;
        case "edit":
            $_POST["t"] = CurrentPage()->TableName;
            $_POST["action"] = "update";
            $_POST["modal"] = 0;

            CurrentPage()->CreateToken();
            $_POST[Config("TOKEN_NAME")] = CurrentPage()->Token;

            break;
        case "delete":
            $_POST["t"] = CurrentPage()->TableName;
            $_POST["a_list"] = "";
            CurrentPage()->CreateToken();
            $_POST[Config("TOKEN_NAME")] = CurrentPage()->Token;
            break;
    }
}
