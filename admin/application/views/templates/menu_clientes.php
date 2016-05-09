<!--<div id="menu">
<ul>
<li><a href=<?=site_url('logout') ?> >Logout</a></li>
<li><a href="#">Buscar
<ul id="search">
<form action=<?=site_url('buscar')?> method="post" >
<table>
<tr><td><input type="text" name="texto" value="" maxlength="10" size="16"></td>
<td><input type="submit" name="envio" value="Send"></td></tr>
</table>
</form>
</ul>
</a>
</li>
<li><a href=<?=site_url('show/'.$this->session->userdata('user')) ?> >Inicio</a></li>
</ul>
</div>-->

<div id="navmenu">
<ul>
   <li><a href="<?=site_url('show/'.$this->session->userdata('user')) ?>"><span data-toggle="tooltip" title="Vuelve al listado de Proyectos">Inicio</span></a></li>
   <!--<li><a class="has-sub" href="javascript:void(0)"><span>Buscar</span></a>
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
   </li>-->
   <li class="last"><a href="<?=site_url('logout')?>"><span>Logout</span></a></li>
</ul>
</div>