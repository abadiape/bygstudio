<table id="list_title">
    <tr>
        <th class="producers"><?=$title?>&nbsp;</th>
        <td>&nbsp;</td>
    <?php if ($title !== "Resultados Busqueda"): ?>
        <td class="producers crear">
            <a class="addclient_popup" href="#container" data-toggle="tooltip" title="Agrega una Productora."><h1>(+)</h1></a>
        </td> 
    <?php endif; ?> 
        <th class="producers">&nbsp;/&nbsp;</th>
        <th class="clips-admin-title" data-toggle="tooltip"><?=$clips_admin_title?>&nbsp;</th><th class="clips-admin-title"><span class="glyphicon glyphicon-triangle-bottom"></span></th>
    </tr>
</table>
<?php echo validation_errors(); ?>
<div class="order_button">
    <input style="color:#c6d73d" id="orderby" type="button" value="&#x25BC" onClick="sorting()" data-toggle="tooltip" title="Invierte el orden alfabético del listado."/>
    <a style="color:#929292; margin-left: 2.0em;" href="javascript:void(0)" data-toggle="tooltip" title="Posible borrar (clic izq. sobre el nombre), las que NO tienen proyectos."><span class="glyphicon glyphicon-trash"></span></a>
</div>
<div id="container" class="container" style="display: none">
    <h1><?php echo $title ?></h1></br>
    <?php echo validation_errors(); ?> 
    <p>Llene la forma con los datos correspondientes y haga clic en <em>"Crear cliente".</em></p></br>
    <?php echo form_open(site_url('crear')) ?>
        <div id="dataclient">
            <h5>Datos productora</h5>
            <ul>
                <li>Nombre:&nbsp;<input id="cliente" type="text" name="cliente" value="" /></li>
                <li>RFC:&nbsp;<input id="rfc" type="text" name="rfc" value="" /></li>
                <li>Address:&nbsp;<input id="address" type="text" name="address" value="" /></li>
                <li>Phone:&nbsp;<input id="tel" type="text" name="tel" value="" /></li>
                <li><input type="submit" name="create" value= "Crear cliente"></li>
            </ul>
        </div>
        <div id="datacontact">
            <h5>Datos contacto</h5>
            <ul>
                <li>Contacto:&nbsp;<input id="contact1" type="text" name="contact1" value="" /></li>
                <li>E-mail:&nbsp;<input id="correo1" type="text" name="correo1" value="" /></li>
                <li>Phone:&nbsp;<input id="tel1" type="text" name="tel1" value="" /></li>
                <li>Usuario:&nbsp;<input id="user1" type="text" name="user1" value="" /></li>
                <li>Password:&nbsp;<input id="pwd1" type="text" name="pwd1" value="" /></li>
            </ul>
        </div>
    </form>
</div>
<div id="producers_list">
    <div class="tse-scrollable wrapper">
        <div class="tse-content">
            <ul class="clientslist">
               <?php foreach ($clientes as $clientes_item): ?> 
               <?php if (!empty($clientes_item['cliente'])): ?>	
                <li class="has-sub">
                    <a id="prod_<?=$clientes_item['id']?>" href="javascript:void(0)" <?php if ((int) $clientes_item['numprojects'] === 0) echo 'onclick = del_customer(' . $clientes_item['id'] . ')';?>><?=strtoupper($clientes_item["cliente"])?></a>
                  <ul>
                     <li class="has-sub"><a href="javascript:void(0)">Proyectos</a>
                        <ul>
                           <li><a href="javascript:void(0)" onClick = "show('<?=site_url('show/'.$clientes_item['id'])?>',
                                            '<?=$clientes_item['cliente'] ?>')">Listado <span class="badge"><?=$clientes_item['numprojects']?></span></a></li>
                           <li class="last"><a class="additem_popup" href="<?=site_url('agregar/'.$clientes_item['id'])?>">Agregar nuevo</a></li>
                        </ul>
                     </li>
                     <li class="has-sub"><a href="javascript:void(0)">Información</a>
                        <ul>
                           <li>
                               <p><em>RFC: </em><?=$clientes_item['rfc']?></a></p>
                           </li>
                           <li>
                               <p><em>Address: </em><?=$clientes_item['address']?></p>
                           </li>
                           <li class="last">
                               <p><em>Tel: </em><?=$clientes_item['tel']?></p>
                           </li>
                        </ul>
                     </li>
                     <li class="has-sub last">
                         <a href="javascript:void(0)">Contactos</a>
                         <ul>
                            <li>
                                <a class="addcontact_popup" href="#newcontact<?=$clientes_item['id']?>"><img src="<?=base_url('images/add-contact.png')?>" alt="Agregar nuevo" height="34" width="34">    
                                </a>
                            </li>
                         <?php for ($i=0;$i<count($clientes_item['contactos']);$i++): ?>
                         <?php if ( ! empty($clientes_item['contactos'][$i])): ?>
                            <li <?php if ($i === count($clientes_item['contactos'])) echo "class='last'" ?> id="<?='editmode'.$clientes_item['id'].'i'.$i?>">
                                <div <?php if ($i%2 !== 0) echo "class='odd_contact'" ?> onClick="contactEdit('<?=$clientes_item['id'].'i'.$i?>','<?=$clientes_item['cliente']?>')">                            
                                        <p id="<?='contactos'.$clientes_item['id'].'i'.$i?>">
                                            <?=$clientes_item['contactos'][$i]['contact']?> <span style="margin-left: 1.0em;" id="<?='minusedit'.$clientes_item['id'].'i'.$i?>" class="glyphicon glyphicon-trash" onclick ="event.stopPropagation(); eliminar('<?=$clientes_item['id'].'i'.$i?>','<?=$clientes_item['cliente']?>')">
                                        </p>                            
                                    <a style="padding-bottom: 5px" id="<?='correos'.$clientes_item['id'].'i'.$i?>" href="mailto:<?=$clientes_item['contactos'][$i]['correo']?>" onclick="event.stopPropagation();"><?=$clientes_item['contactos'][$i]['correo']?></a>
                                    <p style="margin-bottom: 8px" id="<?='phones'.$clientes_item['id'].'i'.$i?>">
                                        <?=$clientes_item['contactos'][$i]['tel']?>
                                    </p>
                                </div>
                            </li>
                        <?php endif; ?>
                        <?php endfor; ?>   	   	
                         </ul>
                         <div class="newcontact" id="newcontact<?=$clientes_item['id']?>" style="display: none">
                            <h1><?php echo 'Contactos '.$clientes_item['cliente'] ?></h1></br>
                            <?php echo validation_errors(); ?> 
                            <p style="color: #929292">Llene las casillas correspondientes y haga clic sobre <em>"Agregar".</em></p>
                            <p style="color: #929292">Asegurese correspondan a un contacto de <?=$clientes_item['cliente']?>.</p></br>
                            <?php echo form_open(site_url('newcontact/'.$clientes_item['id'])) ?>
                                <div id="datacontact">
                                    <ul>
                                        <h5>Datos contacto</h5><br/>
                                        <input id="nameclient" type="hidden" name="nameclient" value="<?=$clientes_item['cliente'] ?>" />
                                        <li>
                                            <label for="contactn">Nombre: </label>
                                            <input id="contactn" type="text" name="contactn" value="" />
                                        </li>
                                        <li>
                                            <label for="correon">E-mail: </label>
                                            <input id="correon" type="text" name="correon" value="" />
                                        </li>
                                        <li>
                                            <label for="teln">Tel: </label>
                                            <input id="teln" type="text" name="teln" value="" />
                                        </li>
                                        <li>
                                            <label for="usern">Usuario: </label>
                                            <input id="usern" type="text" name="usern" value="" />
                                        </li>
                                        <li>
                                            <label for="pwdn">Password: </label>
                                            <input id="pwdn" type="text" name="pwdn" value="" />
                                        </li>
                                        <li>
                                            <input type="submit" name="addc" value= "Agregar" onClick="creado()">
                                        </li>
                                    </ul>
                                </div>
                            </form>
                         </div>
                     </li>
                  </ul>
               </li>
               <?php endif; ?>
               <?php endforeach; ?>
            </ul>
        </div>       
    </div>       
</div>
<br/>
<!--Beginning of HTML clips administration section.-->
<div id="clipspace" style="display: none">
    <div class="add-category">
        <input type="button" id="add-category" value="Nueva Categoría" data-toggle="tooltip" title="Crea una sección de clips.">
    </div>
    <!--<p>Puede reordenar clips (dentro de una misma categoría), arrastrándolos hacia otro lugar y allí aparecerán en la página de BYG.</p>-->
    <?php
    $trans = array(' ' => '_');
    $clipsNames = array();
    $clipsImages = array();
    $clipsVisibility = array();
    $clipsInBanner = array();
    $categoriesOrder = $categoriesPath = $categoriesVisibility = $categoriesCreatedAt = $categoriesUpdatedAt = array();     
    $categoriesCreatedByUser = $categoriesId = array();
    foreach ($clips as $clip): ?>
        <?php 
            $clipType = $clip['type'];
            $listOrder = $clip['list_order'];
            $visibility = $clip['visibility'];
            $inBanner = $clip['in_banner'];
            $clipImage = strtr($clip['img_name'],$trans);
            if (! strpos($clipImage, '.'))
            {
                $clipImage = $clipImage . '.jpg';
            } 
            if  ($clip['title'] !== "")
            {
                $clipName = strtr($clip['title'],$trans);
                /*if (strpos($clipName, '.'))
                {
                  $clipName = substr($clipName,0,-4);
                }*/
            }       
            else 
            {
               $clipName = $clip['img_name'];
               if (strpos($clipName, '.'))
               {
                  $clipName =  substr($clip['img_name'],0,-4);
               }  
            }    
            $clipsNames[$clipType][$listOrder] = $clipName;
            $clipsImages[$clipType][$listOrder] = $clipImage;
            $clipsVisibility[$clipType][$listOrder] = $visibility;
            $clipsInBanner[$clipType][$listOrder] = $inBanner;
            $categoriesId[$clipType] = $clip['category_id'];
            $categoriesName[$clipType] = $clip['name'];
            $categoriesOrder[$clipType] = $clip['category_order'];
            $categoriesPath[$clipType] = $clip['path'];
            $categoriesVisibility[$clipType] = $clip['visible'];
            $categoriesCreatedAt[$clipType] = $clip['created_at'];
            $categoriesUpdatedAt[$clipType] = $clip['updated_at'];
            $categoriesCreatedByUser[$clipType] = $clip['by_user'];
        ?>
    <?php endforeach; ?>
    <?php 
        $keys = array_keys($clipsImages); 
        $categoriesNumber = count($keys);
    ?>
    <?php foreach ($keys as $key): ?>
        <?php $type = $key; 
            switch ($type)
            {
                case 'auto':
                        $tipo = 'video/spots/' . $type . '/';
                        break;
                case 'food':
                        $tipo = 'video/spots/' . $type . '/';
                        break;    
                case 'cine':
                        $tipo = 'video/cinema/';
                        break;
                case 'lifes':
                        $tipo = 'video/spots/' . $type . '/';
                        break;
                default:
                        $tipo = 'admin/uploads/byg_clips/' . $type . '/images/';
            } 
            $count = 0;
        ?>
    <table class="clips-admin<?php if ($categoriesOrder[$key] === '1') echo ' active'; ?>">
        <tr><th class="category-label<?php if ((int) $categoriesOrder[$key] === 1) {echo ' active';}?>">Categoría:</th>
            <th id="clips-admin_<?php echo $key ?>" class="category-show"><span class="glyphicon glyphicon-triangle-<?php if ((int) $categoriesOrder[$key] === 1) {echo 'top active';} else {echo 'bottom';} ?>"></span></th>
            <th class="clip-category-name"><input id="<?php echo 'name_' . $key . '_' . $categoriesId[$key] ?>" type="text" maxlength="16" value="<?php echo ucwords($categoriesName[$key]); ?>" data-toggle="tooltip" title="Nombre de la categoría en listado de página BYG."></th>
            <th class="clip-category-visible"><input type="checkbox" id="<?php echo 'visible_' . $key . '_' . $categoriesId[$key] ?>" value="<?php echo $key ?>" <?php if ($categoriesVisibility[$key] === '1') echo "checked"; ?>><label data-toggle="tooltip" title="Mostrar (o no) esta categoría en la página de BYG.">&nbsp;Visible</label></th>
            <th class="clip-category-position">
                <label data-toggle="tooltip" title="Lugar de esta categoría en listado de página BYG.">Posición:&nbsp;</label>
                <select id="<?php echo 'position_' . $key . '_' . $categoriesId[$key] ?>">
                    <?php for ($i=1; $i <= $categoriesNumber; $i++): ?>
                    <option value="<?php echo $i ?>" <?php if ($i === (int) $categoriesOrder[$key]) echo ' selected="selected"' ?>><?php echo $i ?></option>
                    <?php endfor; ?>
                </select>
            </th>
            <th class="clip-category-add<?php if ($categoriesOrder[$key] === '1') echo ' active'; ?>"><input type="button" id="<?php echo 'upload_' . $key . '_' . $categoriesId[$key] ?>" value="Nuevo Clip" data-toggle="tooltip" title="Sube clip a la categoría."></th>
        </tr>
    </table>
    <div id="upload_space_<?php echo $key ?>">
    </div>
    <table id="clips-<?php echo $key ?>" class="clips<?php if ((int) $categoriesOrder[$key] === 1) echo ' active'; ?>">
        <tr>
        <?php foreach ($clipsNames[$key] as $nameNumber => $name): ?>        
            <?php 
                ++$count;
                if (($count % 2) === 0)
                        echo '<th class="clip-title ' . $key . '">';
                else echo '<th class="clip-title ' . $key . '">';     
            ?>
        <input id="<?php echo 'title_' . $key . '-' . $nameNumber ?>" type="text" maxlength="24" value="<?php echo ucwords($name); ?>">
            </th>
        <?php endforeach; ?>
        </tr>
        <tr class="clip-image-container">
        <?php foreach ($clipsImages[$key] as $imageNumber => $image): ?>
            <td class="clip-image <?php echo $key ?>">
            <?php
                $imgVisible = $clipsVisibility[$key][$imageNumber];
                $imgClass = '';
                if (! $imgVisible || $categoriesVisibility[$key] === '0')
                {
                    $imgClass = 'not-visible';
                }
                echo '<img id="img_' . $key . '-' . $imageNumber.'" src="https://bygstudio.com/' . $tipo . $image.'" 
                draggable="true" width="144" height="89" class="' . $imgClass . '">';
            ?>
                </img>
                <button type="button" id="<?php echo 'delete_' . $key . '-' . $imageNumber ?>"><span class="glyphicon glyphicon-remove"></span></button>
            </td>
        <?php endforeach; ?>
        </tr>
        <tr>
        <?php foreach ($clipsVisibility[$key] as $clipVisibility => $clip_visibility): ?>
            <td class="clip-visible <?php echo $key ?>"> 
                <input type="checkbox" id="<?php echo 'visibility_' . $key . '-' . $clipVisibility ?>" <?php if ($clip_visibility && $categoriesVisibility[$key] === '1') {echo ' checked=checked';} elseif ($categoriesVisibility[$key] === '0') {echo ' disabled=disabled';} ?>><label>&nbsp;Visible en página</label>             
            </td>
        <?php endforeach; ?>
        </tr>
        <tr>
        <?php foreach ($clipsInBanner[$key] as $clipInBanner => $clip_inbanner): ?>
            <?php $imgVisible = $clipsVisibility[$key][$clipInBanner]; ?>
            <td class="clip-banner <?php echo $key ?>"> 
                <input type="checkbox" id="<?php echo 'banner_' . $key . '-' . $clipInBanner ?>" <?php if ($clip_inbanner && $imgVisible && $categoriesVisibility[$key] === '1') {echo ' checked=checked';} elseif (! $imgVisible  || $categoriesVisibility[$key] === '0') {echo ' disabled=disabled';} ?>><label>&nbsp;Banner principal</label>             
            </td>
        <?php endforeach; ?>
        </tr>
        <tr>
        <?php foreach ($clipsImages[$key] as $imageNumber => $image): ?>
            <td class="clip-change_image <?php echo $key ?>"> 
                <input type="button" id="<?php echo 'upload-img_' . $key . '-' . $imageNumber ?>" value="Cambiar imagen">             
            </td>
        <?php endforeach; ?>
        </tr>
    </table>
    <?php endforeach; ?>
    <input id="categories_number" type="hidden" value="<?php echo ($categoriesNumber+1) ?>"/>
</div>
<!--End of HTML clips administration section.-->
<script>
    var show = function(page,client)
    {
        window.open(page,'width=610, height=800');		
    };
    
    var eliminar = function(idi,cliente)
    {
        var contact = document.getElementById("contactos"+idi);
        var correo = document.getElementById("correos"+idi);
        var phone = document.getElementById("phones"+idi);
        var minusedit = document.getElementById("minusedit"+idi);
        var contactname = contact.innerHTML;
        var imgposition = contactname.indexOf("<span");
        contactname = (contactname.substring(0, imgposition)).trim();
        var borrar = window.confirm("Are you sure about deleting contact  '"
        +contactname+"'  in "+cliente+"?"); 
        if(borrar !== true)
        {	
            window.alert(contactname+" wont be deleted in "+cliente);
        }
        else
        {
            if (window.XMLHttpRequest)
            {// code for IE7+, Firefox, Chrome, Opera, Safari
                request=new XMLHttpRequest();
            }
            else
            {// code for IE6, IE5
                request=new ActiveXObject("Microsoft.XMLHTTP");
            }

            function delcont()
            {
                if (request.readyState == 4 && request.status == 200)
                {
                    contact.innerHTML = " ";
                    correo.innerHTML = " ";
                    phone.innerHTML = " ";
                    minusedit.innerHTML = " ";
                    window.alert(request.responseText);
                }
            };

            request.open("GET","/admin/index.php/delcont?info="+contactname+"&idi="+idi,true);
            request.onreadystatechange = delcont;
            request.send(null);			
        }
    };

    var add = function(id,cliente)
    {
        var contact = document.getElementById("contactos"+id);
        var correo = document.getElementById("correos"+id);
        var phone = document.getElementById("phones"+id);
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            request=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            request=new ActiveXObject("Microsoft.XMLHTTP");
        };

        function addcont()
        {
            if (request.readyState == 4 && request.status == 200)
            {
                    window.alert((request.responseText));
            }
        };		
        request.open("GET","/admin/index.php/newcontact/"+id+"?info="+cliente,true);
        request.onreadystatechange = addcont;
        request.send(null);			
    }

    var contactEdit = function(idi,cliente)
    {
        var contact = document.getElementById("contactos"+idi);
        var correo = document.getElementById("correos"+idi);
        var phone = document.getElementById("phones"+idi);
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            request=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            request=new ActiveXObject("Microsoft.XMLHTTP");
        }

        function editc()
        {
            if (request.readyState == 4 && request.status == 200)
            {
                document.getElementById("editmode"+idi).innerHTML = request.responseText;
            }
        };
        request.open("GET","/admin/index.php/editcon?info1="+contact.innerHTML+
        "&info2="+correo.innerHTML+
        "&info3="+phone.innerHTML+
        "&idi="+idi,true);
        request.onreadystatechange = editc;
        request.send(null);			
    }

    var dataEdit = function(id,cliente)
    {
        var rfc = document.getElementById("rfc"+id);
        var address = document.getElementById("address"+id);
        var tel = document.getElementById("tel"+id);
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            request=new XMLHttpRequest();
        } 
        else
        {// code for IE6, IE5
            request=new ActiveXObject("Microsoft.XMLHTTP");
            //document.getElementById("data"+id).innerHTML = request.responseText;
        }

        function editd()
        {
            if (request.readyState == 4 && request.status == 200)
            {
                $("#data"+id).fancybox({
                     'width' : '55%',
                     'fitToView' : false,
                     'transitionIn' : 'none',
                     'transitionOut' : 'none',
                     'scrolling': 'no',
                     'content': request.responseText,
                     beforeShow : function() {
                         $('.fancybox-skin').css({
                             'background' :'#929292',
                             'border-radius':'0.8125em', 
                             'border':'solid 0.125em #c6d73d'
                         });
                        }
                });
            }
        }
        request.open("GET","/admin/index.php/editdata?info1="+rfc.innerHTML.substr(7)+
        "&info2="+address.innerHTML.substr(9)+
        "&info3="+tel.innerHTML.substr(5)+
        "&cliente="+cliente+
        "&id="+id,true);
        request.onreadystatechange = editd;
        request.send(null);		
    };

    var sorting = function()
    {
        orderby =  document.getElementById("orderby");
        var list = $('.clientslist');
        var listItems = list.children('li');
        list.append(listItems.get().reverse());
        if (orderby.value == "\u25BC")
            orderby.value = "\u25B2";
        else orderby.value = "\u25BC";
    }
    
    var creado = function()
    {
        var lleno1 = document.getElementById("contactn").value;
        var lleno2 = document.getElementById("correon").value;
        if (lleno1.trim() === "" || lleno2.trim() === "")
        {
            window.alert("Error, campos vacios introduzca datos!");
        }
        else
        {
            window.alert("El contacto ha sido creado");
        };
        return;
    };
    
    function del_customer(customer_id)
    {
        var customer = document.getElementById("prod_" + customer_id);
        var delcustomer = window.confirm("¿Está seguro de borrar la productora " + customer.innerHTML + "?");
        if(delcustomer !== true)
        {	
            window.alert("La productora "+customer.innerHTML+" No será eliminada.");
        }
        else
        {
            if (window.XMLHttpRequest)
            {// code for IE7+, Firefox, Chrome, Opera, Safari
                request=new XMLHttpRequest();
            }
            else
            {// code for IE6, IE5
                request=new ActiveXObject("Microsoft.XMLHTTP");
            }
            function clientdel()
            {
                if (request.readyState == 4 && request.status == 200)
                {
                    if (request.responseText)
                    {    
                        window.alert(request.responseText);
                        customer.innerHTML = ""; 
                    }
                    else 
                    {
                        window.alert("La productora "+ customer.innerHTML + " No pudo eliminarse!!");
                    }
                }
                else 
                {
                    window.alert("La productora "+ customer.innerHTML + " No pudo eliminarse!!");
                }
            }
            request.open("GET","/admin/index.php/delcustomer?info="+customer.innerHTML+"&id="+customer_id,true);
            request.onreadystatechange = clientdel;
            request.send(null);
        }
    }
    
    $(document).ready(function() {           
                $(".addclient_popup").fancybox({
                     'width' : '75%',
                     'type': 'inline',
                     'href': this.href,
                     'wrapCSS' : 'container',
                     beforeShow : function() {
                         $('.fancybox-skin').css({
                             'background' :'#000000',
                             'border-radius':'0.8125em', 
                             'border':'solid 0.125em #c6d73d'
                         });
                        },
                });
                
                $('.addcontact_popup').click(function() {
                    $.fancybox({
                        'type': 'inline',
                        'href': this.href,
                        'wrapCSS' : 'datacontact',
                        beforeShow : function() {
                             $('.fancybox-skin').css({
                                 'background' :'#000000',
                                 'border-radius':'0.8125em', 
                                 'border':'solid 0.125em #c6d73d'
                             });
                        }
                    });
                });
                
                $('[data-toggle="tooltip"]').tooltip(); 
        });
</script>
