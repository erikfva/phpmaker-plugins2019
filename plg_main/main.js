var EW_PAGE_ID = 'main';
var resizeTimer = 0;

/**************************************
	Barra de menu superior
**************************************/

document.addEventListener('DOMContentLoaded', function() {
	var navbar = document.querySelector('.main-header.navbar');
	var navbarAdjust = function(){
		document.querySelector('.ew-layout').style = 'margin-top:' + navbar.offsetHeight  + 'px';
	};
	
	navbar.classList.add('fixed-top');
	navbarAdjust();

	onResizeWindow(function(){
		navbarAdjust();
	});
})

// jQuery(document).ready(function(){
// 	$('.main-header.navbar').addClass();
// })

//**************************************
//PANTALLAS ESTILO ANDROID
//**************************************
function changePage(op){
	if( typeof op.url === "undefined" ) return;
	var url = op.url; 
	var iframe = $('#frame-' + op.id);
	if(iframe.length == 0 ) return;

	//ocultando el componente indicador de 'pagina cargando...' del iframe actual
	var currIframe = $('div.metro-pivot').find('.pivotItem.current .metro-page>iframe');
	if(currIframe.length && currIframe[0].contentWindow.splashLoadingOff){
		setTimeout(() => {
			currIframe[0].contentWindow.splashLoadingOff();			
		}, 200);
	} 

	iframe.attr('src',url);
	$('div.metro-pivot').data('metro-pivot').goToItemByName(op.nombre); 
}

jQuery(document).ready(function(){

	//Pagina links del menu principal
	ew.language.obj.label_contenido = 'contenido';

			var defaults = {
				animationDuration: 250,
				headerOpacity: 1, //sin disminucion del color cuando esta inactivo.
				fixedHeaders: true,
				headerSelector: function (item) { return item.children("h3").first();  },
				itemSelector: function (item) { return item.children(".pivot-item"); },
				headerItemTemplate: function () { return $("<span class='header'>"); },
				pivotItemTemplate: function () { return $("<div class='pivotItem'>"); },
				itemsTemplate: function () { return $("<div class='items'>"); },
				headersTemplate: function () { return $("<div class='headers btn-group navbar-custom-menu'>"); },
				controlInitialized: function(){
					this.data('metro-pivot',this);
					$(this).find('[onactive]').each(function(){
						$(this).on('page:active',function(){
							eval(this.getAttribute('onactive'));
						})
					});

					//this.headers.children(":contains(Inicio)").hide();
				},
				beforeItemChanged: function(index){

					var page = this.find('.pivot-item:eq(' + index + ')').find('.metro-page');
					if(page.length){
						page.trigger('page:active');
					}
					

					this.headers.find('.header').removeClass('bg-aqua-gradient shadow-b')
					.filter('.current').addClass('bg-aqua-gradient shadow-b');

					$("body").css("width", "inherit").css("height", "inherit");
					
					var iframe = this.find('.pivot-item:eq(' + index + ')').find('iframe');

					if(iframe.length && ( iframe.attr('id')=='frame-content' || iframe.is('.empty')) ){ //load left menu content					
						$('.pageload-overlay').show();
						iframe.attr('src', iframe.data('url') ).removeClass('empty');
					}
				},
				selectedItemChanged: function(index){
					

					if(this.items != undefined){
						var iframe = this.find('.pivot-item:eq(' + index + ')').find('iframe');

						if(iframe.length){
								if(iframe[0].contentWindow && iframe[0].contentWindow.doResize)
									iframe[0].contentWindow.doResize();
								if(iframe[0].contentWindow && typeof iframe[0].contentWindow.refreshContent === 'function'){
									iframe[0].contentWindow.refreshContent();
								}
  						} else {
						//	$("body").css("width", "inherit");
						}
					}
				}
			}
			
			
			$('#mainbody').css('margin-left', $('#leftmenu').width() + 'px' );
			
			$("div.metro-pivot").metroPivot(defaults);
			$("div.metro-pivot").find('.headers').prependTo('#ew-navbar-right');

			$('.pivot-item iframe:not(.lazyload)').each(function(){ $(this).attr('src', $(this).data('url') ).removeClass('empty'); });
			$('#mainbody').removeClass('hide');
			
			/**************************************
			AdminLTE
			**************************************/
			$('body').addClass('sidebar-collapse');

})

//**************************************
// Reacondicionando los links del menu 
// para que se abran en el iframe de contenido
//**************************************
function urlContent(url){
	if($('#frame-content').is(':visible')){
		$('#frame-content').attr('src',url + '?cmd=resetall&opciones=reset');
	}
	$('#frame-content').data('url',url + '?cmd=resetall&opciones=reset');
	$('div.metro-pivot').data('metro-pivot').goToItemByName('contenido');
	$('[data-widget="pushmenu"]').PushMenu('collapse');
	$('.pageload-overlay').show();
}

setTimeout(() => {
	$('#ew-menu a').each(function(){
	
		var ref = $(this).attr('href');
		if(ref && ref != '#')
		$(this)
		.click(function(){
			//var PushMenu = adminlte.PushMenu;
			//PushMenu._jQueryInterface.call($('[data-widget="pushmenu"]'), 'toggle');
			$('[data-widget="pushmenu"]').PushMenu('toggle');

			if($('#frame-content').is(':visible')){ 
				$('#frame-content').attr('src', ref + '?cmd=resetall&opciones=reset'); 
			} 
			$('#frame-content').data('url', ref + '?cmd=resetall&opciones=reset'); 
			$('div.metro-pivot').data('metro-pivot').goToItemByName('contenido');
			$('.pageload-overlay').show();
		})
		.attr('href','#');
	})
}, 200);




var isScrolling = false;

(function( $ ) {
	$(function() {
			$( window ).scroll(function() {
				isScrolling = true;
				clearTimeout( $.data( this, "checkScroll" ) );
				$.data( this, "checkScroll", setTimeout(function() {
					isScrolling = false;
				}, 250) );
			});
	});
})( jQuery );

$('.pageload-overlay').fadeOut();

function adjustAll(){
	if ($('body').hasClass('adjusting')) return;
	$('body').addClass('adjusting');
  	
	//mainbody
	var leftmargin = $('#leftmenu').length && ( $('#leftmenu').position().left == 0 || $('#leftmenu').position().left == 230) ? $('#leftmenu').width() : 0;
	 
	$('#mainbody').css('margin-left', leftmargin + 'px');
	
	$('.pivotItem.current iframe').each(function(){
		if(this.contentWindow.resizeIFRM) this.contentWindow.resizeIFRM();
	})
	
	$('body').removeClass('adjusting');
}

jQuery(document).ready(function(){
	adjustAll();
})