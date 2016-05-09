<h1><?php echo $title; ?></h1>
<?php if (stripos($title, 'correo') === false): ?>
    <div id="search">
    <form action=<?=site_url('buscar')?> method="post" >
    <table border="1" cellPadding="1" align="center">
    <tr><td>Search:<input type="text" name="texto" value="" maxlength="10" ></td>
    <td><input type="submit" name="envio" value="Send"></td></tr>
    </table>
    </form></br>
    </div>
<?php endif; ?>