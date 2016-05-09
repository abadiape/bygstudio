<h1><?php echo $title.$project['project'] ?></h1>
<?php echo form_open(site_url('comments')); ?>
<textarea name='modify'>
<?php echo set_value('modify',$project['anotaciones']); ?>
</textarea>
<?php echo "<input name='save' value='Guardar' type='submit' />
<input name='projectC' value=".$project['project']." type='hidden' />"; ?>
</form>
            <script>
                CKEDITOR.replace('modify');
                CKEDITOR.config.toolbar = [
   ['Styles','Format','Font','FontSize'],
   '/',
   ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-'],
   '/',
   ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
   ['-','Link','TextColor','BGColor']
] ;
            </script>