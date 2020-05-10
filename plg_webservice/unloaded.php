<?php
//echo "plg_webservice: unloaded.php\n";exit();

if (@$GLOBALS["_SERVER"]["REQUEST_METHOD"] == "OPTIONS") {
    header('Access-Control-Allow-Origin: *'); //Permitir cross-domain
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header("Content-Type: application/json");
    header("Access-Control-Allow-Headers: X-API-KEY, X-PINGARUNER, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    exit();
}

//verificando webservice.
if (chkopt("webservice")) {

    if (!IsLoggedIn()) {
        global $Language;
        CurrentPage()->setFailureMessage($Language->Phrase("InvalidUidPwd"));
    }

    $success = true;
    $msg = CurrentPage()->getFailureMessage();
    if ($msg != "") {
        $success = false;
        CurrentPage()->ClearFailureMessage();
        setWSR("success", 0);
        setWSR("msg", $msg);
        //echo json_encode(json_decode('{"success":0,"msg":"'.$msg.'"}'));
    }
    $msg = CurrentPage()->getSuccessMessage();
    if ($msg != "") {
        setWSR("success", 1);
        setWSR("msg", $msg);
        //echo json_encode(json_decode('{"success":1,"msg":"'.$msg.'"}'));
        CurrentPage()->ClearSuccessMessage();
    }

    if ($success) { //prepare json result

        if (CurrentPageID() == 'login') {
            $user = isset($_GET["username"]) ? $_GET["username"] : (isset($_POST["username"]) ? $_POST["username"] : '');
            if (EW_ADMIN_USER_NAME != $user) {
                /*
                global $usuario, $Security;
                $usuario->CurrentFilter = ew_QuotedName("user", EW_USER_TABLE_DBID)."='".$Security->getCurrentUserName()."'";
                global $ADODB_FETCH_MODE;
                $auxADODB_FETCH_MODE = $ADODB_FETCH_MODE;
                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                error_reporting(~E_STRICT);
                $usrJSON = json_decode( ew_ExecuteJson($usuario->SelectSQL()), true);
                unset($usrJSON["password"]); //Quitando el campo "password" para la respuesta webservice.
                $ADODB_FETCH_MODE = $auxADODB_FETCH_MODE;
                 */
                global $UserProfile;
                global $_SESSION;
                //var_dump($usrJSON); exit;
                $_SESSION[CurrentPage()->PageObjName . "_WSR"] = (array) $UserProfile->Profile;
            }
            setWSR("session_key", session_id());

        }

        if (CurrentPageID() == "list") {
            $strJSON = toJSON(CurrentPage());
            $_SESSION[CurrentPage()->PageObjName . "_WSR"] = json_decode($strJSON, true);
        }
    }

    // Clean output buffer
    if (ob_get_length()) {
        ob_end_clean();
    }

    header('Access-Control-Allow-Origin: *'); //Permitir cross-domain
    header("Content-Type: application/json");
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");

    if (@$_SESSION[CurrentPage()->PageObjName . "_WSR"]) {
        if (is_array(@$_SESSION[CurrentPage()->PageObjName . "_WSR"])) {
            echo json_encode(@$_SESSION[CurrentPage()->PageObjName . "_WSR"]); //$strJSON;
        } else {
            echo @$_SESSION[CurrentPage()->PageObjName . "_WSR"]; //$strJSON;
        }
    }

    exit();
}
