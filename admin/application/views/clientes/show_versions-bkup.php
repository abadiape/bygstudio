<div id="list"><h1><?php echo ucfirst(strtolower($title)); ?></h1> 
    <?php 
        if ($versions['total_rows'] > 10)
        {
                if ($page)
                {
                        $i = ($page-1)*10;
                        if ($versions['total_rows']/($page*10) > 1)
                        $m = $i+10;
                        else $m = $versions['total_rows']; 	
                }
                else
                {
                        $i = 0;
                        $m = 10; 
                }
        }
        else 
        {
                $i = 0;
                $m = $versions['total_rows'];
        }
    ?>
    <?php if ($m == 0): ?>
        <h3>Aun NO se han subido versiones para este proyecto!</h3>;
    <?php endif; ?>

    <?php
        $trans = array('á'=>'aQ','é'=>'eQ','í'=>'iQ','ó'=>'oQ','ú'=>'uQ',' '=>'_');
        $setup=array('controls' => true, 'autoplay' => false, 'preload' => 'auto'); 
    ?>
    <ul>
    <?php for (;$i<$m;$i++): ?>
            <?php   
            $file = preg_replace('/\s\s+/', ' ', $versions['videos'][$i]['file_name']);
            $title = strtr($versions['videos'][$i]['proyecto'],$trans);
            $video = "uploads/" . $title . "/" . str_replace(' ','_',$file);
            $proyecto = $versions['videos'][$i]['proyecto'];
            $comments = "http://bygstudio.com/admin/index.php/versiones/" . $title . "/" . str_replace(' ','%20',$file) .
            "\n\n" . $versions['videos'][$i]['comments']; 
            ?>
        <li>
            <a class="boxlink" href="#videobox_<?=$i?>">
                <?= substr($file, 0, -4) ?>
            </a>
        </li>
        <table id="videobox_<?=$i?>" class="vercom">
            <tr>
                <td rowSpan="2" colspan="2">			
                <?php if (substr($versions['videos'][$i]['file_type'],6,3) === 'mp4' || strpos($_SERVER["HTTP_USER_AGENT"],'Chrome')): ?>               		
                    <video id="video<?= $i ?>" controls preload="none" width="480" height="286">
                        <source src="<?= base_url($video)?>" type="video/mp4" /> 
                        <object width="480" height="286" type="application/x-shockwave-flash" data="<?= base_url('mediaelementjs/build/flashmediaelement.swf') ?>">
                            <param name="movie" value="<?= base_url('mediaelementjs/build/flashmediaelement.swf') ?>" />
                            <param name="flashvars" value="controls=true&file=<?= base_url($video)?>" />
                        </object>
                    </video>
                <?php else: ?>
                        <object width="480" height="286" classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" 
                        codebase="http://www.apple.com/qtactivex/qtplugin.cab" class="spinner">
                            <param name="src" value="<?= base_url($video) ?>"/>
                            <param name="controller" value="true" />
                            <param name="autoplay" value="false" /> 
                            <embed src="<?= base_url($video) ?>" width="480" height="286"
                            scale="aspect" autoplay="false" controller="true"
                            type="video/quicktime"
                            pluginspage="http://www.apple.com/quicktime/download/">
                            </embed>
                        </object>
                <?php endif; ?>
                </td>
                <td class="filling">
                    &nbsp;
                </td>
                <th class="comments">Comentarios/anotaciones</th>
            </tr>
            <tr>
                <td class="filling">
                    &nbsp;
                </td>
                <td class="comments">
                    <textarea rows="18" cols="34" name="commentsv<?= $i ?>" id="commentsv<?= $i ?>">
        <?php if ($type === 'admin') echo set_value('commentsv' . $i, $comments) ?>
                    </textarea>
                </td>
            </tr>;
            <tr>
                <th colspan="1" onClick="goPlay('<?= $versions["videos"][$i]["id"] ?>')">
                    <a href="javascript:void(0)">Play HD</a>
                </th>
                <th colspan="1" onClick="goDown('<?= $versions["videos"][$i]["id"] ?>')">
                    <a href="javascript:void(0)">Download</a>
                </th>
            <?php if ($type === 'admin'): ?>
                <th colspan="1" onClick="delVid('<?= $file ?>', '<?= $versions['videos'][$i]['id'] ?>', '<?= $video ?>')">
                    <a href="javascript:void(0)">Eliminar</a>
                </th>
            <?php endif; ?>
                <td class="filling">
                    <img src="<?=base_url('images/comments.png'); ?>" alt="Comentar" height="21" width="21">
                </td>
                <td class="comments" colspan="1">
                    <input type="button" value="Enviar" name="Send<?=$i?>" onclick="savecomments('<?=$versions["videos"][$i]["id"]?>','<?=$i?>')" />
                </td>
            </tr>
        </table>
    <?php endfor; ?>
    </ul>
        <h5 id="pages"><?php if ($pages) echo "Páginas: " . $pages; ?></h5>
</div>
<input type="hidden" id="numFilas" value="<?= $m ?>"  />

<script>
    	var goPlay = function(p) 
    	{
            window.open("http://bygstudio.com/admin/index.php/versiones/"+p);
	};
	
	var goDown = function(d)
	{
            window.open("http://bygstudio.com/admin/index.php/versiones/download/"+d);
		
	};
	
	var savecomments = function(id,index)
	{
            var texto = document.getElementById("commentsv"+index).value;
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

            request.open("GET","/admin/index.php/savecomments?info1="+id+
            "&info2="+texto,true);
            request.onreadystatechange = savec;
            request.send(null);			
	}
        //Llama controlador que borra un video del listado de versiones al hacer clic sobre este, primero pide confirmación.	
	var delVid = function(video,id,archivo)
	{
            resp = confirm("Seguro desea borrar el video "+video+" ?");
            if (!resp)
                window.alert("El video "+video+" NO será borrado!");
            else
            {
            if (window.XMLHttpRequest)
                    request = new XMLHttpRequest();
            function deletev()
            {
                if (request.readyState == 4 && request.status == 200)
                    if (request.responseText)
                    {
                        window.alert("El video "+video+", ha sido borrado!");
                        location.reload();
                    }
                    else window.alert("El video "+video+", NO pudo ser borrado!");
            }
            request.open("GET","/admin/index.php/delvideo?infoid="+id+"&infofile="+archivo,true);
            request.onreadystatechange = deletev;
            request.send(null);
            }	
	};
        
        $(document).ready(function() {
                $('video').mediaelementplayer();
		$(".fancybox").fancybox();
                $('.boxlink').click(function() {
                    $.fancybox({
                        'type': 'inline',
                        'href': this.href,
                        'wrapCSS' : 'vercom',
                        beforeShow : function() {
                             $('.fancybox-skin').css({
                                 'background' :'#484848',
                                 'border-radius':'0.8125em', 
                                 'border':'solid 0.125em #c6d73d'
                             });
                        }
                    });
                });
                
                $('.filling').click(function() {
                    $('.comments').toggle();
                    //$('.filling').hide();
                    $.fancybox.update();
                });
        });
</script>