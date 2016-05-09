<div id="version">
    <ul>
        <li><h1>
        <?php 
        $trans = array('aQ'=>'á','eQ'=>'é','iQ'=>'í','oQ'=>'ó','uQ'=>'ú','_'=>' ');
        $file = preg_replace('/\s\s+/', ' ', $version['video']['file_name']);
        if ($title === 'secure')
        {
                $title1 = $version['video']['proyecto'];
                $title = str_replace(' ','_',$title1);
                $video = "uploads/".$title.'/secure/'.str_replace(' ','_',$file);
        }
        else
        {
                $title1 = strtr($title,$trans);
                $video = "uploads/".$title.'/'.str_replace(' ','_',$file);
        }
        echo $title1."</h1></li><li><h5>".substr($version['video']['file_name'],0,-4)."</h5></li></ul>"; 
        ?> 
        <?php	
	/*$still = substr(str_replace(' ','_',$file),0,-3)."png";
	$still = "uploads/".$title.'/thumbnails/'.$still;*/
	if (!strpos($_SERVER["HTTP_USER_AGENT"],'iPhone'))
		echo "<p id='loadStatus' style='text-align:left;color:#c6d73d';>Cargando por favor espere...</p></br>";
	echo "<table class='vercom'><tr><td rowspan='4'>&nbsp;</td><td colspan='3'>&nbsp;</td></tr><tr><td rowSpan='2' colspan='2'>";
	if (substr($version['video']['file_type'],6,3) === 'mp4' || strpos($_SERVER["HTTP_USER_AGENT"],'Chrome')) //&& !strpos($_SERVER["HTTP_USER_AGENT"],'Firefox')
	{ 		
            echo "<video id='videomp4' controls ";
            /*if (base_url($still))
            {
                    echo "poster='".base_url($still)."' ";
            }*/
            echo "preload='auto' width='480' height='286'>
            <source src='".base_url($video)."' type='video/mp4' />
            <object width='480' height='286' type='application/x-shockwave-flash' data='".base_url("mediaelementjs/build/flashmediaelement.swf")."'>
            <param name='movie' value='".base_url("mediaelementjs/build/flashmediaelement.swf")."' />
            <param name='flashvars' value='controls=true&file=".base_url($video)."' />
            </object>
            </video>";
  	}
  	else
  	{ 
            echo "<div id='mov'></div>";
  	}
	echo "</td><th>Comentarios</th></tr><tr><td rowspan='1'><textarea rows='17' cols='34' 
	name='commentsv' id='commentsv' onClick='delnote()'>"
	.set_value('commentsv','Por favor utilice este campo para enviarnos sus sugerencias o comentarios.')."</textarea></td></tr>";
	echo "<tr><th colspan='1' onClick='goPlay(".$version['video']['id'].")'><a href='javascript:void(0)'>Play HD</a></th>";
	echo "<th colspan='1' onClick='goDown(".$version['video']['id'].")'><a href='javascript:void(0)'>Download HD</a></th>";
	echo "<td colspan='1'><input type='button' value='Enviar' id='sendmail' name='Send' 
	onclick='savecomments(\"".$version['video']['id']."\",\"".substr($video,8)."\")' /></td></tr>";		
        ?>
    </table>
</div>

<script>
    var counter = 0;
    /* plays video in 1024x768 size */	
    var goPlay = function (p) 
    {
        window.close();
        window.open("http://bygstudio.com/admin/index.php/versiones/"+p);
    };
    /* downloads video to the users computeri */	
    var goDown = function(d) 
    {
        window.open("http://bygstudio.com/admin/index.php/versiones/download/"+d);	
    }
    /* waits for the mp4 video to start playing due to viewer action and invokes the visits counter*/
    if (document.getElementById("videomp4"))
    {	
        var video = document.getElementById("videomp4");
        counter++;
        video.addEventListener("playing", function () {
            if (window.XMLHttpRequest)
            {// code for IE7+, Firefox, Chrome, Opera, Safari
                    request=new XMLHttpRequest();
            }
            else
            {// code for IE6, IE5
                    request=new ActiveXObject("Microsoft.XMLHTTP");
            };

            function addmp4()
            {
                if (request.readyState == 4 && request.status == 200)
                {
                        document.getElementById("videomp4");
                }
            };
            /* Cuenta la visita siempre y cuando sea el primer play */
            if (counter < 2)
            {
                request.open("GET","/admin/index.php/addvisit?fname="+"<?php echo $file ?>",true);
                request.onreadystatechange = addmp4;
                request.send(null);
                counter++;
            }
        }, true);
        video.addEventListener('progress',function () 
        {
          var ranges = [];
          // percentage of video loaded
          for(var i = 0; i < video.buffered.length; i ++)
          {
            ranges.push([
                    video.buffered.start(i),
                    video.buffered.end(i)
                    ]);	
          }
          var proportion = 0;
          for(var i = 0; i < video.buffered.length; i ++)
          {
              proportion = parseInt(( (ranges[i][1] - ranges[i][0]) / video.duration )*100);
              document.getElementById("loadStatus").innerHTML = 'Cargando: ' + proportion + '% completo...';
          }
        }, false);
    }

    /* if it's a quicktime movie, it runs the ac_quicktime script for loading it */	
    if (document.getElementById("mov"))
    {
        document.getElementById("mov").innerHTML = QT_GenerateOBJECTText("<?php echo base_url($video); ?>" , 
        '480', '286', '', 'AUTOPLAY', 'False', 'SCALE', 'Aspect',  'obj#id', 'video1', 'emb#id', 'videoqt', 
        'emb#name', 'video1', 'postdomevents', 'True', 'EnableJavaScript', 'True');
    }

    /* define function that shows percentage of movie loaded */
    function showProgress()
    {
        var percentLoaded = 0 ;
        percentLoaded = parseInt((document.video1.GetMaxTimeLoaded() / document.video1.GetDuration()) * 100);
        document.getElementById("loadStatus").innerHTML = 'Cargando: ' + percentLoaded + '% completo...';
    }

    /* define function that executes when movie loading is complete */
   function movieLoaded()
    {
        document.getElementById("loadStatus").innerHTML = "Cargado!" ;
    }

    /* define function that executes when user hits Play button */	
    function playStarted()
    {
        document.getElementById("loadStatus").innerHTML = "";
        counter++;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
                request=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
                request=new ActiveXObject("Microsoft.XMLHTTP");
        };

        function addv()
        {
                if (request.readyState == 4 && request.status == 200)
                {
                        document.getElementById("loadStatus").innerHTML = "";
                }
        };
        /* Cuenta la visita siempre y cuando sea el primer play */
        if (counter < 2)
        {
                request.open("GET","/admin/index.php/addvisit?fname="+"<?php echo $file ?>",true);
                request.onreadystatechange = addv;
                request.send(null);
        }			
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
       RegisterListener('qt_progress', 'video1', 'videoqt', showProgress);
       RegisterListener('qt_load', 'video1', 'videoqt', movieLoaded);      
       RegisterListener('qt_play', 'video1', 'videoqt', playStarted);
    }

    var savecomments = function(id,projver) {
    var texto = document.getElementById("commentsv").value;
    document.getElementById("sendmail").disabled=true;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
            request=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
            request=new ActiveXObject("Microsoft.XMLHTTP");
    };

    function savec()
    {
            if (request.readyState == 4 && request.status == 200)
            {
                    window.alert(request.responseText);
            }
    };

    request.open("GET","/admin/index.php/sendcomments?info1="+id+
    "&info2="+texto+"&info3="+projver,true);
    request.onreadystatechange = savec;
    request.send(null);			
    }

    var delnote = function() {
        var texto = document.getElementById("commentsv");
        texto.value = "";
    }

    // using jQuery
    $('video').mediaelementplayer();
</script>