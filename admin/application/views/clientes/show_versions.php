<div id="list"><h1><?php echo ucfirst(strtolower($title)) ?></h1> 
    <?php    
        $m = $versions['total_rows'];
        $j = $i = 0;
    ?>
    <?php if ($m == 0): ?>
        <h3>Aun NO se han subido versiones para este proyecto!</h3>
    <?php endif; ?>

    <?php
        $trans = array('á'=>'aQ','é'=>'eQ','í'=>'iQ','ó'=>'oQ','ú'=>'uQ',' '=>'_');
        $setup=array('controls' => true, 'autoplay' => false, 'preload' => 'auto'); 
    ?>    
    <div class="order_button" data-toggle="tooltip" title="Inicialmente ordenados por fecha, a partir de la mas reciente.">
        <span style="color:#ffffff">Ordenar por:</span>
        <label for="orderby_date" style="color:#929292" id="date_label">Fecha</label>
        <input style="color:#c6d73d" id="orderby_date" type="button" value="&#x25BC" onClick="sorting('date')"/>
        <label for="orderby_title" style="color:#929292" id="title_label">Título</label>
        <input style="color:#c6d73d" id="orderby_title" type="button" value="&#x25BC" onClick="sorting('title')"/>
    </div>
    <div class="tse-scrollable wrapper">
        <div class="tse-content">
            <ul class="versionslist">
            <?php for (;$i<$m;$i++): ?>
                <?php   
                    $file = preg_replace('/\s\s+/', ' ', $versions['videos'][$i]['file_name']);
                ?>
                <li <?php if ($i%2 !== 0) echo 'class="odd"' ?>>                        
                    <a class="boxlink" href="#videobox_<?=$i?>"><?php if (strpos($file, 'secure') !== false) echo substr($file, 7, -4); else echo substr($file, 0, -4) ?></a>                        
                </li>
            <?php endfor; ?>
            </ul>
        </div>
    </div>
        
    <?php for (;$j<$m;$j++): ?>
        <?php   
            $file = preg_replace('/\s\s+/', ' ', $versions['videos'][$j]['file_name']);
            $title = strtr($versions['videos'][$j]['proyecto'],$trans);
            $video = "uploads/" . $title . "/" . str_replace(' ','_',$file);
            $proyecto = $versions['videos'][$j]['proyecto'];
            $comments = "http://bygstudio.com/admin/index.php/versiones/" . $title . "/" . str_replace(' ','%20',$file) .
            "\n\n" . $versions['videos'][$j]['comments']; 
        ?>
        <table id="videobox_<?=$j?>" class="vercom">
            <tr>
                <td rowSpan="2" colspan="2">			
                <?php if (substr($versions['videos'][$j]['file_type'],6,3) === 'mp4' || strpos($_SERVER["HTTP_USER_AGENT"],'Chrome')): ?>               		
                    <video id="video<?= $j ?>" controls preload="none" width="480" height="286">
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
                    <textarea rows="18" cols="34" name="commentsv<?= $j ?>" id="commentsv<?= $j ?>">
        <?php if ($type === 'admin') echo set_value('commentsv' . $j, $comments) ?>
                    </textarea>
                </td>
            </tr>
            <tr>
                <th colspan="1" onClick="goPlay('<?= $versions["videos"][$j]["id"] ?>')">
                    <a href="javascript:void(0)">Play HD</a>
                </th>
            <?php if ($type === 'admin'): ?>
                <th colspan="1" onClick="goDown('<?= $versions["videos"][$j]["id"] ?>')">
                    <a href="javascript:void(0)">Download</a>
                </th>
                <th colspan="1" onClick="delVid('<?= $file ?>', '<?= $versions['videos'][$j]['id'] ?>', '<?= $video ?>')">
                    <a href="javascript:void(0)">Eliminar</a>
                </th>
             <?php else: ?>
                <th colspan="1">
                    <a href="javascript:void(0)">&nbsp;</a>
                </th>
            <?php endif; ?>
                <td class="filling">
                    <img src="<?=base_url('images/comments.png'); ?>" alt="Comentar" height="21" width="21">
                </td>
                <td class="comments" colspan="1">
                    <input id="mail_<?=$j?>" type="button" value="Enviar" name="Send<?=$j?>" onclick="savecomments('<?=$versions["videos"][$j]["id"]?>','<?=$j?>', '<?=$file?>', '<?=$title?>')" />
                </td>
            </tr>
        </table>
    <?php endfor; ?>
    <input type="hidden" id="numFilas" value="<?= $m ?>" />
</div>

<script>
    var goPlay = function(p) 
    {
        window.open("http://bygstudio.com/admin/index.php/versiones/"+p);
    };
	
    var goDown = function(d)
    {
        window.open("http://bygstudio.com/admin/index.php/versiones/download/"+d);

    };
	
    var savecomments = function(id,index, version, proyecto)
    {
        var texto = document.getElementById("commentsv"+index).value;
        texto = texto.trim();
        if (texto !== "")
        {
            var mailButton = document.getElementById("mail_"+index);
            mailButton.disabled = true;
            mailButton.value = "Enviando, por favor espere...";
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
                    mailButton.disabled = false;
                    mailButton.value = "Enviar";
                }
            };

            request.open("GET","/admin/index.php/savecomments?info1="+id+
            "&info2="+texto+"&info3="+version+"&info4="+proyecto,true);
            request.onreadystatechange = savec;
            request.send(null);
        }
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
                $.fancybox.update();
            });
            // Initialize scroller.
            $('.wrapper').TrackpadScrollEmulator();

            $('[data-toggle="tooltip"]').tooltip(); 
    });
        
    var sorting = function(sort_type)
    {
        orderby =  document.getElementById("orderby_"+sort_type);
        var list = $('.versionslist');
        var listItems = list.children('li').get();
        if (sort_type === 'date')
        {     
            list.append(listItems.reverse());
        }
        else
        {
            listItems.sort(function(a, b) {
            return $(a).text().toUpperCase().localeCompare($(b).text().toUpperCase());
            });
            $.each(listItems, function(idx, itm) { list.append(itm); });
            var dateLabel = document.getElementById("date_label");
            dateLabel.innerHTML = "Invertir";
            var titleLabel = document.getElementById("title_label");
            titleLabel.innerHTML = "";
            orderby.setAttribute("type", "hidden");
        }
        if (orderby.value === "\u25BC")
            orderby.value = "\u25B2";
        else orderby.value = "\u25BC";
    };
</script>