<h2 style="color:#ffffff"><?='Nuevo clip '.ucfirst($title);?></h2>
<p>Se requiere carga simultánea de DOS (2) archivos: un still del video y el clip/trailer del video como tal.</p>
<p>Arrastre ambos archivos hacia el cuadro para subirlos (<em>Nota:</em> Para cancelar carga cierre esta página).</p>
<?php

$attributes = array('class' => 'dropzone', 'id' => 'my-dropzone');
echo form_open_multipart(site_url('uploadclip'),$attributes); ?>
<input id="tipo" type="hidden" value="<?=$title;?>" name="type"/>
<input id="filename" type="hidden" name="filename"/>
</form>
<script>
Dropzone.options.myDropzone = {
  acceptedMimeTypes: "image/*,video/*",
  maxFilesize: 64,
  addRemoveLinks: true,
  init: function() {
    	this.on("success",function(file) { 
    	var fname = file.name;
    	var ftype = file.type;
        fname = fname.replace(/\s/g,"_");
        var pos = fname.indexOf(".");
        pos = fname.substr(pos+1); 
    	if (window.XMLHttpRequest)
   		request = new XMLHttpRequest();	
	function done()
	{
		if (request.readyState == 4 && request.status == 200)
		{	
        		window.alert(request.responseText);
		}
	};
		
	request.open("GET","/admin/index.php/uploadclip?tipo="+document.getElementById("tipo").value+"&ftype="+ftype,true);
	request.onreadystatechange = done;
	request.send(null);			
} );
	this.on("addedfile", function(file) { 
	var forbidden = ["<",">",":","\"","\\","/","|","*","?","[","]","=","%","$","+",",",";","~","á","é","í","ó","ú"];
	var fname = file.name;
	var ftype = file.type;
	var match = -1;
	for (var i=0;i<forbidden.length;i++)
	{
		match = fname.indexOf(forbidden[i]);
		if (match !== -1)
		{
			alert("NO es válido usar ' "+forbidden[i]+" ' en el nombre de archivo. Por favor quítelo!");
			window.location.reload(true);
			break;
		}	
	}
	if (ftype.substr(0,5) !== "image")
	{
		if (window.XMLHttpRequest)
   		{// code for IE7+, Firefox, Chrome, Opera, Safari
   			request=new XMLHttpRequest();
   		}
	
	function warningfile()
	{
		if (request.readyState == 4 && request.status == 200)
		{
			if (request.responseText > 0)
			{
			alert(file.name+" ya existe, y será reemplazado. Sino desea esto, cierre la página antes de terminar la carga.");
			}
			document.getElementById("filename").value = file.name;
		}
	};
		
	request.open("GET","/admin/index.php/uploadclip?fname="+file.name,true);
	request.onreadystatechange = warningfile;
	request.send(null);
	}	
	});
  }
};
</script> 