<h1><?php echo $title ?></h1>
<div id = "loginbox">
<?php echo form_open(site_url('login/index')) ?>
<table id="sesion">
<tr>
<th id="user">
<a href="javascript:void(0)">
<span class="itemuser">Usuario: </span>
<span class ="itemhelp">
Usuario asignado por BYG Studio a su personal interno. 
</span>
</a>
</th>
<td><input id="usuario" type="text" name="usuario" value="" maxlength="12" size="16" ></td>
</tr>
<tr>
<th id="pwd">
<a href="javascript:void(0)">
<span class="itempass">Password: </span>
<span class ="itemhelp">
Clave correspondiente a su usuario.
</span>
</a>
</th>
<td><input id="password" value="" type="password" name="password" maxlength="16" size="16" ></td>
</tr>
<tr>
<td></td>
<td><input id="login" value="Login" type="submit" name="login"></td>
</tr>
<tr>
<td id="keyword"></td>
</tr>
</table>
</form></br></br>
<div id="campo">
<a href="javascript:void(0)" onClick="cambiar()" style="color:#929292">Cambiar/recuperar contrase&ntilde;a</a>
</div>
</div>
<script>
var filluser = function () {document.getElementById("keyword").innerHTML = ""}; 
var changepass = function ()
{
	var newkey = "newkey";
	var confirm = "confirm";
	newkey = prompt("Introduzca su nueva contraseña(mín. 8 - máx. 16, caracteres)","");
	if (newkey)
	confirm = prompt("Confirme su nueva contraseña","");
	if (newkey!=null && newkey!="")
	{
		if (newkey === confirm && newkey.length >= 6)
		alert("Contraseña cambiada! Un e-mail le será enviado con ella a usuario@bygstudio.com.");
		else alert("Los valores no coinciden! Por favor intente nuevamente");
	}
	else alert("Por favor llene el campo con valores válidos!");
}
var cambiar = function() {
    var user = document.getElementById("usuario").value;
    var pwd = document.getElementById("password").value;
    var passpage = document.getElementById("campo");
    if (window.XMLHttpRequest)
    request = new XMLHttpRequest();
    function loadpage() {
        if (request.readyState == 4 && request.status == 200)
        passpage.innerHTML = request.responseText;
    }
    request.open("GET", "/admin/index.php/changepass?info="+user+"&info1="+pwd,true);
    request.onreadystatechange = loadpage;
    request.send(null);
    }
   
var pwdged = function() {
    var newp = document.getElementById("newpwd").value;
    var newa = document.getElementById("newagain").value;
    var pwd = document.getElementById("password").value;
    var user = document.getElementById("usuario").value;
    if (pwd !== "" && user !== "")
    {
    if (newp !== "" && newp === newa)
    {
    if (window.XMLHttpRequest)
    request = new XMLHttpRequest();
    function verify () {
        if (request.readyState == 4 && request.status == 200)
        {
        	var verified = request.responseText;
        	if (verified == 1)
        	{
         		window.alert("Su password ha sido cambiado!");
        		window.location.reload();
        	}
        	else 
        	{
        		window.alert("El usuario/password ingresado no es válido!");
        	}
        }
    }
    request.open("GET","/admin/index.php/cambio?info="+pwd+"&new="+newp+"&user="+user,true);
    request.onreadystatechange = verify;
    request.send(null);
    }
    else 
    {
    window.alert("Los datos para su nuevo password no coinciden! Por favor ingreselos nuevamente.");
    document.getElementById("newpwd").value = "";
    document.getElementById("newagain").value = "";
    }
    }
    else window.alert("No ha ingresado su usuario/contraseña actual!");
}

var sendmail = function () 
{
	var user = document.getElementById("usuario").value;
	if (user !== "")
	{
	if (window.XMLHttpRequest)
	request = new XMLHttpRequest();
	function mailconfirm()
	{
		if (request.readyState == 4 && request.status == 200)
		{
		if (request.responseText == false)
		window.alert("El usuario no existe! Por favor verifique el dato e intente nuevamente");
		else window.alert("Un e-mail con su password le ha sido enviado a: "+request.responseText);
		}
	}
	request.open("GET","pwdmail?info="+user,true);
	request.onreadystatechange = mailconfirm;
	request.send(null);
	}
	else window.alert("Por favor ingrese su usuario en el campo para ello!");
}
</script>