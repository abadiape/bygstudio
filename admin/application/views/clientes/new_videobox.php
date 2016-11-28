<table id="videobox<?=$suffix?>" class="vercom">
    <tr>
        <td>			
        <?php if (substr($video, -3) === 'mp4' || strpos($_SERVER["HTTP_USER_AGENT"],'Chrome')): ?>               		
            <video id="video<?= $j ?>" controls preload="none" width="480" height="286">
                <source src="<?= base_url($video)?>" type="video/mp4" /> 
                <object width="480" height="286" type="application/x-shockwave-flash" data="<?= base_url('mediaelementjs/build/flashmediaelement.swf') ?>">
                    <param name="movie" value="<?= base_url('mediaelementjs/build/flashmediaelement.swf') ?>" />
                    <param name="flashvars" value="controls=true&file=<?= base_url($video)?>" />
                </object>
            </video>
        <?php else: ?>
                <object width="480" height="286" classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" 
                codebase="http://www.apple.com/qtactivex/qtplugin.cab" class="spinner">
                    <param name="src" value="<?= base_url($video) ?>"/>
                    <param name="controller" value="true" />
                    <param name="autoplay" value="false" /> 
                    <embed src="<?= base_url($video) ?>" width="480" height="286"
                    scale="aspect" autoplay="false" controller="true"
                    type="video/quicktime"
                    pluginspage="http://www.apple.com/quicktime/download/">
                    </embed>
                </object>
        <?php endif; ?>
        </td>                                                            
    </tr>                                      
</table>

