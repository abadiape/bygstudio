<table id="title"><tr><th><?php echo $title.$projects['cliente'] ?></td></tr></table>
<?php
    $i = 0;
?>
<nav class="proyectos">
    <div class="tse-scrollable wrapper">
        <div class="tse-content">
            <ul>    
            <?php foreach ($projects['data'] as $data): ?>
                <?php $proyecto = $data['project'] ?>
                <?php if ($proyecto): ?>
                    <?php 
                        $trans = array('á'=>'aQ','é'=>'eQ','í'=>'iQ','ó'=>'oQ','ú'=>'uQ',' '=>'_');
                        $proyecto = strtr($proyecto,$trans);
                        ++$i;
                    ?>            
                            <li <?php if ($i%2 !== 0) echo 'class="odd"' ?>>
                                <a href="<?=site_url('versions/'.$proyecto)?>"><?=$proyecto?>&nbsp;<span class="badge"><?=$projects['numversions'][$i-1]?></span></a>                     
                            </li>                
                <?php endif; ?>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>

<script>
    $(document).ready(function() {
    // Initialize scroller.
        $('.wrapper').TrackpadScrollEmulator();
    });
</script>


	