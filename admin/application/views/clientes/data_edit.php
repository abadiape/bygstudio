<?php echo form_open(site_url('editdata')); ?>
    <ul class="data">
        <li>
                <?php 
                    $pos = strpos($cliente,' ');
                    $cust = strtolower(substr($cliente,0,$pos));
                ?>            
                <?php if (file_exists('images/logo_'.$cust.'.svg')): ?>
                <embed src="<?=base_url('images/logo_'.$cust.'.svg')?>"></embed>
                <?php else: ?> 
                <h5><?=$cliente?><h5><br/>
                <?php endif; ?>
        </li>
        <li>
            <label>RFC:</label>
            <input id="rfc<?=$id?>" type="text" size="32" name="rfcedit" value="<?=set_value('rfcedit',$rfc)?>"/>
        </li> 
        <li>
            <label>Address:</label>
            <input id="address<?=$id?>" type="text" size="32" name="addedit" value="<?=set_value('addedit',$address)?>"/>
        </li>
        <li>
            <label>Tel:</label>
            <input id="tel<?=$id?>" type="text" size="32" name="teledit" value="<?=set_value('teledit',$tel)?>"/>
        </li>
        <li>
        <?php if ( !isset($message)): ?>
            <input id="edit<?=$id?>" type="submit" value="Send"/>
        <?php else: ?>
            <?=$message?>
        <?php endif;?>
        </li>
        <li>
            <input type="hidden" name="id" value="<?=set_value('id',$id)?>"/>
        </li>
    </ul> 
</form>