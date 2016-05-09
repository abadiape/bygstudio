<ul id="clipstitle">
    <li><h1><?=$title;?></h1></li>
    <li><a href="<?=site_url('uploadclip/'.$tipo)?>">⬆ Nuevo clip/trailer</a></li>
</ul><br /><br />
<input id="tipo" type="hidden" value="<?=$tipo;?>"/>
<div id="clipspace">
    <p>La prioridad es de izquierda(+) a derecha(-). Puede reordenarlos, arrastrándolos a su nueva posición y así aparecerán en la página de BYG.</p> 
    <p>Para borrar clip del servidor, arrástrelo a la papelera. Esto; a diferencia del reordenamiento, NO se puede deshacer (una vez confirme).</p>
    <table id="clips">
    <?php
    $count = 0;
    $trans = array(' ' => '_');
    $rev = array('_' => ' ');
    switch ($tipo)
    {
            case 'music':
                    $tipo = 'video/videos/';
                    break;
            case 'cine':
                    $tipo = 'video/cinema/';
                    break;
            case 'series':
                    $tipo = 'video/series/';
                    break;
            default:
                    $tipo = 'video/spots/'.$tipo.'/';
    } ?>
        <tr>
    <?php   foreach ($clips as $value)
            {
                ++$count;
                if (($count % 2) === 0)
                        echo '<th id="text'.$value['list_order'].'" class="even';
                else echo '<th id="text'.$value['list_order'].'" class="odd';
                if ($value['title'] !== "")
                    $clipTitle = str_replace('_', ' ', $value['title']);
                else $clipTitle = $value['img_name'];
                echo '">'.ucwords($clipTitle).'</th><th>&nbsp;</th>';
            } ?>
        </tr>
        <tr>
    <?php   foreach ($clips as $value)
            {
                if (strpos('.', $value['img_name']))
                    $clipImage = strtr($value['img_name'],$trans);
                else
                    $clipImage = strtr($value['img_name'],$trans).'.jpg';
                echo '<td><img id="strip'.$value['list_order'].'" src="http://bygstudio.com/'.$tipo.$clipImage.'" 
                draggable="true" width="144" height="89"></img></td><td>&nbsp;</td>';
            } ?>
        </tr>
    </table>
    <input id="trash" type="image" src="<?=base_url('images/trash.svg');?>"/>
</div>

<script>
var tipo = document.getElementById("tipo").value;
var dragSrcEl = null;

function handleDragStart(e) {
// Target (this) element is the source node.
  this.style.opacity = '0.4';  // this / e.target is the source node.
  
  dragSrcEl = this;

  e.dataTransfer.effectAllowed = 'move';
  e.dataTransfer.setData('text/uri-list', this.getAttribute('src'));
  
  srcImgId = this.getAttribute('id');
  srcImgNumber = srcImgId.substr(5);
  srcText = document.getElementById('text'+srcImgNumber);
}

function handleDragOver(e) {
  if (e.preventDefault) {
    e.preventDefault(); // Necessary. Allows us to drop.
  }

  e.dataTransfer.dropEffect = 'move';  

  return false;
}

function handleDragEnter(e) {
  // this / e.target is the current hover target.
  this.classList.add('over');
}

function handleDragLeave(e) {
  this.classList.remove('over');  // this / e.target is previous target element.
}

function handleDrop(e) {
  // this / e.target is current target element.

  if (e.stopPropagation) {
    e.stopPropagation(); // stops the browser from redirecting.
  }

  // Don't do anything if dropping the same column we're dragging.
  if (dragSrcEl != this) {
    // Set the source column's HTML to the HTML of the column we dropped on.
    var svgOrJpg = this.getAttribute("src").length;
    svgOrJpg -= 9;
    trashString = this.getAttribute("src").substr(svgOrJpg, 5);
    if (trashString !== "trash")
    {
    	dragSrcEl.setAttribute('src', this.getAttribute('src'));
    	this.setAttribute('src',e.dataTransfer.getData('text/uri-list'));
    
    	destImgId = this.getAttribute('id');
    	destImgNumber = destImgId.substr(5);
    	destText = document.getElementById('text'+destImgNumber);
    	textSwap = srcText.innerHTML; 
  	srcText.innerHTML = destText.innerHTML;
  	destText.innerHTML = textSwap;
  	fileToDel = "";
    }
    else
    { 
    	fileToDel = e.dataTransfer.getData('text/uri-list');
    	var delConfirm = window.confirm('¿Está seguro de borrar "'+fileToDel+'" ?');
    	if (delConfirm)
    	{
    		dragSrcEl.parentNode.removeChild(dragSrcEl);
    		srcText.innerHTML = "";//srcText.parentNode.removeChild(srcText);
    		destImgNumber = "";
    	}
    	else fileToDel = "";
    }
    if (window.XMLHttpRequest)
    {
  		request = new XMLHttpRequest();
  		request.open("GET","/admin/index.php/updateclips?tipo="+tipo+"&origen="+srcImgNumber+"&destino="+destImgNumber+"&file="+fileToDel,true);
  		request.send(null); 	
	}
  return false;
}
}

function handleDragEnd(e) {
  // this/e.target is the source node.

  [].forEach.call(images, function (col) {
    col.classList.remove('over');
  });
  trash.classList.remove("over");
  this.style.opacity = '1.0';
}

var images = document.querySelectorAll('#clips td img');
[].forEach.call(images, function(col) {
  col.addEventListener('dragstart', handleDragStart, false);
  col.addEventListener('dragenter', handleDragEnter, false);
  col.addEventListener('dragover', handleDragOver, false);
  col.addEventListener('dragleave', handleDragLeave, false);
  col.addEventListener('drop', handleDrop, false);
  col.addEventListener('dragend', handleDragEnd, false);
});

trash = document.querySelector("#trash");
function borrar(img) {
	img.addEventListener('dragenter', handleDragEnter, false);
	img.addEventListener('dragover', handleDragOver, false );
	img.addEventListener('dragleave', handleDragLeave, false);
	img.addEventListener('drop', handleDrop, false);
  	img.addEventListener('dragend', handleDragEnd, false);
}
borrar(trash);	
</script>