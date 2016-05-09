<table><tr><th><h1><?php echo $title; ?></h1></th><td>&nbsp;</td><td id="mailbox" onclick="dissapear()"></td></tr></table>
<!-- <p>Drag your video files into the space provided below this paragraph, or click onto it in order to upload your files.</p> -->
<p>Haga clic sobre el recuadro de abajo o arrastre su archivo de video hacia este, para subirlo (<em>Nota:</em> Para cancelar carga cierre esta página).</p>
<?php
$trans = array('á'=>'aQ','é'=>'eQ','í'=>'iQ','ó'=>'oQ','ú'=>'uQ',' '=>'_');
$title = strtr($title,$trans); 
$attributes = array('class' => 'dropzone', 'id' => 'my-dropzone');
echo form_open_multipart(site_url('upload'),$attributes); ?>
<input type="hidden" value=<?=$title ?> name="proyecto" id="project" />
<input type="hidden" name="filename" id="filename" />
</form><br/> 
<audio id="audioplay">
<source src="<?php echo base_url("sounds/HALmessage.wav"); ?>">
<source src="<?php echo base_url("sounds/affirmative.wav"); ?>">
<source src="<?php echo base_url("sounds/HAL2001.mp3"); ?>">
</audio>
<div id="capturestill" style="border: solid 1px #ccc; padding: 10px; text-align: center;display:block;"> 
<video id="video1" preload="auto" width="480" height="286" muted loop>
<object width="480" height="286" type="application/x-shockwave-flash" data="<?=base_url("mediaelementjs/build/flashmediaelement.swf") ?>" >
<param name="movie" value="<?=base_url("mediaelementjs/build/flashmediaelement.swf") ?>" />
<param id="valor" name="flashvars" value="controls=true&file=" />
</object>
</video>
<br/> 
</div>
<div id="output" name="output" style="display: inline-block; top: 4px; position: relative ;border: dotted 1px #ccc; padding: 2px;">
</div> 
<script>
Dropzone.options.myDropzone = {
  maxFilesize: 2048,
  addRemoveLinks: true,
  init: function() {
    this.on("success",function(file) {
        var fname = file.name;
        fname = fname.replace(/\s/g,"_");
        var pos = fname.indexOf(".");
        pos = fname.substr(pos+1); 
    	document.getElementById("video1").setAttribute("src","<?php echo base_url('uploads/'.$title.'/'.'"+fname+"'); ?>");
    	var browser = BrowserDetect.browser;
    	if (pos === "mp4" && (browser === "Firefox" || browser === "Opera"))
    	{
    	document.getElementById("valor").setAttribute("value","controls=true&file=<?php echo base_url('uploads/'.$title.'/'.'"+fname+"'); ?>");
    	}
    	if (pos === "mov" || pos === "MOV")
        {
        document.getElementById("capturestill").innerHTML = QT_GenerateOBJECTText("<?php echo base_url('uploads/'.$title.'/'.'"+fname+"'); ?>" , 
        '480', '286', '', 'SCALE', 'Aspect', 'CONTROLLER' , 'False', 'VOLUME' , '0' , 'postdomevents', 'True',  'AUTOPLAY', 'True', 
        'obj#id', 'video1', 'emb#id', 'videoqt', 'emb#name', 'video1', 'EnableJavaScript', 'True');
        RegisterListeners();
        }                        
	if (window.XMLHttpRequest)
   	{// code for IE7+, Firefox, Chrome, Opera, Safari
   		request=new XMLHttpRequest();
   	}
 	else
   	{// code for IE6, IE5
   		request=new ActiveXObject("Microsoft.XMLHTTP");
  	};
	
	function mailLink()
	{
		if (request.readyState == 4 && request.status == 200)
		{
			var audio = document.getElementById("audioplay");
        		audio.play();  
        		document.getElementById("mailbox").innerHTML = (request.responseText);
        		if (pos === "mp4")
        		{
        			if (browser !== "Firefox" && browser !== "Opera")
        			{
        				var video = document.getElementById("video1");
					video.play();
				}
				else 
				{
					$('video').mediaelementplayer();
				}	
			}  
			// else void document.video1.Play();
		}
	};	
	request.open("GET","/admin/index.php/upload?mailinfo="+document.getElementById("project").value,true);
	request.onreadystatechange = mailLink;	
	request.send(null);			
} );

	this.on("addedfile", function(file) {
	if (window.XMLHttpRequest)
   	{// code for IE7+, Firefox, Chrome, Opera, Safari
   		request=new XMLHttpRequest();
   	}
 	else
   	{// code for IE6, IE5
   		request=new ActiveXObject("Microsoft.XMLHTTP");
  	};
	
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
	request.open("GET","/admin/index.php/upload?info="+file.name,true);
	request.onreadystatechange = warningfile;
	request.send(null);	
	});
  }
};

    /* define function that executes after video starts playing (Autoplay) */	
	function playStarted()
        {
        	setTimeout("void document.video1.Stop()",1000);
         
        }
 
    /* define function that adds another function as a DOM event listener */
         function myAddListener(obj, evt, handler, captures)
        {
        if ( document.addEventListener )
            obj.addEventListener(evt, handler, captures);
        else
            // IE
            obj.attachEvent('on' + evt, handler);
       }

   /* define functions that register each listener */
    function RegisterListener(eventName, objID, embedID, listenerFcn)
    {
        var obj = document.getElementById(objID);
        if ( !obj )
            obj = document.getElementById(embedID);
        if ( obj )
            myAddListener(obj, eventName, listenerFcn, false);
    }

    /* define a single function that registers all listeners to call onload */
    function RegisterListeners()
    { 
       RegisterListener('qt_play', 'video1', 'videoqt', playStarted);
    }

var video = document.getElementById("video1");
video.addEventListener("playing", function () {setTimeout("video.pause()",1000);setTimeout("videoStill()",1000);}, true);
   
var scaleFactor = 0.50;
/**
* Captures a image frame from the provided video element.
*
* @param {Video} video HTML5 video element from where the image frame will be captured.
* @param {Number} scaleFactor Factor to scale the canvas element that will be return. This is an optional parameter.
*
* @return {Canvas}
*/
function capture(video, scaleFactor) {
    if(scaleFactor == null){
        scaleFactor = 1;
    }
    var w = video.videoWidth * scaleFactor;
    var h = video.videoHeight * scaleFactor;
    var canvas = document.createElement('canvas');
        canvas.width  = w;
        canvas.height = h;
    var ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, w, h);
    return canvas;
}
/**
* Invokes the <code>capture</code> function and attaches the canvas element to the DOM.
*/
function videoStill(){
    var pos = fname.indexOf(".");
    pos = fname.substr(pos+1); 
    var video = document.getElementById("video1");
    if (pos !== "mp4")
    {
        if ( !video )
            video = document.getElementById("videoqt");
    }  
    var output = document.getElementById('output');
    var project = document.getElementById('project').value
    var canvas = capture(video, scaleFactor);
    canvas.onclick = function(){
          window.open(this.toDataURL());
    }; 
    output.appendChild(canvas);
    still = canvas.toDataURL();
    var fname = document.getElementById('filename').value; 
    fname = fname.replace(/\s/g,"_");
	if (window.XMLHttpRequest)
   	{// code for IE7+, Firefox, Chrome, Opera, Safari
   		request=new XMLHttpRequest();
   	}
 	else
   	{// code for IE6, IE5
   		request=new ActiveXObject("Microsoft.XMLHTTP");
  	};
	
	function sendStill()
	{
		if (request.readyState == 4 && request.status == 200)
		{
			document.getElementById("mailbox");
		}
	};	
	request.open("POST","/admin/index.php/uploadstill",true);
	request.onreadystatechange = sendStill;
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.send("project="+document.getElementById('project').value+
	"&fname="+fname+"&still="+still);			
}

var dissapear = function()
{
	document.getElementById("mailbox").innerHTML = "";	
}
// alert("El caracter"+"<strong>"+" <>:"\/|*?[]=%$+,;~"+ "</strong>"+" no es válido en un nombre de archivo. Por favor corrija");
</script> 