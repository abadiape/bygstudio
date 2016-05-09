<?php 
    $atts = array(
                  'width'      => '1280',
                  'height'     => '720',
                  'scrollbars' => 'yes',
                  'status'     => 'yes',
                  'resizable'  => 'yes',
                  'screenx'    => '0',
                  'screeny'    => '0'
    );
?>
<div id="navmenu">
<ul>
   <li><a href="<?=site_url('clientes') ?>"><span>Inicio</span></a></li>
   <li class="has-sub"><a href="#"><span>⬆X(clip/trailer)</span></a>
      <ul>
         <li class="has-sub"><a href="#"><span>Spots</span></a>
            <ul>
               <li><?=anchor_popup('loadclips/auto', 'Automotive', $atts);?></li>
               <li><?=anchor_popup('loadclips/food', 'Food&Bev', $atts);?></li>
               <li><?=anchor_popup('loadclips/health', 'Health&Beauty', $atts);?></li>
               <li class="last"><?=anchor_popup('loadclips/lifes', 'Lifestyle', $atts);?></li>
            </ul>
         </li>
         <li class="has-sub"><a href="#"><span>TV</span></a>
            <ul>
               <li><?=anchor_popup('loadclips/series', 'Series', $atts);?></li>
               <li class="last"><?=anchor_popup('loadclips/music', 'MusicVideos', $atts);?></li>
            </ul>
         </li>
         <li><?=anchor_popup('loadclips/cine', 'Cine', $atts);?></a>
      </ul>
   </li>
   <li><a class="has-sub" href="javascript:void(0)"><span>Buscar</span></a>
       <ul class="search">
            <li>
                <form action=<?=site_url('buscar')?> method="post" >
                    <input type="text" name="texto" value="" maxlength="10" size="16">
                    <input type="submit" name="envio" value="Send">

                <?php if ($title === "Productoras "): ?>
                    <input type="hidden" name="pageppal" value="Productoras">
                <?php else: ?>
                    <input type="hidden" name="pageppal" value="otromenu">
                <?php endif; ?>
                </form>
            </li>
       </ul>
   </li>
   <li class="last"><a href="<?=site_url('logout')?>"><span>Logout</span></a></li>
</ul>
</div>