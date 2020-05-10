function isCrossOrigin() {
  try {
    //try to access the document object
    if (top.document || top.document.domain) {
      //we have the same document.domain value!
    }
  } catch (e) {
    //We don't have access, it's cross-origin!
    return true;
  }
  return false;
}

function onResizeWindow(f) {
  if (typeof f !== "function") return;
  var _resizeTimer = null;
  window.addEventListener("resize", function () {
    if (_resizeTimer) {
      clearTimeout(_resizeTimer);
    }
    _resizeTimer = setTimeout(function () {
      f();
    }, 1000);
  });
}

function sendParentMessage(msg) {
  if (window.parent) {
    window.parent.postMessage(msg, "*");
  }
}

// Called sometime after postMessage is called
function receiveMessage(event) {
  //console.log(event.data);
  switch (event.data.action || "") {
    case "resize":
      resizeIFRM(event.data);
      break;
    case "full":
      toggleFullIFRM(event.data);
      break;
    default:
  }
}

window.addEventListener("message", receiveMessage, false);

function viewPort() {
  //viewport size
  var w = window,
    d = document,
    e = d.documentElement,
    g = d.getElementsByTagName("body")[0],
    x_ = w.innerWidth || e.clientWidth || g.clientWidth,
    y_ = w.innerHeight || e.clientHeight || g.clientHeight;
  return { x: x_, y: y_ };
}

//********
//*Realizando el mejor ajuste del ancho y alto del iframe basado en su contenido y el ancho del navegador
//********
function doResize() {
  if (window.frameElement && window.parent) {
    // Send message to the top window (parent) at 500ms interval

    // var iframe = window.frameElement;
    // if(iframe.classList.contains('iframe-resizing')) return;
    // iframe.classList.add('iframe-resizing');

    //	setInterval(function() {
    // first parameter is the message to be passed
    // second paramter is the domain of the parent
    // in this case "*" has been used for demo sake (denoting no preference)
    // in production always pass the target domain for which the message is intended

    var wheight = document.body.scrollHeight;
    var wwidth = document.body.scrollWidth;

    if (ew && ew.PAGE_ID == "list") {
      wwidth = $(".ew-grid").width() + 20;
      document.body.style.width = wwidth + "px";
    }

    if (ew && (ew.PAGE_ID == "add" || ew.PAGE_ID == "edit")) {
      wwidth = $(".ew-editor").length ? $(".ew-editor").width() + 120 : 0; //Ajustar al ancho del editor html o al 100%.
    }

    sendParentMessage({
      action: "resize",
      height: wheight,
      width: wwidth,
      frameId: window.frameElement.id || null,
    });

    //	}, 1500);
  }
}

function resizeIFRM(data) {
  if (!data || !data.frameId || data.width == 20) return;
  var iframe = document.getElementById(data.frameId);
  var wwidth = $("main-header.navbar").length
    ? $("main-header.navbar").width()
    : $("html").width() || 0;

  iframe.style.width =
    data.width > 0 && data.width > wwidth ? data.width + "px" : "100%";

  setTimeout(() => {
    iframe.style.height =
      iframe.contentWindow.document.body.scrollHeight + "px";
    iframe.classList.remove("iframe-resizing");
  }, 200);

  //document.body.style.width = document.body.scrollWidth <= iframe.contentWindow.innerWidth? data.width : '100%';

  //console.log(document.body.scrollWidth, iframe.contentWindow.innerWidth);

  //document.body.scrollWidth <= iframe.contentWindow.innerWidth
  //console.log(iframe.offsetLeft);
  document.body.style.width =
    (data.width < wwidth ? wwidth : data.width + 20) + "px";
  //console.log($('html').width());
  //console.log(iframe.contentWindow.innerWidth, data.width);
}

document.addEventListener("DOMContentLoaded", function () {
  //window.onload = function(){
  //----------------------------------------------------
  // Autoajustando el iframe segun su contenido
  //----------------------------------------------------
  var delay = 0;
  if (ew && ew.PAGE_ID == "list" && window.frameElement) {
    document.body.style.width = "5000px"; //Para que la tabla no estreche el contenido de sus celdas.
  }
  if (ew && (ew.PAGE_ID == "add" || ew.PAGE_ID == "edit")) {
    //document.body.style.width = '100%';
    delay = 1000; //tiempo para que se inicializen componentes visuales antes de calcular el tamanio.
  }

  setTimeout(() => {
    doResize();
  }, delay);

  $(".collapse").on("shown.bs.collapse hidden.bs.collapse", function () {
    doResize();
  });
  $(".ewAddBlankRow").on("click", function () {
    resizeIFRM();
  });
  $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
    resizeIFRM();
  });
  //----------------------------------------------------

  //Volviendo el scroll al inicio.
  !isCrossOrigin() &&
    top.$("html, body").animate({ scrollTop: 0, scrollLeft: 0 }, 100);

  //----------------------------------------------------
  // Autoajuste de dialogos emergentes
  //----------------------------------------------------
  $(
    ".ewModalDialog,#ewPrompt,#ewModalDialog,#ewModalLookupDialog,#ewAddOptDialog"
  )
    .on("show.bs.modal", function () {})
    .on("shown.bs.modal", function () {
      var $dlg = $(this);

      //Fix: habilitando el boton guardar nuevamente.
      var actionBtn = $dlg.find("button.btn-primary.ewButton");
      if (actionBtn.length) {
        actionBtn.prop("disabled", false);
      }

      doResizeDlg($dlg);
    })
    .on("hidden.bs.modal", function () {
      $(this).css("width", "inherit");
      if ($(".modal.in").length == 0) doResize();
    });
  //----------------------------------------------------
});

function splashLoadingOff() {
  //if(top) top.$('.pageload-overlay').fadeOut();
  try {
    // ...but not to the document inside it
    if (top) top.$(".pageload-overlay").hide();
    //$('.pageload-overlay').fadeOut();
    $(".pageload-overlay").hide();
  } catch (e) {
    //alert(e); // Security Error (another origin)
  }
}

function resizeIFRMto($el, deltaxy) {
  var deltax =
    typeof deltaxy == "undefined"
      ? 0
      : typeof deltaxy.x == "undefined"
      ? 0
      : parseInt(deltaxy.x);
  var deltay =
    typeof deltaxy == "undefined"
      ? 0
      : typeof deltaxy.y == "undefined"
      ? 0
      : parseInt(deltaxy.y);

  if (window.frameElement && window.innerHeight < $el.height() + deltay) {
    var ifrm = window.frameElement;

    $(ifrm).addClass("iframe-resizing");
    $(ifrm).css({ height: $el.height() + deltay + "px" });
    $(ifrm).removeClass("iframe-resizing");
  }
}

function doResizeDlg($dlg) {
  if (!window.frameElement) return;

  setTimeout(() => {
    if (
      $(window.frameElement).height() < $dlg.find(".modal-content").height()
    ) {
      $dlg.height($dlg.find(".modal-content").height() + 150);
      $(window.frameElement).height($dlg.height() + 150);
    }

    $dlg.scrollTop(0);
    !isCrossOrigin() &&
      top.$("html, body").animate({ scrollTop: 0, scrollLeft: 0 }, 500);

    if (!isCrossOrigin() && $dlg.width() > top.innerWidth) {
      var newwidth =
        top.innerWidth -
        window.frameElement.offsetLeft -
        window.frameElement.offsetParent.offsetLeft;
      $dlg.width(newwidth > 700 ? newwidth : 700);
    }

    if (
      !isCrossOrigin() &&
      $dlg.parent() &&
      $dlg.parent().parent() &&
      top.$("body").width() < $dlg.parent().parent().width()
    )
      top.$("body").width($dlg.parent().parent().width() - 20);
  }, 800);
}

if (window.frameElement) {
  //-> Si es un iframe
  /*
	$(window).on('load',function(){ //-> Se definen acciones realizadas al cargar el contenido del iframe
		//Volviendo el scroll al inicio.
		(!isCrossOrigin()) && top.$("html, body").animate({scrollTop: 0, scrollLeft: 0 }, 500);
		//Realizando autoajuste del alto del iframe despues de milisegundos
		setTimeout(function(){
			doResize();
		},250)

		$('.collapse').on('shown.bs.collapse hidden.bs.collapse', function(){
			resizeIFRM();
		});

		$(".ewModalDialog,#ewPrompt,#ewModalDialog,#ewModalLookupDialog,#ewAddOptDialog")
		.on("show.bs.modal",function(){

		})
		.on("shown.bs.modal",function(){
			var $dlg = $(this);

			//Fix: habilitando el boton guardar nuevamente.
			var actionBtn = $dlg.find('button.btn-primary.ewButton');
			if(actionBtn.length){
				actionBtn.prop('disabled', false);
			}

			doResizeDlg($dlg);								
		})
		.on("hidden.bs.modal",function(){
			$(this).css('width','inherit');
			if($('.modal.in').length == 0)
				resizeIFRM();
		});

		$('.ewAddBlankRow').on('click',function(){ resizeIFRM(); });
		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			resizeIFRM();
		})
	});
*/
  //Ajustando el contenido de iframe al cambiar de tamanio la pantalla principal
  if (!isCrossOrigin()) {
    top.onResizeWindow(function () {
      doResize();
    });
    /*
		top.$(top).bind('resize', function () {
			//doResize();
				if(top && top.resizeTimer) top.clearTimeout(top.resizeTimer);
				if(top) top.resizeTimer = top.setTimeout(function(){
							doResize();
					}, 250);
					
		})
		*/
  }
}

if (typeof ew_OnError == "function") {
  PHPMaker_ew_OnError = ew_OnError;
  ew_OnError = function (frm, el, msg) {
    setTimeout(function () {
      splashLoadingOff();
    }, 200);
    PHPMaker_ew_OnError(frm, el, msg);
  };
}

//+++++ FUNCIONES PARA REFRESCAR EL CONTENIDO DEL LISTADO MEDIANTE AJAX

//USO => En la seccion Client Script/ Table-Specific/ List Page/ StartUp Script invocar a la funcion de la sgte. manera:
//function refreshContent(t){
//	refreshTableOn({
//		time:(!$.isUndefined(t)?t:0),
//		oncomplete:function(){
//			calcular_edad();
//			hideEmpty();
//		}
//	});
//}

//jQuery(window).ready(function(){
//	refreshContent(7000);
//});

function ApplyTemplateTable(containerTable) {
  containerTable
    .find(
      "table." +
        EW_TABLE_CLASSNAME +
        ":not(.ewExportTable):not(#" +
        EW_REPORT_CONTAINER_ID +
        " table)"
    )
    .each(ew_SetupTable); // Init tables
  containerTable
    .find(
      "table." +
        EW_GRID_CLASSNAME +
        ":not(.ewExportTable):not(#" +
        EW_REPORT_CONTAINER_ID +
        " table)"
    )
    .each(ew_SetupGrid); // Init grids
  coolTemplate(containerTable);
}

function isScrolledIntoView(el) {
  var elemTop = el.getBoundingClientRect().top;
  var elemBottom = el.getBoundingClientRect().bottom;

  var isVisible = elemTop >= 0 && elemBottom <= window.innerHeight;
  return isVisible;
}

function refreshTable(options) {
  if (typeof options.containerTable == "undefined") return;
  let ref = "#" + options.containerTable.attr("id");
  w = mainwin(window);
  if (
    !(w.isScrolling || false) &&
    $(window.frameElement ? window.frameElement : window).is(":visible") &&
    $(ref).is(":visible:not(.updating)") &&
    options.containerTable.find("input:checkbox:checked").length === 0
  ) {
    options.onbefore.call();

    // $(referencia).load(location.href + ' ' + referencia, function(){
    // 	ApplyTemplateTable($(this));
    // 	resizeIFRM(2000);
    // 	options.oncomplete.call();
    // 	$(this).removeClass('updating');
    // });

    var pageID = CurrentForm.ID.substring(1);
    $(ref).addClass("updating");

    $("#tbl_" + pageID).load(
      location.href + " " + "#tbl_" + pageID + ">*",
      function () {
        if ($("#tpd_" + pageID).length)
          //Has template definition?
          ew_ApplyTemplate(
            "tpd_" + pageID,
            "tpm_" + pageID,
            pageID,
            "",
            ewVar.templateData
          );
        ApplyTemplateTable(options.containerTable);
        resizeIFRM(500);
        options.oncomplete.call();
        $(ref).removeClass("updating");
        if (options.time > 0)
          setTimeout(function () {
            refreshTable(options);
          }, options.time);
      }
    );
  } else {
    if (options.time > 0)
      setTimeout(function () {
        refreshTable(options);
      }, options.time);
  }
}

function refreshTableOn(options) {
  let defaultopt = {
    time: 10000, //10 segundos
    onbefore: function () {},
    oncomplete: function () {},
    containerTable: $('div[id*="gmp_"]'),
    condition: function () {
      return true;
    },
    params: null,
    forceRefresh: false,
    url:
      location.href.indexOf("about:blank") != -1
        ? $(window.frameElement).data("url")
        : location.href,
  };
  if (typeof options !== "undefined") $.extend(defaultopt, options);

  //if(!isScrolledIntoView(defaultopt.containerTable.get(0))) return;
  //console.log(options.containerTable.attr('id'));
  //ApplyTemplateTable(defaultopt.containerTable);
  //console.log(defaultopt.time);
  setTimeout(function () {
    refreshTable(defaultopt);
  }, defaultopt.time);
}
/*
jQuery(document).ready(function () {
  //Mostrar la pantalla de cargando... al dar click en los sgtes elementos:
  $(
    ".ewDetailAddGroup , .ewBreadcrumbs a, .ewListOptionBody .btn:not(.ewGridLink), .ewPager .btn"
  ).on("click", function () {
    //$('.pageload-overlay').show();
    //console.log('show');
    if (!isCrossOrigin()) if (top) top.$(".pageload-overlay").show();
  });
  //Ocultar la pantalla de cargando... al cerrar los siguientes dialogos modal:
  $("#ewModalDialog,#ewModalLookupDialog,#ewAddOptDialog").on(
    "hide.bs.modal",
    function () {
      splashLoadingOff();
    }
  );

  //FIX tooltip position when overflow-x is hidden
  $(".ewTooltipLink").on("shown.bs.popover", function (e) {
    var pw = this.parentElement.clientWidth || 0;
    if (pw > 0 && pw < $(this).width()) {
      var $tip = $(this).data("bs.popover").tip();
      var dx = $(this).width() - pw;
      $tip.css("left", parseInt($tip.css("left")) - dx + "px");
    }
  });
});
*/
