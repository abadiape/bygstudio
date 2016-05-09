<table id="clips-<?php echo $key ?>" class="clips active">
    <tr>
    <?php $count = 0; foreach ($clips as $clip): ?>        
        <?php 
            ++$count;
            if (($count % 2) === 0)
                    echo '<th class="clip-title ' . $key . '">';
            else echo '<th class="clip-title ' . $key . '">';     
        ?>
    <input id="<?php echo 'title_' . $key . $clip['list_order'] ?>" type="text" maxlength="24" value="<?php echo ucwords($clip['title']); ?>">
        </th>
    <?php endforeach; ?>
    </tr>
    <tr class="clip-image-container">
    <?php foreach ($clips as $clip): ?>
        <td class="clip-image <?php echo $key ?>">
        <?php
            $imgVisible = $clip['visibility'];
            $imgClass = '';
            if (! $imgVisible)
            {
                $imgClass = 'not-visible';
            }
            echo '<img id="img_' . $key . $clip['list_order'] . '" src="https://bygstudio.com/admin/uploads/byg_clips/' . $key . '/images/' . $clip['img_name'] .'" 
            draggable="true" width="144" height="89" class="' . $imgClass . '">';
        ?>
            </img>
            <button type="button" id="<?php echo 'delete_' . $key . $clip['list_order'] ?>"><span class="glyphicon glyphicon-remove"></span></button>
        </td>
    <?php endforeach; ?>
    </tr>
    <tr>
    <?php foreach ($clips as $clip): ?>
        <td class="clip-visible <?php echo $key ?>"> 
            <input type="checkbox" id="<?php echo 'visibility_' . $key . $clip['visibility'] ?>" <?php if ($clip['visibility']) {echo ' checked=checked';} elseif ($clip['visibility'] === '0') {echo ' disabled=disabled';} ?>><label>&nbsp;Visible en página</label>             
        </td>
    <?php endforeach; ?>
    </tr>
    <tr>
    <?php foreach ($clips as $clip): ?>
        <?php $imgVisible = $clip['visibility']; ?>
        <td class="clip-banner <?php echo $key ?>"> 
            <input type="checkbox" id="<?php echo 'banner_' . $key . $clip['list_order'] ?>" <?php if ($clip['in_banner'] && $imgVisible && $imgVisible === '1') {echo ' checked=checked';} elseif (! $imgVisible  || $imgVisible === '0') {echo ' disabled=disabled';} ?>><label>&nbsp;Banner principal</label>             
        </td>
    <?php endforeach; ?>
    </tr>
    <tr>
    <?php foreach ($clips as $clip): ?>
        <td class="clip-change_image <?php echo $key ?>"> 
            <input type="button" id="<?php echo 'upload-img_' . $key . $clip['list_order'] ?>" value="Cambiar imagen">             
        </td>
    <?php endforeach; ?>
    </tr>
</table>

