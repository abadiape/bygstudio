<div>
<?php
	$trans = array('á'=>'aQ','é'=>'eQ','í'=>'iQ','ó'=>'oQ','ú'=>'uQ',' '=>'_');
	$video = "uploads/".strtr($version['video']['proyecto'],$trans).'/'.str_replace(' ','_',$version['video']['file_name']);
	if ($title === 'secure')
		$video = "uploads/".strtr($version['video']['proyecto'],$trans).'/secure/'.str_replace(' ','_',$version['video']['file_name']);
	//echo "<tr><td class='celda'>";
	if (substr($version['video']['file_type'],6,3) === 'mp4') //&& !strpos($_SERVER["HTTP_USER_AGENT"],'Firefox')
	{ 		
		echo "<video id='video1' style='margin-left: 2%' controls autoplay width='1024' height='768'>
		<source src='".base_url($video)."' type='video/mp4' />
  		<object width='1024' height='768' type='application/x-shockwave-flash' data='".base_url("mediaelementjs/build/flashmediaelement.swf")."'>
        	<param name='movie' value='".base_url("mediaelementjs/build/flashmediaelement.swf")."' />
        	<param name='flashvars' value='controls=true&file=".base_url($video)."' />
    		</object>
  		</video>";
  	}
  	else
  	{ 
		echo "<object style='margin-left: 2%' width='1024' height='768' classid='clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B' 
 		codebase='http://www.apple.com/qtactivex/qtplugin.cab' class='spinner'>
 		<param name='src' value=".base_url($video)."/>
 		<param name='controller' value='true' />
 		<param name='autoplay' value='true' /> 
 		<embed src=".base_url($video)." width='1024' height='768'
 		scale='aspect' autoplay='true' controller='true'
 		pluginspage='http://www.apple.com/quicktime/download/'>
 		</embed>  	
  		</object>";
  	}
?>
</div>
<script>
// using jQuery
$('video').mediaelementplayer();
</script>