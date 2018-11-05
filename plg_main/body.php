<?php if(function_exists("comportamiento") && comportamiento("100")){ ?>
<!--
**************************************
	 Motor de busqueda estilo Google
**************************************
!-->
<?php ew_AddClientScript($plgConf["plugins_path"]."plg_main/busqueda/main.js"); ?>
 <div id="formulario-buscar" class="open" align="center" style="top:10px;height:50px;right:50px">
	<form id="form-busqueda" name="f">
			<div class="pull-right input-group input-group-sm" style="z-index:2;">
		  <input id="texto-busqueda"

       type="text" class="form-control" name="q" size="20" placeholder="Buscar..." title="Busqueda">
		  <span class="input-group-btn">
		  	<button class="btn btn-default" type="button" onclick="$('#texto-busqueda').val('').focus();">&times;</button>
			<button id="btnBuscar" name="btnBuscar" class="btn btn-primary goobutton" type="button"><span class="glyphicon glyphicon-search"></span></button>
		  </span>
	  </div>
   </form>
<span class="responsive dropdown-menu dropdown-submenu" id="mensaje-buscando" style="color:#4CC3EC; text-align:center; display:none">Buscando...<img src="<?php echo $plgConf["plugins_path"]; ?>plg_main/busqueda/img/loading.gif"></span>
</div>
<?php }?>

<!--
**************************************
	Cuerpo principal
**************************************
!-->
<?php
	$__Pages = isset($__Pages)?$__Pages:array();
?>

<div id="mainbody" class="hide">
	<div class="metro-pivot">
<?php
	$scriptLoadPage = '';
	foreach($__Pages as $__Page){ ?>
	<div class="pivot-item" data-onselect="">
		<h3>
			<?php if(!empty($__Page["button_imagen"])){ ?>
			<img src="<?php echo $__Page["button_imagen"];?>" align="absmiddle" style="width:38px" border="0">
			<?php } ?>
			<?php if(!empty($__Page["button_html"])){
				echo $__Page["button_html"];
			} ?>			
			<span style="display:none"><?php echo $__Page["nombre"];?></span>
		</h3>
		<div class="metro-page" <?php if(!empty($__Page["onactive"])) echo 'onactive="'.$__Page["onactive"].'"';?>>
		<?php if(!empty($__Page["content"])){
			echo ($__Page["content"]);
		} else { ?>

			<iframe id="frame-<?php echo $__Page["id"];?>" style="<?php if(!empty($__Page["iframe_style"])) echo $__Page["iframe_style"];?>" class="empty <?php if(!empty($__Page["iframe_class"])) echo $__Page["iframe_class"];?>" scrolling="no" src="" data-url="<?php if(!empty($__Page["iframe_url"])) echo $__Page["iframe_url"];?>" frameborder="0" marginheight="0" marginwidth="0"  width="100%" height="100%"></iframe>

		<?php
			$scriptLoadPage .= "ew.language.obj.label_".$__Page["nombre"]." = '".$__Page["nombre"]."';";

			if(!empty($__Page["iframe_onload"]))
				$scriptLoadPage .= $scriptLoadPage .= "$('#frame-".$__Page["id"]."').on('load',function(){ ".$__Page["iframe_onload"]." });";
		} ?>
		</div>

	</div>
<?php	} ?>
<!-- Pagina links del menu principal-->
	<div class="pivot-item">
		<h3><span style="display:none">contenido</span></h3>
		<iframe id="frame-content" data-url="" class="autosize empty" scrolling="no" src="" frameborder="0" marginheight="0" marginwidth="0"  ></iframe>
	</div>
	<script type="text/javascript">
		ew.language.obj.label_contenido = 'contenido';
<?php	
	if(!empty($scriptLoadPage)){
		echo $scriptLoadPage;
	}
?>
	</script>
  </div>
</div>
