<?php echo form_open(site_url('editcon'));
echo '<table class="contactline"><tr><th id="contactos'.$idi.'"><input type="text" name="contedit" value="'.set_value('contedit',$contact).'"/></th>
<td id="minusedit'.$idi.'"><input type="submit" value="Send"/></td></tr>'; 
echo "<tr><th colspan='2' id='correos".$idi."'><input type='text' name='mailedit' value='".set_value('mailedit',$correo)."'/></th></tr>";
echo "<tr><th colspan='2' id='phones".$idi."'><input type='text' name='phonedit' value='".set_value('phonedit',$phone)."'/></th></tr>
<tr><td colspan='2'>&nbsp;<input type='hidden' name='idi' value='".set_value('idi',$idi)."'/></td></tr></table>"; ?> 
</form>