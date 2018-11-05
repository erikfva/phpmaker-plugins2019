<?php
	include_once "plugins/phpfn.php";
	global $_SESSION;

	$PageName = basename(CurrentPageName(), ".php");
	if(!empty($_SESSION[$PageName."_run_script"])){
		echo $_SESSION[$PageName."_run_script"];
		$_SESSION[$PageName."_run_script"] = "";
	}
	global $SkipHeaderFooter;

	if(@$SkipHeaderFooter && CurrentPage()->TableName != "main.php"){
	//if( CurrentPage()->TableName != "main.php"){
		echo "</script>";
			$bc = Breadcrumb();
			if($bc) $bc->Render();
		echo "<script>";
	}
?>