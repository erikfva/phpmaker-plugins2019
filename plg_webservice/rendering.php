<?php
/* if (chkopt("webservice")) { //Si se ha llamado como servicio.

$err = CurrentPage()->getFailureMessage();
if ($err != "") {
echo "rendering" . CurrentPage()->getFailureMessage() . "rendering";
setWSR('{"success":0,"msg":"' . $err . '"}');
}
if (CurrentPageID() == "list") {
$strJSON = toJSON(CurrentPage());
$_SESSION[CurrentPage()->PageObjName . "_WSR"] = json_decode($strJSON, true);
CurrentPage()->terminate();
}
}
 */
if (chkopt("webservice")) {
    $msg = CurrentPage()->getFailureMessage();
    if ($msg != "") {
        CurrentPage()->ClearFailureMessage();
        setWSR("success", 0);
        setWSR("msg", $msg);
    }
}
