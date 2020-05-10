
<?php
global $plgConf;

include_once $plgConf["plugins_path"] . "phpfn.php";
global $_SESSION;

$PageName = basename(CurrentPageName(), ".php");
if (!empty($_SESSION[$PageName . "_run_script"])) {
    echo $_SESSION[$PageName . "_run_script"];
    $_SESSION[$PageName . "_run_script"] = "";
}
?>
    //plg_main: client_script.php
    //Write javascript for <head> section.
