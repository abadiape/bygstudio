<table><tr><th><h1 id="title"><?php echo $title; ?></h1></th><td>&nbsp;</td><td id="mailbox" onclick="dissapear()"></td></tr></table>
<!-- <p>Drag your video files into the space provided below this paragraph, or click onto it in order to upload your files.</p> -->
<?php if ($message) echo $message ?>
<p>Arrastre su(s) archivo(s) de video hacia el recuadro, para subirlo(s) (<em>Nota:</em> Para cancelar carga cierre esta página).</p>
<?php
$trans = array('á'=>'aQ','é'=>'eQ','í'=>'iQ','ó'=>'oQ','ú'=>'uQ',' '=>'_');
$title = strtr($title,$trans); 
$attributes = array('class' => 'dropzone', 'id' => 'my-dropzone');
echo form_open_multipart(site_url('upload'),$attributes); ?>
    <input type="hidden" value="<?=$title ?>" name="proyecto" id="project" />
    <input type="hidden" name="filename" id="filename" />
</form>
<audio id="audioplay">
    <source src="<?php echo base_url("sounds/HALmessage.wav"); ?>">
    <source src="<?php echo base_url("sounds/affirmative.wav"); ?>">
    <source src="<?php echo base_url("sounds/HAL2001.mp3"); ?>">
</audio>
<div id="capturestill" style="border: solid 1px #ccc; padding: 10px; text-align: center;display:none;"> 
    <video id="video1" preload="auto" width="480" height="286" muted loop>
    </video>
</div>
<script>
    Dropzone.options.myDropzone = {
      maxFilesize: 1024,
      addRemoveLinks: true,
      init: function() {
            var m = 0;
            var n = 0;
            var fname;
            var mailinfo = "";
            var filesArray = {};
            this.on("success",function(file) {
                fname = file.name;
                filesArray["file"+m] = fname;
                ++m;
                fname = fname.replace(/\s/g,"_");
                var pos = fname.indexOf(".");
                pos = fname.substr(pos+1); 
                var browser = BrowserDetect.browser;
                if (pos === "mp4" && (browser !== "Firefox" && browser !== "Opera"))
                {
                    document.getElementById("video1").setAttribute("src","<?php echo base_url('uploads/'.$title.'/'.'"+fname+"'); ?>");
                }
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    request=new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    request=new ActiveXObject("Microsoft.XMLHTTP");
                }

                function mailLink()
                {
                    if (request.readyState == 4 && request.status == 200)
                    {
                        var audio = document.getElementById("audioplay");
                        if (m  === n)
                        {                            
                            audio.play();
                            document.getElementById("mailbox").innerHTML = request.responseText;                            
                        }
                        /*if (pos === "mp4" && (browser !== "Firefox" && browser !== "Opera"))
                        {
                            var video = document.getElementById("video1");
                            video.play();
                        }*/
                    }
                };
                if (m  === n)
                {
                    mailinfo = document.getElementById("project").value;
                    filesArray = JSON.stringify(filesArray);
                }
                request.open("GET","/admin/index.php/upload?mailinfo="+mailinfo+"&filesarray="+filesArray,true);
                request.onreadystatechange = mailLink;
                request.send(null);			
            } );
            this.on("addedfile", function(file) { 
                ++n;
                var forbidden = ["<",">",":","\"","\\","/","|","*","?","[","]","=","%","$","+",",",";","~","á","é","í","ó","ú"];
                fname = file.name;
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

    var video = document.getElementById("video1");
    //video.addEventListener("playing", function () {setTimeout("video.pause()",1000);setTimeout("videoStill()",1000);}, true);

    var scaleFactor = 1.0;
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
        var video = document.getElementById("video1");
        var canvas = capture(video, scaleFactor);
        var still = canvas.toDataURL();
        var fname = document.getElementById('filename').value; 
        fname = fname.replace(/\s/g,"_");
        var project = document.getElementById('project').value
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
                        document.getElementById("video1").setAttribute("src","");
                }
            };	
            request.open("POST","/admin/index.php/uploadstill",true);
            request.onreadystatechange = sendStill;
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send("project="+project+
            "&fname="+fname+"&still="+still);			
    }

    var dissapear = function()
    {
        document.getElementById("mailbox").innerHTML = "";
        document.getElementById("title").setAttribute("onclick","appear()");
    }

    var appear = function()
    {
        document.getElementById("mailbox").innerHTML = "<a href='<?php echo site_url('sendmail') ?>' >"+"Enviar link(URL) de esta versión"+"</a>";
    }
</script> 