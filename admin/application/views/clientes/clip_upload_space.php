<div id="clips-upload_<?php echo $key ?>" class="clips-upload active">
    <?php if (isset($text)): ?>
        <?php echo $text; ?>
    <?php else: ?>
        <p>Arrastre el VIDEO del nuevo clip y la respectiva FOTO; en cualquier orden, hacia el espacio debajo, para subirlos.</p>
    <?php endif; ?>
    <?php
        $attributes = array('class' => 'dropzone', 'id' => 'dropzone-' . $key);
        echo form_open_multipart(site_url('uploadclip'),$attributes); 
    ?>
        <input id="tipo-<?php echo $key ?>" type="hidden" value="<?=$key;?>" name="type-<?php echo $key ?>"/>
    <?php  if (isset($order)):  ?>
        <input type="hidden" value="<?=$order;?>" name="listorder"/>
    <?php endif; ?>
        <input id="filename" type="hidden" name="filename"/>
    </form>
    <script>
        var myDropzone = new Dropzone("#dropzone-<?php echo $key ?>", 
        { acceptedMimeTypes: "image/*,video/*",
          maxFilesize: 64,
          addRemoveLinks: true,
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
                        if (request.readyState == 4 && request.status == 200)
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
                                $(".clips.active").remove();
                                $(uploadAnswer).insertAfter("div.clips-upload.active");
                            } 
                            if (uploadAnswer.indexOf("El nuevo clip") !== -1)
                            {
                                myDropzone.removeAllFiles();
                                var newUploadSpace = "<div id='upload_space_<?php echo $key ?>'></div>";
                                $("#clips-upload_<?php echo $key ?>").replaceWith(newUploadSpace); 
                                $(".clip-category-add.active > input").trigger("click");
                            }
                        }
                    };

                    request.open("GET","/admin/index.php/uploadclip?tipo="+document.getElementById("tipo-<?php echo $key ?>").value+"&ftype="+ftype,true);
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
                            alert("NO es válido usar ' "+forbidden[i]+" ' en el nombre de archivo. Por favor quítelo!");
                            this.removeFile(file);
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
                                alert(file.name+" ya existe, y será reemplazado. Sino desea esto, cierre la página antes de terminar la carga.");
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

