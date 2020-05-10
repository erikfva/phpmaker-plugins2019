//***********************************************************/
	//plg_main: Include javascript code in footer for all pages.";
	//-----------------------------------------------------------/
<?php
if (CurrentPage()->PageID == 'changepwd') {?>
	$("#fchangepwd").attr("target","_top");
<?php }?>
	$(".pageload-overlay").hide();
	//***********************************************************/
