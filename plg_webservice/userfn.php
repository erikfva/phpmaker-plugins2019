<?php

//include_once "plugins/phpfn.php";
if (file_exists($plgConf["plugins_path"] . "phpfn.php")) {
    include_once $plgConf["plugins_path"] . "phpfn.php";
} else {
    include_once "../" . $plgConf["plugins_path"] . "phpfn.php";
}

//echo "plg_webservice: userfn.php\n";exit();
//----------------------------------------------------------------------//
//Login con key de session guardada en variable $_SESSION utilizado para integracion con otros sistemas.
//----------------------------------------------------------------------//

$sessionid = @$_POST["session_key"] . @$_GET["session_key"];

if (!empty($sessionid)) {

    if (session_id() != "") {
        @session_destroy();
    }

    session_id($sessionid);
    session_start();
}

//-------------------------//
//***Funciones de soporte
//-------------------------//

function chkopt($op)
{
    global $cmdx;
    if (strpos($op, "webservice") !== false) {
        return strpos(Get("cmdx"), "webservice") !== false || strpos(Post("cmdx"), "webservice") !== false;
    }
    return (!empty($cmdx) && in_array($op, $cmdx));
}

function setWSR($key, $value = null)
{ //Web Service Response
    global $_SESSION;
    if (func_num_args() >= 2) {
        $_SESSION[CurrentPage()->PageObjName . "_WSR"][$key] = $value;
    } else {
        if (!is_null($obj = json_decode($key, true))) {
            foreach ($obj as $okey => $oval) {
                setWSR($okey, $oval);
            }
        }
    }
}

function toJSON($page)
{

    //** Get Fields Info */
    $FieldList = array();
    $orderField = "";
    $orderBy = $page->getSessionOrderBy();

    $orderType = strpos($orderBy, "ASC") !== false ? "ASC" : "DESC";
    foreach ($page->fields as $FldVar => $field) {
        $FieldList[] = array('id' => $field->FieldVar, 'name' => $FldVar,
            'caption' => $field->caption(),
            'sortable' => ($page->SortUrl($field) == "" ? false : true),
            'visible' => $field->Visible);
        $orderField = strpos(" " . $orderBy, " " . $FldVar . " ") !== false || strpos($orderBy, "`" . $FldVar . "` ") !== false ? $FldVar : $orderField;
    }

    //var_dump($orderBy, $orderField);exit();

    //** Get Security Info */
    global $Security;
    $Allowed = array(
        'CanView' => $Security->CanView(),
        'CanEdit' => $Security->CanEdit(),
        'CanDelete' => $Security->CanDelete(),
        'CanAdd' => $Security->CanAdd(),
        'CanList' => $Security->CanList(),
        'CanAdmin' => $Security->CanAdmin(),
        'CanSearch' => $Security->CanSearch(),
        'CanReport' => $Security->CanReport(),
    );

    $utf8 = (strtolower(Config("PROJECT_CHARSET")) == "utf-8");
    $bSelectLimit = $page->UseSelectLimit;

    //** Load recordset
    if ($bSelectLimit) {
        $page->TotalRecs = $page->ListRecordCount();
    } else {
        if (!$page->Recordset) {
            $page->Recordset = $page->LoadRecordset();
        }

        $rs = &$page->Recordset;
        if ($rs) {
            $page->TotalRecs = $rs->RecordCount();
        }

    }
    $page->StartRec = 1;

    //** Export one page only
    $page->SetupStartRecord(); // Set up start record position

    // Set the last record to display
    if ($page->DisplayRecords <= 0) {
        $page->StopRecord = $page->TotalRecords;
    } else {
        $page->StopRecord = $page->StartRecord + $page->DisplayRecords - 1;
    }

    if ($bSelectLimit) {
        $rs = $page->LoadRecordset($page->StartRecord - 1, $page->DisplayRecords <= 0 ? $page->TotalRecords : $page->DisplayRecords);
        /*
    $sSql = !empty($page->customSQL)? $page->customSQL : $page->ListSQL();
    $offset = $page->StartRec-1;
    $rowcnt = $page->DisplayRecs <= 0 ? $page->TotalRecs : $page->DisplayRecs;
    $sSql .= " LIMIT $rowcnt OFFSET $offset";
    global $ADODB_FETCH_MODE;
    $auxADODB_FETCH_MODE = $ADODB_FETCH_MODE;
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    error_reporting(~E_STRICT);
    $rs = ew_LoadRecordset($sSql);
    $ADODB_FETCH_MODE = $auxADODB_FETCH_MODE;
     */
    }

    if (!$rs) {
        header("Content-Type:"); // Remove header
        header("Content-Disposition:");
        $page->ShowMessage();
        return;
    }

    $Pager = new PrevNextPager($page->StartRecord, $page->DisplayRecords, $page->TotalRecords);

//** Create Json Document */
    $page->Export = "Json";
    $page->ExportDoc = GetExportDocumentJson($page, "h");
    $Doc = &$page->ExportDoc;

//** Set range limits
    if ($bSelectLimit) {
        $page->StartRecord = 1;
        $page->StopRecord = $page->DisplayRecords <= 0 ? $page->TotalRecords : $page->DisplayRecords;
    }

    // Call Page Exporting server event
    //--    $page->ExportDoc->ExportCustom = !$page->Page_Exporting();
    //--    $ParentTable = "";
    //--    $page->Page_DataRendering($page->PageHeader);
    $page->ExportDocument($Doc, $rs, $page->StartRecord, $page->StopRecord, "");
    //--    $page->Page_DataRendered($page->PageFooter);

    // Close recordset
    $rs->Close();
    // Call Page Exported server event
    //--    $page->Page_Exported();

    // Export header and footer
    //--    $Doc->ExportHeaderAndFooter();

    // Output data
    //    return json_encode($Doc->Items, 0);

    //$newRecord = NewRecord($page);

    //get protected method, reference -> https: //stackoverflow.com/questions/20334355/how-to-get-protected-property-of-object-in-php
    $Model = getModel($page);

    return json_encode(
        array(
            'psearch' => $page->BasicSearch->Keyword,
            'TableVar' => $page->TableVar,
            'TableCaption' => $page->TableCaption(),
            'Security' => $Allowed,
            'PageUrl' => $page->PageUrl(),
            'pager' => $Pager,
            'fieldList' => $FieldList,
            'orderField' => $orderField,
            'orderType' => $orderType,
            'Model' => $Model,
            'rows' => $Doc->GetItems(),

        )
    );

}
