<table class="clips-admin active">
    <tr><th class="category-label active"><?php echo $label ?>:</th>
        <th id="clips-admin_<?php echo $code ?>" class="category-show"><span class="glyphicon glyphicon-triangle-top active"></span></th>
        <th class="clip-category-name"><input id="<?php echo 'name_' . $code . '_' . $id ?>" type="text" maxlength="16" value="<?php echo ucwords($name); ?>" data-toggle="tooltip" title="Nombre de la categoría en listado de página BYG."></th>
        <th class="clip-category-visible"><input type="checkbox" id="<?php echo 'visible_' . $code . '_' . $id ?>" value="<?php echo $code ?>" checked><label data-toggle="tooltip" title="Mostrar (o no) esta categoría en la página de BYG.">&nbsp;Visible</label></th>
        <th class="clip-category-position">
            <label data-toggle="tooltip" title="Lugar de esta categoría en listado de página BYG.">Posición:&nbsp;</label>
            <select id="<?php echo 'position_' . $code . '_' . $id ?>">
                <?php for ($i=1; $i <= $order; $i++): ?>
                <option value="<?php echo $i ?>" <?php if ($i === (int) $order) echo ' selected="selected"' ?>><?php echo $i ?></option>
                <?php endfor; ?>
            </select>
        </th>
        <th class="clip-category-add active"><input type="button" id="<?php echo 'upload_' . $code . '_' . $id ?>" value="Nuevo Clip" data-toggle="tooltip" title="Sube clip a la categoría."></th>
    </tr>
</table>
<div id="clips-upload_<?php echo $code ?>" class="clips-upload active">
    <p>Arrastre el VIDEO del primer clip y la respectiva FOTO; en cualquier orden, hacia el espacio debajo, para subirlos.</p>
    <?php
        $attributes = array('class' => 'dropzone', 'id' => 'dropzone-' . $code);
        echo form_open_multipart(site_url('uploadclip'),$attributes); 
    ?>
        <input id="tipo-<?php echo $code ?>" type="hidden" value="<?=$code;?>" name="type-<?php echo $code ?>"/>
        <input id="filename" type="hidden" name="filename"/>
    </form>
    <script>
        var myNewDropzone = new Dropzone("#dropzone-<?php echo $code ?>",
        { acceptedMimeTypes: "image/*,video/*",
          maxFilesize: 64,
          addRemoveLinks: true,
          maxFiles: 2,
          dictCancelUpload: "Remover Archivo",
          dictMaxFilesExceeded: "Máximo dos (2) archivos: el video y su still.",
          init: function() {
                this.on("success",function(file) { 
                    var fname = file.name;
                    var ftype = file.type;
                    fname = fname.replace(/\s/g,"_");
                    var pos = fname.indexOf(".");
                    pos = fname.substr(pos+1); 
                    if (window.XMLHttpRequest)
                            request = new XMLHttpRequest();	
                    function showNewClip()
                    {
                        if (request.readyState === 4 && request.status === 200)
                        {	
                            var uploadAnswer = request.responseText;
                            if (ftype.substr(0, 5) !== "image")
                            {    
                                window.alert(uploadAnswer);
                            }
                            else
                            {                                                                
                                if (uploadAnswer.indexOf("El nuevo clip") !== -1)
                                {
                                    var paragraphPos = uploadAnswer.indexOf("<p");
                                    uploadAnswer = uploadAnswer.substring(0, paragraphPos);
                                }
                                $(uploadAnswer).insertAfter("div.clips-upload.active");
                            } 
                            if (uploadAnswer.indexOf("El nuevo clip") !== -1)
                            {
                                myNewDropzone.removeAllFiles();
                                var newUploadSpace = "<div id='upload_space_<?php echo $code ?>'></div>";
                                $("#clips-upload_<?php echo $code ?>").replaceWith(newUploadSpace);                                
                                $("#name_<?php echo $code ?>_<?php echo $id ?>").attr('disabled', false);
                                $("#visible_<?php echo $code ?>_<?php echo $id ?>").attr('disabled', false);
                                $("#position_<?php echo $code ?>_<?php echo $id ?>").attr('disabled', false);
                                $("#upload_<?php echo $code ?>_<?php echo $id ?>").trigger('click');
                            }
                        }
                    };

                    request.open("GET","/admin/index.php/uploadclip?tipo="+document.getElementById("tipo-<?php echo $code ?>").value+"&ftype="+ftype,true);
                    request.onreadystatechange = showNewClip;
                    request.send(null);			
                } );
                this.on("addedfile", function(file) { 
                    var forbidden = ["<",">",":","\"","\\","/","|","*","?","[","]","=","%","$","+",",",";","~","á","é","í","ó","ú"];
                    var fname = file.name;
                    var ftype = file.type;
                    var match = -1;
                    for (var i=0;i<forbidden.length;i++)
                    {
                        match = fname.indexOf(forbidden[i]);
                        if (match !== -1)
                        {
                            window.alert("NO es válido usar ' "+forbidden[i]+" ' en el nombre de archivo. Por favor quítelo!");
                            window.location.reload(true);
                            break;
                        }	
                    }
                    if (ftype.substr(0,5) !== "image")
                    {
                        if (window.XMLHttpRequest)
                        {// code for IE7+, Firefox, Chrome, Opera, Safari
                                request=new XMLHttpRequest();
                        }

                        function warningfile()
                        {
                            if (request.readyState == 4 && request.status == 200)
                            {
                                if (request.responseText > 0)
                                {
                                    window.alert(file.name+" ya existe, y será reemplazado. Sino desea esto, cierre la página antes de terminar la carga.");
                                }
                                document.getElementById("filename").value = file.name;
                            }
                        };

                        request.open("GET","/admin/index.php/uploadclip?fname="+file.name,true);
                        request.onreadystatechange = warningfile;
                        request.send(null);
                    }	
                });
            }
        });
    </script>
</div>

