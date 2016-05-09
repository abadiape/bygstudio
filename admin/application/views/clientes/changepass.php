<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <title>Cambio Contrase&ntilde;a:</title>
</head>
<body>
    <div>
        <text style="color:#929292">Sino recuerda su password, ingrese su usuario y haga clic en <em style="color:#FFFFFF"> Enviar</em>,
        se le asignará uno nuevo que le llegar&aacute; a su correo de BYG (usuario@bygstudio.com).
        </text></br></br>
        <form>
            <input type="button" value="Enviar" onClick="sendmail()" />
        </form></br></br>
        <h1 style="color:#FFFFFF">Cambio de contrase&ntilde;a:</h1></br></br>
        <text style="color:#929292">Para cambio de password, es indispensable que llene los campos vac&iacute;os, incluyendo 
        los de la parte superior; si a&uacute;n no lo ha hecho. Luego, de clic en <em style="color:#FFFFFF"> Cambiar</em>.
        </text></br></br>
        <form>  
            <label style="color:#c6d73d">Nuevo password:</label>
            <input type="password" id="newpwd" name="newpwd" maxLength="16" size="16" /><text style="color:#929292"> M&iacute;nimo ocho caracteres</text></br></br>
            <label style="color:#c6d73d">Repita nuevo password:</label>
            <input type="password" id="newagain" name="newagain" maxLength="16" size="16" /></br></br>
            <input type="button" value="Cambiar" onClick="pwdged()" />
        </form>
        </div>
</body>
</html>