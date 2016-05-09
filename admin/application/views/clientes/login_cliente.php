<h1><?php echo $title ?></h1>
<div id = "loginbox">
<?php echo form_open(site_url('versiones/login')) ?>
<table id="sesion">
<tr>
<th id="user">
<a href="javascript:void(0)">
<span class="itemuser">Usuario: </span>
<span class ="itemhelp">
Usuario enviado en el correo de su nueva versión 
</span>
</a>
</th>
<td><input id="usuario" type="text" name="usuario" value="" maxlength="6" size="8" ></td>
</tr>
<tr>
<th id="pwd">
<a href="javascript:void(0)">
<span class="itempass">Password: </span>
<span class ="itemhelp">
Clave correspondiente al usuario enviado
</span>
</a>
</th>
<td><input id="password" value="" type="password" name="password" maxlength="8" size="10" ></td>
</tr>
<tr>
<td></td>
<td><input id="login" value="Login" type="submit" name="login"/></td>
</tr>
<tr>
<td><input id="videoid" value="<?php echo $version['video']['id'] ?>" type="hidden" name="videoid"/></td>
</tr>
</table>
</form></br></br>
</div>


