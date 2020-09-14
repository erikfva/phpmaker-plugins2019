//***********************************************************/
	//plg_main: Include javascript code in footer for all pages.";
	//-----------------------------------------------------------/
<?php
if (@CurrentPage()->PageID == 'changepwd') {?>
	$("#fchangepwd").attr("target","_top");
<?php }?>
	$(".pageload-overlay").hide();
	//***********************************************************/
<?php if (IsGet() && Get("hidemasterheader") || IsPost() && Post("hidemasterheader")) {?>
        let links = document.querySelectorAll(
          ".ew-grid-cancel, .ew-inline-edit, .ew-inline-cancel, .ew-show-all, .ew-delete"
		);

		links.forEach(function(element) {
          element.href += "&hidemasterheader=true";
		});

		let forms = document.querySelectorAll(".ew-form");
		forms.forEach(function(form) {
          let hiddenInput = document.createElement("input");
          hiddenInput.type = "hidden";
          hiddenInput.name = "hidemasterheader";
          hiddenInput.value = "true";
          form.appendChild(hiddenInput);
        });
<?php }?>