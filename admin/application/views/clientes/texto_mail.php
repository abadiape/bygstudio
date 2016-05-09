<?php 
    $n = $count;
    $correos = "";
    $file = str_replace(' ','%20',$file);
    if (isset($files_count))
    {
        for ($i=0; $i<$files_count; $i++)
        {
           $a = 'file' . $i;
           $$a = str_replace(' ','%20',$$a);
        }        
    }
    $trans = array('aQ'=>'á','eQ'=>'é','iQ'=>'í','oQ'=>'ó','uQ'=>'ú','_'=>' ');
    $proyecto = strtr($project,$trans); 
    if ($secure)
    {
        $user = substr(strtolower($project), 0, 5);
        $file = md5(str_replace('%20','',$file));
        $project = $project . '/secure';	
        $accessKey = substr(md5('sec' . $file), 0, 6);
        if (isset($files_count))
        {
            for ($i=0; $i<$files_count; $i++)
            {
               $a = 'file' . $i;
               $$a = md5(str_replace('%20','',$$a));
            }
            $accessKey = substr(md5('sec' . $file0), 0, 6);
        }
    }
    for ($i=1;$i<=$n;$i++)
    {
        if ($i === 1)
        {
            $correos .= ${'correo'.$i};
        }
        else $correos .= ", ".${'correo'.$i};
    }
?>
    <p>Verifique/agregue/modifique informaci&oacute;n pertinente, haga clic en "Enviar".</p></br>
    <?php echo form_open(site_url('sendmail')); ?>
        <table id="mail" class="mail">
            <tr><th>Destinatario(s):</th>
                <td>
                    <?php 
                        echo "<input type='text' value='".set_value('correos',$correos)."' name='correos' size='55' />";
                    ?>
                </td>
            </tr>
            <tr>
                <th>Asunto:</th>
                <td>
                    <input type="text" value="<?php echo set_value('subject','Nueva(s) versión(es) de su proyecto '.$proyecto); ?>" 
                name="subject" maxLength="55" size="55"/>
                </td>
            </tr>
            <tr><th>Texto:</th>
                <td>
                    <textarea id="texto" name="texto">
                        <!DOCTYPE HTML>
                        <html lang="es">
                            <head>
                                <meta http-equiv="Content-type" content="text/html; charset=utf-8"  >
                                <link rel="stylesheet" type="text/css" href=<?=base_url("css/byg_admin.css");?> />
                            </head>
                            <body>
                                <?php echo '<p>Estimado cliente, en la(s) p&aacute;gina(s): ';
                                    if ($secure)
                                    {
                                        echo '<span>(usuario: ' . $user . ' contraseña: ' . $accessKey . ')</span></p>';
                                    }
                                    else
                                        echo '</p>'; 
                                ?>
                                
                                <?php if (isset($files_count)): ?>
                                    <ul>
                                    <?php for ($i=0; $i<$files_count; $i++): ?>
                                        <?php
                                            $a = 'file' . $i;
                                            echo '<li><a href="http://bygstudio.com/admin/index.php/versiones/'.$project.'/'.$$a.'">
                                            http://bygstudio.com/admin/index.php/versiones/'.$project.'/'.$$a.'</a></li>';
                                        ?>
                                    <?php endfor; ?>
                                    </ul>                                                                
                                <?php    
                                    echo '<p style="text-align:justify;">Se encuentran disponibles nuevas versiones de su proyecto 
                                    <strong>'.$proyecto.'.</strong>
                                    Mediante el campo de comentarios puede enviarnos sus opiniones o sugerencias. Cordialmente,</p></br>'; 
                                ?>
                                <?php else: ?>
                                    <?php
                                       echo '<a href="http://bygstudio.com/admin/index.php/versiones/'.$project.'/'.$file.'">
                                            http://bygstudio.com/admin/index.php/versiones/'.$project.'/'.$file.'</a>';        
                                       echo '<p style="text-align:justify;">Se encuentra disponible nueva versi&oacute;n de su proyecto 
                                    <strong>'.$proyecto.'.</strong>                                
                                Mediante el campo de comentarios puede enviarnos sus opiniones o sugerencias. Cordialmente,</p></br>';
                                    ?>
                                <?php endif; ?>
                                <?php 
                                    echo '<p>'.$signed.'</p><strong>BYG STUDIO S.A. de C.V.</strong>
                                    <p>Prolongaci&oacute;n Reforma 2000, Lomas de Santa Fe - Álvaro Obreg&oacute;n, C.P. 01210</p>
                                    <p>México D.F. - México</p>
                                    <p>Teléfono:+52(55)52552532</p>'; 
                                ?>
                            </body>
                        </html> 
                    </textarea>
                </td>
            </tr>
            <tr>
                <th></th>
                <td>
                    <input type="submit" value="Enviar" id="sendmail" onclick="disable()" />
                </td>
            </tr>
        </table>	 
    </form>

<script>
    var disable = function()
    {
        document.getElementById("sendmail").disabled=true;
        document.getElementById("sendmail").value="Enviando, por favor espere...";
        for(var texto in CKEDITOR.instances)
        {
            CKEDITOR.instances.texto.updateElement();
        }
        CKEDITOR.instances.texto.getData();
    };

    CKEDITOR.replace('texto');
    CKEDITOR.config.toolbar = [
        ['Styles','Format','Font','FontSize'],
        '/',
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-',
        'Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-'],
        '/',
        ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['-','Link','TextColor','BGColor']						] ;
                //CKEDITOR.inline('texto');
</script> 