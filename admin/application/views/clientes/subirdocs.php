<h1><?php echo $title; ?></h1>
<div id="boxdoc">
<p>Drag your (document) files into the space provided below this paragraph, or click onto it in order to upload your files.</p><br/>
<?php 
$attributes = array('class' => 'dropzone');
echo form_open_multipart(site_url('subirdocs'),$attributes); ?>
<input type="hidden" value=<?=$title ?> name="proyecto" />
</form>
</div>
 