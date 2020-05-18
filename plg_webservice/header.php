<?php
//echo "plg_webservice: header.php";exit();

if (file_exists($plgConf["plugins_path"] . "phpfn.php")) {
    include_once $plgConf["plugins_path"] . "phpfn.php";
} else {
    include_once "../" . $plgConf["plugins_path"] . "phpfn.php";
}

//var_dump($_POST);exit();

//----------------------------------------------------------------------//
//***procesando respuestas especiales por webservice o solicitudes json
//----------------------------------------------------------------------//

if (chkopt("webservice")) {

    global $Language;
    $sessionid = @$_POST["session_key"] . @$_GET["session_key"];

    // Language object
    if (!isset($Language)) {
        $Language = new Language();
    }

    if (chkopt("language")) {
        echo json_encode($Language);
        exit();
    }

    if (CurrentPageName() != "login.php" && !chkopt("login") && !IsLoggedIn() && (@$_SESSION[EW_PROJECT_NAME . "_Username"] == "")) { //validando opciones de autologin
        //autologin con parámetro 'session_key'

        if ($sessionid) {
            if (session_id() != "") {
                @session_destroy();
            }

            session_id($sessionid);
            session_start();
            header('Access-Control-Allow-Origin: *'); //Permitir cross-domain
            if (session_id() == "") {
                echo '{success:0,msg:"' . ew_DeniedMsg() . '"}';
                exit;
            }
        } else {
            header('Access-Control-Allow-Origin: *'); //Permitir cross-domain

            //***verificando acceso anonimo
            global $UserProfile, $Security;
            // User profile
            $UserProfile = new UserProfile();
            // Security
            $Security = new AdvancedSecurity();
            $TableName = CurrentPage();
            $action = "";
            if (strpos($TableName, "list") !== false) {
                $action = "list";
            }

            if (strpos($TableName, "edit") !== false) {
                $action = "edit";
            }

            if (strpos($TableName, "add") !== false) {
                $action = "add";
            }

            if (strpos($TableName, "delete") !== false) {
                $action = "delete";
            }

            $find = array("list", "edit", "add", "delete", ".php");
            $TableName = str_replace($find, "", $TableName);
            $Security->LoadCurrentUserLevel(CurrentProjectID() . $TableName);
            $authorized = false;
            switch ($action) {
                case "list":
                    $authorized = $Security->CanList();
                    break;
                case "edit":
                    $authorized = $Security->CanEdit();
                    break;
                case "add":
                    $authorized = $Security->CanAdd();
                    break;
                case "delete":
                    $authorized = $Security->CanDelete();
                    break;
            }
            if (!$authorized) {
                echo '{success:0,msg:"' . DeniedMessage() . '"}';
                exit;
            }
        }
    }

    if (CurrentPage() == "ewupload14.php") {
        if (@$_GET[@$_GET["id"]] != "" && @$_GET["rnd"] == "") {
            $file_name = UploadTempPath() . $_GET[$_GET["id"]];
            $key = EW_RANDOM_KEY . session_id();
            /*
            $fn = $plgConf["plugins_path"]."plg_webservice/ewfile.php?t=" . ew_Encrypt($_GET["table"], $key) ."&fn=" . ew_Encrypt($file_name, $key).(@$_GET["version"]=="thumbnail"?"":"&width=0&height=0");
            header('Location: '.$fn); exit;
             */
            $_GET["t"] = ew_Encrypt($_GET["table"], $key);
            $_GET["fn"] = ew_Encrypt($file_name, $key);
            if (@$_GET["version"] != "thumbnail") {
                $_GET["width"] = 0;
                $_GET["height"] = 0;
            }
            include_once $plgConf["plugins_path"] . "plg_webservice/ewfile.php";
            exit;
        }
    }

}
