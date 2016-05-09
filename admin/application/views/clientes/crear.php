<div id="container">
    <h1><?php echo $title ?></h1></br>
    <?php echo validation_errors(); ?> 
    <p>Llene las casillas con los datos respectivos y haga clic sobre <em>"Crear cliente".</em></p></br>
    <?php echo form_open(site_url('crear')) ?>
        <div id="dataclient">
            <h5>Datos cliente</h5>
            <ul>
                <li>Nombre:&nbsp;<input id="cliente" type="text" name="cliente" value="" /></li>
                <li>RFC:&nbsp;<input id="rfc" type="text" name="rfc" value="" /></li>
                <li>Address:&nbsp;<input id="address" type="text" name="address" value="" /></li>
                <li>Phone:&nbsp;<input id="tel" type="text" name="tel" value="" /></li>
                <li><input type="submit" name="create" value= "Crear cliente" onClick="creado()"></li>
            </ul>
        </div>
        <div id="datacontact">
            <h5>Datos contacto</h5>
            <ul>
                <li>Contacto:&nbsp;<input id="contact1" type="text" name="contact1" value="" /></li>
                <li>E-mail:&nbsp;<input id="correo1" type="text" name="correo1" value="" /></li>
                <li>Phone:&nbsp;<input id="tel1" type="text" name="tel1" value="" /></li>
                <li>Usuario:&nbsp;<input id="user1" type="text" name="user1" value="" /></li>
                <li>Password:&nbsp;<input id="pwd1" type="text" name="pwd1" value="" /></li>
            </ul>
        </div>
    </form>
</div>

<script>
var creado = function()
{
    var lleno1 = document.getElementById("cliente");
    var lleno2 = document.getElementById("rfc");
    var lleno3 = document.getElementById("address");
    var lleno4 = document.getElementById("tel");
    var lleno5 = document.getElementById("contact1");
    var lleno6 = document.getElementById("correo1");
    var lleno7 = document.getElementById("tel1");
    if (lleno1.value === "" || lleno2.value === "" || lleno3.value === "" || lleno4.value === "" || lleno5.value === "" || lleno6.value === "" 
    || lleno7.value === "")
    {
        window.alert("Error, campos vacios introduzca datos!");
    }
    else
    {
        window.alert("El cliente ha sido creado");
    };
    return;

};
</script>