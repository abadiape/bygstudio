<div id="contenido">
    <h1><?php echo $title.$clientes['cliente'] ?></h1></br>
    <p>Asigne nombre al nuevo proyecto y haga clic en <em>"Crear "</em>.</p>
    <p class="note">Nota: Los comentarios pueden "modificarse/agregarse" despu&eacute;s.</p><br/>
    <?php 
        $attributes = array('name' => 'projectform', 'id' => 'projectform');
        echo form_open(site_url('agregar'), $attributes) ?>
    <input type="hidden" value=<?php echo $clientes['id'] ?> name="productora" >
    <table>
        <tr>
            <th>Nombre:&nbsp;&nbsp;<input id="project" type="text" name="project" maxlength="34" size="34" ></th>
        <td>
            <input id="sendproject" type="submit" value="Crear" name="sendproject""></td>
        </tr>
        <tr>
            <th colspan="2">&nbsp;</th></tr>
        <tr>
            <th colspan="2">Comentarios (opcional):</th>
        </tr>
        <tr>
            <td colspan="2">
                <textarea name="apuntes"><?php set_value('apuntes') ?></textarea>
            </td>
        </tr>
    </table>
    </form>
</div>

<script>
    CKEDITOR.replace('apuntes');
    CKEDITOR.config.toolbar = [
       ['Styles','Format','Font','FontSize'],
       '/',
       ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-'],
       '/',
       ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
       ['-','Link','TextColor','BGColor']
    ] ;
</script>