<table id="title">
    <tr>
        <th><?php if ($title !== 'Recarga ') echo $title.$projects['cliente'] ?></th>
    </tr>
</table>
<?php
    $atts = array(
                  'width'      => '480',
                  'height'     => '600',
                  'scrollbars' => 'yes',
                  'status'     => 'yes',
                  'resizable'  => 'yes',
                  'screenx'    => '800',
                  'screeny'    => '0'
        );
    $i = 0;
?>
<nav class="proyecto" id="listado"> 
    <input type="hidden" id="cliente_id" value="<?=$projects['id'];?>"/> 
    <div class="tse-scrollable wrapper">
        <div class="tse-content">
            <ul>
            <?php foreach ($projects['data'] as $data): ?>		
                <?php $proyecto = $data['project'] ?>
                <?php  if ($proyecto): ?>    
                <li class="dropdown" <?php  if ($i > 5) echo 'data-toggle="tooltip" title="Si al hacer clic, los sub-men&uacute;s solo son visibles parcialmente, use el scroll para desplazarse."'?>>
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?=$proyecto?>
                    <span class="caret"></span></button>
                    <?php 
                        $trans = array('á'=>'aQ','é'=>'eQ','í'=>'iQ','ó'=>'oQ','ú'=>'uQ',' '=>'_');
                        $proyecto = strtr($proyecto,$trans);
                        ++$i;
                    ?>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?=site_url('upload/'.$proyecto)?>" title="<?=$proyecto?>">Subir Video&nbsp;<span class="glyphicon glyphicon-upload"></span></a>
                        </li>
                        <li>
                            <a href="<?=site_url('upload/secure/'.$proyecto)?>" title="<?=$proyecto?>">Subir Video Seguro&nbsp;<span class="glyphicon glyphicon-circle-arrow-up"></span></a>
                        </li>
                        <?php if (isset($projects['numversions'][$i-1])): ?>
                        <li>
                            <a href="<?=site_url('versions/'.$proyecto)?>">Ver versiones&nbsp;<span class="badge"><?=$projects['numversions'][$i-1]?></span></a>
                        </li>
                        <?php endif;?>
                        <li>
                            <a href="javascript:void(0)" onClick="delProject('<?=$proyecto?>')">Eliminar proyecto&nbsp;<span class="glyphicon glyphicon-trash"></span></a>
                        </li>
                        <!--<li>
                            <a href="javascript:void(0)">Documentaci&oacute;n</a>
                            <ul>
                                <li>
                                    <a href="<?=site_url('subirdocs/'.$proyecto)?>">Subir</a>
                                </li>
                                <li>
                                    <a href="<?=site_url('verdocs/'.$proyecto)?>">Ver</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href='javascript:void(0)'>Comentarios</a>
                            <ul>
                                <li class="note"><?=anchor_popup(site_url('comments/'.$proyecto),'BYG', $atts)?></li>
                                <li class="note"><?=anchor_popup(site_url('notes/'.$proyecto),'Clientes', $atts)?></li>
                            </ul>
                        </li>-->
                    </ul>
                </li>
                <?php endif; ?>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>

<script>
    var delProject = function(project)
    {
        id = document.getElementById("cliente_id").value;
        resp = window.confirm("Está seguro de BORRAR el proyecto "+project+" ? Esta operación no se puede deshacer!");
        if ( ! resp)
            window.alert("El proyecto "+project+" NO será borrado.");
        else
        {
            if (window.XMLHttpRequest)
                request = new XMLHttpRequest();
            function delproj()
            {
                if (request.readyState == 4 && request.status == 200)
                    if (request.responseText)
                    {
                        window.alert("El proyecto "+project+" ha sido borrado!");
                        document.getElementById("listado").innerHTML = request.responseText;
                    }
                    else window.alert("El proyecto "+project+" NO pudo ser borrado!");
            }
            request.open("GET","/admin/index.php/delproject?info="+project+"&id="+id,true);
            request.onreadystatechange = delproj;
            request.send(null);
        }
    }
    
    $(document).ready(function() {
    	// Initialize scroller.
        $('.wrapper').TrackpadScrollEmulator();
		$('[data-toggle="tooltip"]').tooltip();    
    });
</script>