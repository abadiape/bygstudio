<?php echo validation_errors(); ?>
<div id="reqanswer">
<h1><?php echo $title; 
$trans = array('á'=>'aQ','é'=>'eQ','í'=>'iQ','ó'=>'oQ','ú'=>'uQ',' '=>'_');?></h1></br>
<table>
<tr>
<th>Proyecto</th><th>Nombre de la versión</th><th>Tamaño <p>(bytes)</p></th><th>Fecha de carga</th>
</tr>

<?php foreach($clientes as $clientes_item): ?>
	<?php echo form_open(site_url('cambiar')); 
	$proyecto = strtr($clientes_item['proyecto'],$trans); 
	$fname = str_replace(" ","%20",$clientes_item['file_name']); ?>
	<input type="hidden" name="id" value="<?php echo $clientes_item['id'] ?>" />
    	<tr class="row">	
	<td><?php echo $clientes_item['proyecto'] ?></td> 
	<td><?php echo anchor_popup("versiones/".$proyecto."/".$fname,$clientes_item['file_name']) ?>
	<span class = "tooltip"><?php echo site_url("versiones/".$proyecto."/".$fname); ?></span>
	</td>
	<td class="size"><?php echo $clientes_item['file_size'] ?></td>
	<td><?php echo $clientes_item['date'] ?></td>
	</tr>
	</form>
	<?php endforeach ?>
</table>
</div>

