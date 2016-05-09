<?php if ($order == FALSE): ?> 
    <table id="title">
        <tr>
            <th><?=$title?>&nbsp;</th>
            <td>&nbsp;</td>
        <?php if ($title !== "Resultados Busqueda"): ?>
            <td class="crear">
                <a class="additem_popup" href="<?=site_url('crear') ?>"><h1>(+)</h1></a>
                <span class = "tooltip">Crea nuevo cliente </span>
            </td> 
        <?php endif; ?>    
        </tr>
    </table>
        <?php echo validation_errors(); ?>
    <div class="list">
        <input style="color:#c6d73d" id="orderby" type="button" value="&#x25BC" onClick="sorting()"/>
    </div>
<?php endif; ?>
    <div class="list" id="listado">
        <ul>
    <?php foreach ($clientes as $clientes_item): ?> 
        <?php if (!empty($clientes_item['cliente'])): ?>	
            <li><a href='javascript:void(0)'><?=$clientes_item['cliente']?></a>
                <ul>
                    <li>
                        <?php
                            $pos = strpos($clientes_item['cliente'],' ');
                            $cust = strtolower(substr($clientes_item['cliente'],0,$pos)); 
                        ?>
                        <div class="main">
                            <div class="bloqueizq">
                                <div id="<?='data'.$clientes_item['id']?>">
                                    <table class="data">  
                                        <tr>
                                            <th colspan="2" rowspan="1">
                                    <?php if (file_exists('images/logo_'.$cust.'.svg')): ?>
                                            <embed src="<?=base_url('images/logo_'.$cust.'.svg')?>"></embed>                                           
                                    <?php else: ?>
                                            <h5>Logo del cliente aquí<h5></br>                                       
                                    <?php endif; ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td id="<?='rfc'.$clientes_item['id']?>">RFC: <?=$clientes_item['rfc']?></td>
                                            <th class="infoedit">
                                                <a href="javascript:void(0)" onClick="dataEdit('<?=$clientes_item['id']?>','<?=$clientes_item['cliente']?>')">Editar</a>
                                                <span class = "tooltip">Permite modificar datos del cliente </span>
                                            </th>
                                        </tr> 
                                        <tr>
                                            <td colspan="2" id="<?='address'.$clientes_item['id']?>">Address: <?=$clientes_item['address']?></td>
                                        </tr> 
                                        <tr>
                                            <td colspan="2" id="<?='tel'.$clientes_item['id']?>">Tel: <?=$clientes_item['tel']?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>
                                    </table>
                                </div>
                                <table class="crearver">
                                    <tr>
                                        <td class="infopro" colspan="2" onClick = "show('<?=site_url('show/'.$clientes_item['id'])?>',
                                    '<?=$clientes_item['cliente'] ?>')"><a href="javascript:void(0)">Listado proyectos</a>
                                        <span class = "tooltip">Lista proyectos existentes </span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="infopro" colspan="2">
                                            <a class="additem_popup" href="<?=site_url('agregar/'.$clientes_item['id'])?>">Agregar proyecto</a>
                                            <span class = "tooltip">Crea un proyecto nuevo</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>	
                            <div class="bloqueder">
                                <table class="contitle">
                                    <tr>
                                        <th>
                                            <h2 style="text-decoration:underline;">Contactos:</h2>
                                        </th>
                                        <td>
                                            <a href="javascript:void(0)" onclick="show('<?=site_url('newcontact/'.$clientes_item['id'])?>',
                                                '<?=$clientes_item['cliente'] ?>')"><h3>(+)</h3></a>
                                        </td>
                                        <td>&nbsp;</td>
                                        </tr>
                                </table>     
            <?php for ($i=1;$i<=$clientes['m'];$i++):?>
                <?php if ( ! empty($clientes_item['contact'.$i])): ?>
                                <div id="<?='editmode'.$clientes_item['id'].'i'.$i?>">
                                    <table class="contactline">
                                        <tr>
                                            <th id="<?='contactos'.$clientes_item['id'].'i'.$i?>"><?=$clientes_item['contact'.$i]?></th>
                                            <td id="<?='editar'.$clientes_item['id'].'i'.$i?>">
                                                <a href="javascript:void(0)" onClick="contactEdit('<?=$clientes_item['id'].'i'.$i?>',
                                                    '<?=$clientes_item['cliente']?>')">&nbsp;Editar</a>
                                            </td>
                                            <td id="<?='minusedit'.$clientes_item['id'].'i'.$i?>">
                                                <a href="javascript:void(0)" 
                                        onClick = "eliminar('<?=$clientes_item['id'].'i'.$i?>','<?=$clientes_item['cliente']?>')">Borrar</a>
                                            </td>
                                        </tr> 
                                        <tr>
                                            <th colspan="3" id="<?='correos'.$clientes_item['id'].'i'.$i?>"><?=$clientes_item['correo'.$i]?></th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" id="<?='phones'.$clientes_item['id'].'i'.$i?>"><?=$clientes_item['tel'.$i]?></th>
                                        </tr>
                                        <tr>
                                            <td colspan="3">&nbsp;</td>
                                        </tr>
                                    </table>
                                </div>
                <?php endif; ?>
            <?php endfor; ?>   	   	
            </div>	
        <?php endif; ?>
                        </div>
                    </li>
                </ul>
            </li>
    <?php endforeach; ?>
        </ul>
    </div>

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
        var editar = document.getElementById("editar"+idi);
        var borrar = window.confirm("Are you sure about deleting contact "
        +contact.innerHTML+" in "+cliente+"?"); 
        if(borrar !== true)
        {	
            window.alert(contact.innerHTML+" wont be deleted in "+cliente);
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
                    editar.innerHTML = " "
                    minusedit.innerHTML = " ";
                    window.alert(request.responseText);
                }
            };

            request.open("GET","/admin/index.php/delcont?info="+contact.innerHTML+"&idi="+idi,true);
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
            document.getElementById("data"+id).innerHTML = request.responseText;
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
        listado = document.getElementById("listado");
        if (window.XMLHttpRequest)
                request = new XMLHttpRequest();
        function orderBy()
        {
            if (request.readyState == 4 && request.status == 200)
            {
                if (orderby.value == "\u25BC")
                        orderby.value = "\u25B2";
                else orderby.value = "\u25BC";
                listado.innerHTML = request.responseText;
            }	
        }
        request.open("GET","/admin/index.php/clientes?info="+orderby.value,true);
        request.onreadystatechange = orderBy;
        request.send(null);
    }
    
    $(document).ready(function() {           
                $(".additem_popup").fancybox({
                     'width' : '55%',
                     'fitToView' : false,
                     'transitionIn' : 'none',
                     'transitionOut' : 'none',
                     'type' : 'iframe',
                     beforeShow : function() {
                         $('.fancybox-skin').css({
                             'background' :'#000000',
                             'border-radius':'0.8125em', 
                             'border':'solid 0.125em #c6d73d'
                         });
                         //this.height = $('.additem_popup').contents().find('html').height();
                        },
                      //onComplete: $.fancybox.update();
                });
                                
                // Initialize scroller.
                $('.wrapper').TrackpadScrollEmulator();
        });
</script>