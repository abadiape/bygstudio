<h1><?php echo $title.$clientes['cliente'] ?></h1></br>
<?php echo validation_errors(); ?> 
<p>Llene las casillas correspondientes y haga clic en <em>"Agregar".</em></p>
<p>Verifique primero que correspondan a un contacto de <?=$clientes['cliente']?></p></br>
<?php echo form_open(site_url('newcontact/'.$clientes['id'])) ?>
<div id="datacontact">
    <ul><h5>Datos contacto</h5><br/>
        <input id="nameclient" type="hidden" name="nameclient" value="<?php echo $clientes['cliente'] ?>"/>
        <li>
            <label for="contactn">Nombre: </label>
            <input id="contactn" type="text" name="contactn" value="" />
        </li>
        <li>
            <label for="correon">E-mail: </label>
            <input id="correon" type="text" name="correon" value="" />
        </li>
        <li>
            <label for="teln">Tel: </label>
            <input id="teln" type="text" name="teln" value="" />
        </li>
        <li>
            <label for="usern">Usuario: </label>
            <input id="usern" type="text" name="usern" value="" />
        </li>
        <li>
            <label for="pwdn">Password: </label>
            <input id="pwdn" type="text" name="pwdn" value="" />
        </li>
        <li>
            <input type="submit" name="addc" value= "Agregar" onClick="creado()">
        </li>
    </ul>
</div>
</form>


<script type="text/javascript">
    var creado = function()
    {
        var lleno1 = document.getElementById("contactn").value;
        var lleno2 = document.getElementById("correon").value;
        if (lleno1.trim() === "" || lleno2.trim() === "")
        {
            window.alert("Error, campos vacios introduzca datos!");
        }
        else
        {
            window.alert("El contacto ha sido creado");
        };
        return;
    };
</script>