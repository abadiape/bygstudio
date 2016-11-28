<!DOCTYPE html>
<html>
    <head>
    <title>Byg Studio | Postproduction services México</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="description=" content="BYG STUDIO offers a whole range of specialized postproduction services including editing, color grading, animation, compositing and VFX for commercials, TV series and feature films for the USA and Latin American markets. We are based in Mexico.">
    <meta name="keywords" content="Byg, Bygstudio, Byg Films, Producciones, Productoras, Post, Postproductora, Postproducciones, Postproduccion, Postproduction, Mexico, Productions, TV, Spots, Mexicano, America Latina, Colombia, Publicidad, post-production, editing, commercials, marketing, series, vfx, animation, color grading, compositing, La Otra Familia, Heroes del Norte, Cloroformo, Paramedicos, Dos lunas, Pulling strings, Amor a primera visa" />
    <link rel="stylesheet" href="http://bygstudio.com/css/reset1.0.css"/>
    <link rel="stylesheet" href="http://bygstudio.com/css/screen.css" />
    <!--<link rel="stylesheet" href="http://bygstudio.com/css/sections.css" />-->  
    <link rel="stylesheet" href="http://bygstudio.com/admin/owl-carousel/owl-carousel/owl.carousel.css">
    <link rel="stylesheet" href="http://bygstudio.com/admin/owl-carousel/owl-carousel/owl.theme.css">
    <link rel="stylesheet" href="http://bygstudio.com/admin/mediaelementjs/build/mediaelementplayer2.css")/>
    <link rel="stylesheet" href="http://bygstudio.com/admin/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript" src="http://bygstudio.com/admin/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
    <script type="text/javascript" src="http://bygstudio.com/admin/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
    <script src="http://bygstudio.com/admin/owl-carousel/owl-carousel/owl.carousel.js"></script>
    <script src="http://bygstudio.com/admin/mediaelementjs/build/mediaelement-and-player.min.js"></script>     
    </head>
    <body>    
        <?php  
            class Clips {

                private $mysqli;

                public function __construct()
                {
                        $this->mysqli = new mysqli("localhost","bygfilms_cliente","samanthaABADIA2011#","bygfilms_clientes");
                        if ($this->mysqli->connect_errno)
                                die('No se pudo conectar a la base de datos: '.$this->mysqli->connect_errno.' '.$this->mysqli->connect_error);	
                        $this->mysqli->set_charset("utf8");
                }

                public function get_categories()
                {
                   $query = "SELECT * FROM clip_categories WHERE visible='1' ORDER BY category_order";
                    if ( ! $this->mysqli->query($query))
                        die('No se pudo extraer datos de la tabla!: '.$this->mysqli->errno.' '.$this->mysqli->error);
                    return $this->mysqli->query($query); 
                }

                public function get_clips($dire = false)
                {
                    if ($dire)
                    {
                        $modernCategory = strpos($dire, 'byg_clips');
                        if ($modernCategory !== false)
                        {
                           $this->tipo = substr($dire, 24); 
                        }
                        else
                        {
                            $this->tipo = substr($dire, 0, -1);
                            if (strlen($this->tipo) > 12)
                                $this->tipo = substr($this->tipo, 12);
                            else 
                            {
                                $this->tipo = substr($this->tipo, 6, 4);                    
                            }
                        }
                        $query = "SELECT fname, img_name FROM byg_clips WHERE type='$this->tipo' AND visibility='1' ORDER BY list_order";
                    }
                    else
                    {
                        $query = "SELECT fname, img_name, path FROM byg_clips JOIN clip_categories WHERE (in_banner='1' AND clip_categories.code = byg_clips.type)"; 
                    }
                    if ( ! $this->mysqli->query($query))
                        die('No se pudo extraer datos de la tabla!: '.$this->mysqli->errno.' '.$this->mysqli->error);
                    $result = $this->mysqli->query($query);
                    $imagelist = $videolist = $dirList = $clipsData = array();                                     
                    $trans = array(' ' => '_');
                    if ($result)
                    {
                        $i=0;
                        while($row = $result->fetch_array(MYSQLI_ASSOC))
                        {
                            $clipImage = strtr($row['img_name'], $trans);
                            if (! strpos($clipImage, '.'))
                            {
                                $imagelist[] = $clipImage . '.jpg';
                            } 
                            else
                            {
                                $imagelist[] = $clipImage;
                            }
                            if ($row['fname'] === '')
                            {                                    
                                $videolist[] = strtr($row['img_name'], $trans) . '.mp4';
                            }
                            else 
                            {                                   
                                $videolist[] = strtr($row['fname'], $trans);
                            }
                            if (! $dire)
                            {                                
                                if (strpos($row['path'], 'byg_clips') !== FALSE)
                                {
                                   $dirList[] = 'admin/uploads/' . $row['path']; 
                                }
                                else 
                                {
                                   $dirList[] = 'video/' . $row['path'];  
                                }
                            }
                        }
                        
                        foreach ($imagelist as $value)
                        {
                            if (isset($dirList[$i]))
                            {
                               $dire = $dirList[$i];
                               $idSuffix = '_banner-' . $i;
                            }
                            else
                            {
                               $idSuffix = '_' . $this->tipo . '-'. $i; 
                            }
                            $image = $dire . $value;
                            $video = $dire . $videolist[$i];                             
                            if (strpos($dire, 'byg_clips') !== false)
                            { 
                               $image = $dire . '/images/' . $value;
                               $video = $dire . '/videos/' . $videolist[$i];
                            }
                            $clipsData[$i]['id_suffix'] = $idSuffix;
                            $clipsData[$i]['image'] = $image;
                            $clipsData[$i]['video'] = $video;                            
                            if (! isset($dirList[$i]))
                            {
                                echo "<div class=\"item\" id=\"video$idSuffix\" data-video=\"$video\"><img src=\"http://bygstudio.com/$image\"></div>";
                            }
                            ++$i;
                        } 
                        return $clipsData;
                    }
                    else
                    {
                        return FALSE;
                    }
                }

                public function get_text()
                {
                    $contentQuery = "SELECT content FROM pages_text WHERE name='mainpage'"; 
                    $content = $this->mysqli->query($contentQuery);
                    $text = $content->fetch_row();
                    return $text[0];
                }
            }
        ?>
        <!-- Content Start -->
        <div id="website">
            <!-- Organic Tabs Content Starts -->	
            <div id="tab-two">
                <!-- Logo -->
                <div id="logo"></div>
                <!-- Menu Starts -->
                <div id="items">	
                    <ul class="nav">
                        <li><a href="http://bygstudio.com/about.php" id="about" onclick="go('about')">ABOUT</a></li>
                        <li><a href="http://bygstudio.com/services.php" id="serv" onclick="go('serv')">SERVICES</a></li>
                        <li><a href="http://bygstudio.com/contact.php" id="contact" onclick="go('contact')">CONTACT</a></li>
                    </ul>
                </div>
                <!-- Wrapping Starts -->
                <div class="list-wrap" id="seccion"> 
                    <!-- About Starts -->	
                    <div id="mainpage">
                        <div id="container">
                        <!-- Text -->
                        <?php 
                            $clipsCollection = new Clips(); 
                            $categoriesData =  $clipsCollection->get_categories();
                            $bannerCollection = new Clips(); 
                            $bannerData = $bannerCollection->get_clips();                            
                        ?>                           
                            <ul>
                                <li>                                    
                                    <div class="owl-carousel owl-theme banner" id="banner-section">                                        
                                        <?php foreach ($bannerData as $data): ?> 
                                            <div class="item" id="video<?=$data['id_suffix']?>" data-video="<?=$data['video']?>">                                                
                                                <img src="http://bygstudio.com/<?=$data['image']?>" height="320">               
                                            </div>
                                        <?php endforeach; ?>                   
                                    </div>
                                </li> 
                            <?php foreach ($categoriesData as $categoryData):                                 
                                if (strpos($categoryData['path'], 'byg_clips') !== FALSE)
                                {
                                   $directory = 'admin/uploads/' . $categoryData['path']; 
                                }
                                else 
                                {
                                   $directory = 'video/' . $categoryData['path'];  
                                } 
                                $categoryName = '';
                                if (strpos($categoryData['path'], 'spots') !== FALSE)
                                {
                                   $categoryName .= 'Spots - '; 
                                }
                                $categoryName .= ucfirst($categoryData['name']);
                            ?>
                                <li>
                                    <h2 style="color:#C6D73D; font-size: 1.5em"><?=$categoryName; ?></h2>
                                    <div class="owl-carousel owl-theme category" id="<?=$categoryData['code']; ?>-section">                                       
                                        <?php $clipsCollection->get_clips($directory); ?>                                      
                                    </div>
                                </li>
                            <?php endforeach; ?>
                            </ul>                                
                        </div> 
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {                              
                
                $(".item").on("click", function() {                
                    var path = $(this).attr("data-video"); 
                    var id = $(this).attr("id");
                    var suffix = id.substring(5); 
                    $('.vercom').remove();
                    var tableHtml = '<div id="videobox'+suffix+'" class="vercom"><object width="480" height="286" classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" class="spinner"><param name="src" value="http://bygstudio.com/'+path+'"/><param name="controller" value="true"/><param name="autoplay" value="false" /><embed src="http://bygstudio.com/'+path+'" width="480" height="286" scale="aspect" autoplay="false" controller="true" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object></div>';                
                    $("#container").after(tableHtml);
                    $("video").mediaelementplayer();
                    $(".fancybox").fancybox();
                    $.fancybox({
                        type: 'inline',
                        href: '#videobox'+suffix,
                        wrapCSS: 'vercom',
                        scrolling: 'no',                    
                        beforeShow : function() {
                             $('.fancybox-skin').css({
                                 'background' :'#484848',
                                 'border-radius':'0.8125em', 
                                 'border':'solid 0.125em #c6d73d'
                             });
                        },
                        afterClose : function(){ $("#videobox"+suffix).remove(); }
                    });
                    $.fancybox.update();
                        
                });
              
                $("#banner-section").owlCarousel({
                    autoPlay: true,
                    navigation : true,
                    navigationText : [
                      "<i class='icon-chevron-left icon-white'></i>",
                      "<i class='icon-chevron-right icon-white'></i>"
                      ],
                    stopOnHover: true,
                    items: 2,
                    autoHeight : true
                });
                  
                var categoriesDivs = document.querySelectorAll("div.owl-carousel.category");
                for (x in categoriesDivs)
                {
                    var divId = categoriesDivs[x].getAttribute("id");
                    $("#"+divId).owlCarousel({
                        autoPlay: true,
                        navigation : true,
                        navigationText : [
                          "<i class='icon-chevron-left icon-white'></i>",
                          "<i class='icon-chevron-right icon-white'></i>"
                          ],
                        stopOnHover: true
                    });
                }                      
              
            });
            
            var go =  function(lid)
            {
                var preSeccion = '<p id="vname"></p><div id="container"><div class="wrapper"><ul id="slider">';
                var postSeccion = '</ul></div></div>';
                var seccion = document.getElementById("seccion");                               
                function active () {
                var chosen = {about:"about",serv:"serv",contact:"contact",auto:"ads",food:"ads",health:"ads",lifes:"ads",series:"tv",videos:"tv",cine:"cine"};
                for (var field in chosen)
                {
                        if (field !== lid)
                        document.getElementById(chosen[field]).style.color="";
                }
                document.getElementById(chosen[lid]).style.color="#bdbdbd";
                }
                switch (lid)
                {
                case "about":                	
                seccion.innerHTML = '<div id="about"><div id="container"><h1>'+
                'About '+'<span>'+'Byg Studio'+'</span></h1><p style="text-align:justify;text-justify:inter-word;">'+
                'We are a company based in Mexico city with an extensive experience in film and TV post-production.'+
                '<span>'+' BYG STUDIO'+'</span>'+' offers a whole range of specialized services including '+'<span>'+
                'editing, color grading, animation, compositing and VFX '+'</span>'+
                ' for commercials, TV series and feature films for the USA and Latin American markets. We help directors and producers accomplish their ultimate vision using the '+
                '<span>'+'foremost standards '+'</span>'+'and' +'<span>'+' digital technologies '+'</span>'+'available.'+'<br/><br/>'+
                'Our company has a superb, flexible and dedicated team. We are proud of being a spirited teamwork, our passionate lead editor and Post-production Manager is Mr. '+
                '<a href="http://www.imdb.com/name/nm2179589/"><span>'+' Camilo R. Abadía (http://www.imdb.com/name/nm2179589/)'+'</span></a>'+
                ', who nowadays is arguably one of the best editors based in Mexico and who is available for work worldwide.'+'</p></div></div>';
                break;
                case "serv":                
                seccion.innerHTML = '<div id="services"><div id="container"><h1>'+'Services'+
                '</h1><p style="text-align:justify;text-justify:inter-word;">'+
                'Editing should be a seamless process, so well done than audiences should not be conscious of it: '+
                '<span>'+'"The art of making public unaware the continuous feature they are watching was not just shot that way".'+
                '</span></br></br>'+' We offer the following post-production services:'+'</p><br/><br/><marquee class="left" behavior="slide" direction="up"><ul><li>*'+' Editing'+'</li><li>*'+
                ' Color grading'+'</li><li>*'+' Animation (2D and 3D)'+'</li><li>*'+' VFX'+'</li><li>*'+' Compositing'+'</li><li>*'+
                ' Finishings & Online'+'</li></ul></marquee></div></div>';
                break;	
                case "contact":                
                seccion.innerHTML = '<div id="contact"><div id="container"><h1>'+
                'Contacts'+'</h1><marquee class="left" behavior="slide" direction="up" width="233" height="360"><p>'+
                'CAMILO R. ABADIA'+'<br />'+
                'Post-Production Director'+'<br /><a href="mailto:camilo.a@bygstudio.com">'+
                'camilo.a@bygstudio.com'+'</a><br /><br />'+'LUIS EDUARDO ABADIA'+'<br />'+
                'Manager and Marketing Director'+'<br /><a href="mailto:eduardo.a@bygstudio.com">'+
                'eduardo.a@bygstudio.com'+'</a><br /><br />'+'LUISA FERNANDA HOYOS'+'<br />'+
                'Public Relations'+'<br /><a href="mailto:luisa.h@bygstudio.com">'+'luisa.h@bygstudio.com'+
                '</a><br /></p></marquee><div class="right"><p>'+'Prolongación Reforma 2000 P. 6, Col. Lomas de Santa Fé C.P. 01210, México D.F.'+
                '<br />'+'Landline:+52 (55) 5255-2532'+'<br />'+'Cellular:+521 (55) 3493-9457'+
                '<br /><br /><a href="http://twitter.com/#!/BYGSTUDIO" target="_blank"><img src="img/twitter.png">'+
                'Follow us in Twitter'+'</a><br /><a href="http://www.facebook.com/BygStudio" target="_blank"><img src="img/facebook.png">'+
                'Follow us in Facebook'+'</a></p></div></div></div>';
                break;
                }
            };
            //Llama la funcion que reproduce el respectivo video escogido.
            var playVideo = function (t,i) {
                var elemento = document.getElementById("video_banner-"+i);
                elemento.innerHTML = '<object width="480" height="286" classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" class="spinner"><param name="src" value="http://bygstudio.com/'+t+'"/><param name="controller" value="true"/><param name="autoplay" value="false" /><embed src="http://bygstudio.com/'+t+'" width="480" height="286" scale="aspect" autoplay="false" controller="true" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object>';
                
            };
            
        </script>
    </body>
</html>