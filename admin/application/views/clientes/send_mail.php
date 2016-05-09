<p>Verifique/agregue/modifique informaci&oacute;n pertinente, haga clic en "Send".</p></br>
<?php 
$n = $count;
$correos = "";
$trans = array('aQ'=>'á','eQ'=>'é','iQ'=>'í','oQ'=>'ó','uQ'=>'ú','_'=>' ');
$proyecto = strtr($project,$trans);
for ($i=1;$i<=$n;$i++)
{
	if ($i === 1)
	{
	$correos .= ${'correo'.$i};
	}
	else $correos .= ", ".${'correo'.$i};
} 
$file = str_replace(' ','%20',$file); 
$cuerpo = 'En: http://bygstudio.com/admin/index.php/versiones/'.$project.'/'.$file.' 
se encuentra disponible la ultima version de su proyecto '.$proyecto.'. 
Mediante el campo de comentarios puede enviarnos sus opiniones o sugerencias.
 
Cordialmente, 

BYG STUDIO';
echo validation_errors(); 
echo form_open(site_url('sendmail')); 
?>
<table id="mail">
<tr><th>Destinatario(s):</th><td>
<?php 
echo "<input type='text' value='".set_value('correos',$correos)."' name='correos' size='55' />";
?>
</td></tr>
<tr>
<th>Asunto:</th>
<td><input type="text" value="<?php echo set_value('subject','Nueva version de su proyecto '.$proyecto); ?>" 
name="subject" maxLength="55" size="55"/></td>
</tr>
<tr><th>Texto:</th>
<td>
<textarea name='texto' cols='55' rows='8'>
<?php echo set_value('texto',$cuerpo); ?>
</textarea>
</td></tr>
<tr><th></th><td><input type="submit" value="Enviar" id="sendmail" onclick="disable()" /></td></tr>
</table>	 
</form>
			<script>
			var disable = function()
			{
			document.getElementById("sendmail").disabled=true;
			document.getElementById("sendmail").value="Enviando, por favor espere...";
			}
			
			CKEDITOR.replace('texto');
			CKEDITOR.config.toolbar = [
   		['Styles','Format','Font','FontSize'],
   		'/',
   		['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-',
   		'Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-'],
   		'/',
   		['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
   		['-','Link','TextColor','BGColor']						] ;
			</script> 
 