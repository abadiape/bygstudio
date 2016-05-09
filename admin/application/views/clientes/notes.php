<h1><?php echo $title.$project['project'] ?></h1><div>
<?php 
echo form_open(site_url('notes')) ?>
<table>
<tr><th></th>
<td><textarea name="notesc">
<?php echo set_value('notesc',$project['notesc']); ?>
</textarea>
            <script>
                CKEDITOR.replace('notesc');
                CKEDITOR.config.toolbar = [
   ['Styles','Format','Font','FontSize'],
   '/',
   ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-'],
   '/',
   ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
   ['-','Link','TextColor','BGColor']
] ;
            </script>
</td></tr>
<tr><td></td><td><input id="sendnotes" type="submit" value="Send" name="sendnotes" ></td></tr>
</table>
<?php echo "<input name='proyectoC' value=".$project['project']." type='hidden' />"; ?>
</form>
</div>