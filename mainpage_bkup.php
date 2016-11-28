<!DOCTYPE html>
<html>
<head>
<title>Byg Studio | Postproduction services México</title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta name="description=" content="BYG STUDIO offers a whole range of specialized postproduction services including editing, color grading, animation, compositing and VFX for commercials, TV series and feature films for the USA and Latin American markets. We are based in Mexico.">
<meta name="keywords" content="Byg, Bygstudio, Byg Films, Producciones, Productoras, Post, Postproductora, Postproducciones, Postproduccion, Postproduction, Mexico, Productions, TV, Spots, Mexicano, America Latina, Colombia, Publicidad, post-production, editing, commercials, marketing, series, vfx, animation, color grading, compositing, La Otra Familia, Heroes del Norte, Cloroformo, Paramedicos, Dos lunas, Pulling strings, Amor a primera visa" />
<link rel="stylesheet" href="http://bygstudio.com/css/reset1.0.css"/>
<link rel="stylesheet" href="http://bygstudio.com/css/screen.css" />
<link rel="stylesheet" href="http://bygstudio.com/css/sections.css" /> 
<link rel="stylesheet" href="http://bygstudio.com/admin/slider/css/anythingslider.css">
<link rel="stylesheet" href="http://bygstudio.com/admin/mediaelementjs/build/mediaelementplayer2.css")/>
<script src="http://bygstudio.com/admin/mediaelementjs/build/jquery.js"></script>
<script src="http://bygstudio.com/js/organictabs.jquery.js"></script>
<script src="http://bygstudio.com/js/jquery.color.js"></script>
<script src="http://bygstudio.com/js/cufon-yui.js"></script>
<script src="http://bygstudio.com/js/byg3_400.font.js"></script>
<script src="http://bygstudio.com/js/detect.js"></script>
<script src="http://bygstudio.com/admin/mediaelementjs/build/mediaelement-and-player.min.js"></script> 
<script src="http://bygstudio.com/admin/slider/js/jquery.anythingslider.js"></script>
<script src="http://bygstudio.com/admin/slider/js/jquery.anythingslider.video.js"></script>
<!-- Flash Background -->	
<script src="js/ufo.js"></script>
<script>
var FO = { movie:"flash/background.swf", width:"1100", height:"600", majorversion:"8", build:"0", xi:"true", wmode:"transparent" };
UFO.create(FO, "flashContent");
</script> 
</head>
<body>
<!-- Flash Background -->
<div id="testcommand">
<?php $flashback = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
	width="1100" height="600"
	codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab">
	<param name="movie" value="flash/background.swf">
	<param name="play" value="false">
	<param name="loop" value="false">	
	<param name="quality" value="high">
	<param name="scale" value="noscale">
	<param name="bgcolor" value="#000000">
	<param name="wmode" value="transparent">
	<param name="align" value="tl">
	<embed name="testcommand" src="flash/background.swf" width="1100" height="600"
	play="false" loop="false" quality="high" scale="noscale" bgcolor="#000000"
	wmode="transparent" align="tl" swliveconnect="true"
	pluginspage="http://www.macromedia.com/go/flashplayer/">
	</embed>
	</object>'; 
echo $flashback; 
class Spots {
	private $mysqli;
	public function __construct()
	{
		$this->mysqli = new mysqli("localhost","bygfilms_cliente","samanthaABADIA2011#","bygfilms_clientes");
		if ($this->mysqli->connect_errno)
			die('No se pudo conectar a la base de datos: '.$this->mysqli->connect_errno.' '.$this->mysqli->connect_error);	
                $this->mysqli->set_charset("utf8");
	}
	public function get_spots($dire)
	{
		$this->tipo = $dire;
		if (strlen($this->tipo) > 12)
			$this->tipo = substr($this->tipo,12);
		else 
		{
			$this->tipo = substr($this->tipo,6,4);
			if ($this->tipo == 'vide')
				$this->tipo = 'music';
			if ($this->tipo == 'seri')
				$this->tipo = 'series';
		}
		$query = "SELECT fname, img_name FROM byg_clips WHERE type='$this->tipo'";
		if ( ! $this->mysqli->query($query))
			die('No se pudo extraer datos de la tabla!: '.$this->mysqli->errno.' '.$this->mysqli->error);
		$result = $this->mysqli->query($query);
		$imagelist = array();
		$videolist = array();
		$i=0;
		if ($result)
		{
			while($row = $result->fetch_array(MYSQLI_ASSOC))
			{
				if ($row['fname'] == '')
				{
					$imagelist[] = strtr($row['img_name'],' ','_').'.jpg';
					$videolist[] = strtr($row['img_name'],' ','_').'.mp4';
				}
				else 
				{
					$imagelist[] = strtr($row['img_name'],' ','_');
					$videolist[] = strtr($row['fname'],' ','_');
				}
			}
			
			foreach ($imagelist as $value)
			{
	 echo "<li id=\"video$i\"><img src=\"http://bygstudio.com/$dire/$value\" onclick=\"playVideo(\'$dire/$videolist[$i]\',\'$i\')\"></li>";
				++$i;
			}
			return $videolist; 
		}
		return FALSE;
	}
        
        public function get_text()
        {
            $contentQuery = "SELECT content FROM pages_text WHERE name='mainpage'"; 
            $content = $this->mysqli->query($contentQuery);
            $text = $content->fetch_row();
            return $text[0];
        }
}
?>
</div>
<!-- Content Start -->
<div id="website">
<!-- Organic Tabs Content Starts -->	
<div id="tab-two">
<!-- Logo -->
<div id="logo"></div>
<!-- Menu Starts -->
<div id="items">	
<ul class="nav">
<li><a href="http://bygstudio.com/about.php" id="about" onclick="go('about')">ABOUT</a></li>
<li><a href="http://bygstudio.com/services.php" id="serv" onclick="go('serv')">SERVICES</a></li>
<li><a href="javascript:void(0)" id="ads">ADS</a>
<ul>
<li><a href="automotive" onclick="go('auto')">Automotive</a></li>
<li><a href="food" onclick="go('food')">Food & Bev</a></li>
<li><a href="health" onclick="go('health')">Health</a></li>
<li><a href="lifestyle" onclick="go('lifes')">Lifestyle</a></li>
</ul></li>
<li><a href="javascript:void(0)" id="tv">TELEVISION</a>
<ul>
<li><a href="series" onclick="go('series')">Series</a></li>
<li><a href="videos" onclick="go('videos')">Music videos</a></li>
</ul></li>
<li><a href="cinema" id="cine" onclick="go('cine')">CINEMA</a></li>
<!-- <li><a href="#blog" onclick="go('blog')">BLOG</a></li> -->
<li><a href="http://bygstudio.com/contact.php" id="contact" onclick="go('contact')">CONTACT</a></li>
</ul>
</div>
<!-- Wrapping Starts -->
<div class="list-wrap" id="seccion"> 
<!-- About Starts -->	
<div id="about">
<div id="container">
<!-- Text -->
<?php 
    $spots = new Spots;
    echo $spots->get_text();
?>
</div> 
</div>
</div>
</div>
</div>

<script>
/*var ancho = document.getElementById("tab-two");
ancho = ancho.offsetWidth;
var fontsize = parseInt((1.3125*ancho)/75)+"px";
var items = document.getElementById("items");
items.style.fontSize = fontsize;*/
var go =  function(lid)
{
if (document.getElementById("slider"))
stopAnythingSlider();
var preSeccion = '<p id="vname"></p><div id="container"><div class="wrapper"><ul id="slider">';
var postSeccion = '</ul></div></div>';
var seccion = document.getElementById("seccion");
var flashback = document.getElementById("testcommand");
var testcom = <?php echo json_encode($flashback); ?>;
function active () {
var chosen = {about:"about",serv:"serv",contact:"contact",auto:"ads",food:"ads",health:"ads",lifes:"ads",series:"tv",videos:"tv",cine:"cine"};
for (var field in chosen)
{
	if (field !== lid)
	document.getElementById(chosen[field]).style.color="";
}
document.getElementById(chosen[lid]).style.color="#bdbdbd";
}
switch (lid)
{
case "about":
flashback.innerHTML = "";	
seccion.innerHTML = '<div id="about"><div id="container"><h1>'+
'About '+'<span>'+'Byg Studio'+'</span></h1><p style="text-align:justify;text-justify:inter-word;">'+
'We are a company based in Mexico city with an extensive experience in film and TV post-production.'+
'<span>'+' BYG STUDIO'+'</span>'+' offers a whole range of specialized services including '+'<span>'+
'editing, color grading, animation, compositing and VFX '+'</span>'+
' for commercials, TV series and feature films for the USA and Latin American markets. We help directors and producers accomplish their ultimate vision using the '+
'<span>'+'foremost standards '+'</span>'+'and' +'<span>'+' digital technologies '+'</span>'+'available.'+'<br/><br/>'+
'Our company has a superb, flexible and dedicated team. We are proud of being a spirited teamwork, our passionate lead editor and Post-production Manager is Mr. '+
'<a href="http://www.imdb.com/name/nm2179589/"><span>'+' Camilo R. Abadía (http://www.imdb.com/name/nm2179589/)'+'</span></a>'+
', who nowadays is arguably one of the best editors based in Mexico and who is available for work worldwide.'+'</p></div></div>';
break;
case "serv":
flashback.innerHTML = "";
seccion.innerHTML = '<div id="services"><div id="container"><h1>'+'Services'+
'</h1><p style="text-align:justify;text-justify:inter-word;">'+
'Editing should be a seamless process, so well done than audiences should not be conscious of it: '+
'<span>'+'"The art of making public unaware the continuous feature they are watching was not just shot that way".'+
'</span></br></br>'+' We offer the following post-production services:'+'</p><br/><br/><marquee class="left" behavior="slide" direction="up"><ul><li>*'+' Editing'+'</li><li>*'+
' Color grading'+'</li><li>*'+' Animation (2D and 3D)'+'</li><li>*'+' VFX'+'</li><li>*'+' Compositing'+'</li><li>*'+
' Finishings & Online'+'</li></ul></marquee></div></div>';
break;	
case "contact":
flashback.innerHTML = "";
seccion.innerHTML = '<div id="contact"><div id="container"><h1>'+
'Contacts'+'</h1><marquee class="left" behavior="slide" direction="up" width="233" height="360"><p>'+
'CAMILO R. ABADIA'+'<br />'+
'Post-Production Director'+'<br /><a href="mailto:camilo.a@bygstudio.com">'+
'camilo.a@bygstudio.com'+'</a><br /><br />'+'LUIS EDUARDO ABADIA'+'<br />'+
'Manager and Marketing Director'+'<br /><a href="mailto:eduardo.a@bygstudio.com">'+
'eduardo.a@bygstudio.com'+'</a><br /><br />'+'LUISA FERNANDA HOYOS'+'<br />'+
'Public Relations'+'<br /><a href="mailto:luisa.h@bygstudio.com">'+'luisa.h@bygstudio.com'+
'</a><br /></p></marquee><div class="right"><p>'+'Prolongación Reforma 2000 P. 6, Col. Lomas de Santa Fé C.P. 01210, México D.F.'+
'<br />'+'Landline:+52 (55) 5255-2532'+'<br />'+'Cellular:+521 (55) 3493-9457'+
'<br /><br /><a href="http://twitter.com/#!/BYGSTUDIO" target="_blank"><img src="img/twitter.png">'+
'Follow us in Twitter'+'</a><br /><a href="http://www.facebook.com/BygStudio" target="_blank"><img src="img/facebook.png">'+
'Follow us in Facebook'+'</a></p></div></div></div>';
break;
case "auto": //Caso comerciales automoviles
seccion.innerHTML = preSeccion+'<?php
$dire = "video/spots/auto";
$vname = $spots->get_spots($dire); ?>'+postSeccion;
var index = <?php echo json_encode($vname); ?>;
break;
case "food": //Caso comerciales comidas y bebidas
seccion.innerHTML = preSeccion+'<?php 
$dire = "video/spots/food";
$vname = $spots->get_spots($dire); ?>'+postSeccion;
var index = <?php echo json_encode($vname); ?>;
break;
case "health": //Caso comerciales salud
seccion.innerHTML = preSeccion+'<?php 
$dire = "video/spots/health";
$vname = $spots->get_spots($dire); ?>'+postSeccion;
var index = <?php echo json_encode($vname); ?>;
break;
case "lifes": //Caso comerciales estilo de vida
seccion.innerHTML = preSeccion+'<?php 
$dire = "video/spots/lifes";
$vname = $spots->get_spots($dire); ?>'+postSeccion;
var index = <?php echo json_encode($vname); ?>;
break;
case "series": //Caso series televisivas
seccion.innerHTML = preSeccion+'<?php 
$dire = "video/series";
$vname = $spots->get_spots($dire); ?>'+postSeccion;
var index = <?php echo json_encode($vname); ?>;
break;
case "videos": //Caso videos musicales
seccion.innerHTML = preSeccion+'<?php 
$dire = "video/videos";
$vname = $spots->get_spots($dire); ?>'+postSeccion;
var index = <?php echo json_encode($vname); ?>;
break;
case "cine": //Caso peliculas cine
seccion.innerHTML = preSeccion+'<?php 
$dire = "video/cinema";
$vname = $spots->get_spots($dire); ?>'+postSeccion;
var index = <?php echo json_encode($vname); ?>;//Recupera el arreglo de nombres de videos desde la variable PHP
break;
}
active();
updateContainer();
flashback.innerHTML = testcom;
if (typeof index[0] !== "undefined")
{
	if (lid !== "about" && lid !== "serv" && lid !== "contact")
	{
	//llama la funcion de slider insertando el respectivo nombre de slide en el párrafo encima del video
	var vname = document.getElementById("vname");
	var namedot = index[0].indexOf(".");
	vname.innerHTML = "<span>1&nbsp;&nbsp;&nbsp;</span>" + index[0].charAt(0).toUpperCase() + (index[0].slice(1,namedot)).replace(/_/g," ");
	$(function(){
	 $('#slider')
	   .anythingSlider({autoPlay: true,onSlideComplete: function(slider) {namedot = index[slider.currentPage-1].indexOf("."); 
	   vname.innerHTML = "<span>" + slider.currentPage  + "&nbsp;&nbsp;&nbsp;</span>" 
	   + index[slider.currentPage-1].charAt(0).toUpperCase() + index[slider.currentPage-1].slice(1, namedot).replace(/_/g," ");
	   document.getElementById("vname").style.display="block";}, hashTags:false}) // add any non-default options here
	   .anythingSliderVideo(); // only add this if your slider includes supported videos (new v1.9)
	});
	}
}
function stopAnythingSlider() {
	if (typeof $('#slider').data('AnythingSlider') !== "undefined")
	{
		$('#slider').data('AnythingSlider').gotoPage(1);
	   	$('#slider').data('AnythingSlider').startStop(false);  
	}
}
}
//Llama la funcion que reproduce el respectivo video escogido del slider
var playVideo = function (t,i) {
var elemento = document.getElementById("video"+i);
elemento.innerHTML ="<video controls autoplay><source src='http://bygstudio.com/"+ t + "' type='video/mp4'><object width='480' height='286' type='application/x-shockwave-flash' data='admin/mediaelementjs/build/flashmediaelement.swf'><param name='movie' value='admin/mediaelementjs/build/flashmediaelement.swf'/><param name='flashvars' value='controls=true&file=http://bygstudio.com/" + t + "'/></object></video>";
// using jQuery
$('video').mediaelementplayer({videoWidth: 640,videoHeight: 435,success: function(media) {
        media.addEventListener('playing', function(media) {document.getElementById("vname").style.display="none";})}});
$('#slider').data('AnythingSlider').startStop(false);
}
<!-- Organic Tabs -->
	$(function() {
	$("#tab-two").organicTabs({
	"speed": 200
	});
	}); 
<!-- Menu -->
	$(document).ready(function(){
	$(".nav a").stop().hover(function() {
	$(this).stop().animate({ color: "#585858" }, 200);
	},function() {
    $(this).animate({ color: "#989898" }, 300);
	});
	});
<!-- Fade Menu -->
	$(document).ready(function(){
	$('.nav').fadeIn(2000);
	});
<!-- Fade Logo -->
	$(document).ready(function(){
	$('#logo').fadeIn(1000);
	});
<!-- Fade Sections -->
	$(document).ready(function(){
	$('#tab-two').fadeIn(2000);
	});
<!-- Cufon -->
	Cufon.replace('h1,h2,h3,h4', { fontFamily: 'byg3' });
</script>
</body>
</html>