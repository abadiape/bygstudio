<table><tr><th><h1><?php echo $title; ?></h1></th><td>&nbsp;</td><td id="mailbox" onclick="dissapear()"></td></tr></table>
<!-- <p>Drag your video files into the space provided below this paragraph, or click onto it in order to upload your files.</p> -->
<p>Haga clic sobre el recuadro de abajo o arrastre su archivo de video hacia este, para subirlo (<em>Nota:</em> Para cancelar carga cierre esta página).</p>
<?php
$trans = array('á'=>'aQ','é'=>'eQ','í'=>'iQ','ó'=>'oQ','ú'=>'uQ',' '=>'_');
$title = strtr($title,$trans); 
$attributes = array('class' => 'dropzone', 'id' => 'my-dropzone');
//$name = $_FILES['file']['name'];
echo form_open_multipart(site_url('upload'),$attributes); ?>
<input type="hidden" value=<?=$title ?> name="proyecto" id="project" />
</form>
<div id="capvideo" style="border: solid 1px #ccc; padding: 10px; text-align: center;display:block;"> 
<audio id="audioplay" autoplay>
<source src="<?php echo base_url("sounds/water-splash.wav"); ?>" type="audio/wav">
<source src="<?php echo base_url("sounds/water-splash.mp3"); ?>" type="audio/mpeg"></audio>
<video id="video" width="320" height="240" controls>
<source id="videoshot" src="<?php echo base_url('uploads/'.$title.'/Ion4_y_tw.mp4'); ?>"  type="video/mp4">
</video><br/>
<button onclick="shoot()" style="width: 64px;border: solid 2px #ccc;">Capture</button><br/>
<div id="output" style="display: inline-block; top: 4px; position: relative ;border: dotted 1px #ccc; padding: 2px;">
</div>    
</div>
<script>
var videoId = 'video';
var scaleFactor = 0.25;
var snapshots = [];
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
function shoot(){
    var video  = document.getElementById(videoId);
    var output = document.getElementById('output');
    var canvas = capture(video, scaleFactor);
        canvas.onclick = function(){
            window.open(this.toDataURL());
        };
    snapshots.unshift(canvas);
    output.innerHTML = '';
    for(var i=0; i<4; i++){
        output.appendChild(snapshots[i]);
    }
}

var dissapear = function()
{
	document.getElementById("mailbox").innerHTML = "";
}
</script> 