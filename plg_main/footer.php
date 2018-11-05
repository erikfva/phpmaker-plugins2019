<script type="text/javascript">
	//plg_main: Include javascript code in footer for all pages.
<?php if(CurrentPage()->PageID == 'changepwd'){ ?>
	$("#fchangepwd").attr("target","_top");
<?php } ?>
</script>
<?php
global $EW_RELATIVE_PATH;
global $Page;

if($Page && $Page->TableName == "main.php"){
	AddClientScript($plgConf["plugins_path"]."plg_main/metro/jquery.metro.js");
	AddClientScript($plgConf["plugins_path"]."plg_main/main.js");
}
?>