<!DOCTYPE html>
<html>
    <head>
        <title>Byg Studio | Postproduction services México</title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta name="description=" content="BYG STUDIO offers a whole range of specialized postproduction services including editing, color grading, animation, compositing and VFX for commercials, TV series and feature films for the USA and Latin American markets. We are based in Mexico.">
        <meta name="keywords" content="Byg, Bygstudio, Byg Films, Producciones, Productoras, Post, Postproductora, Postproducciones, Postproduccion, Postproduction, Mexico, Productions, TV, Spots, Mexicano, America Latina, Colombia, Publicidad, post-production, editing, commercials, marketing, series, vfx, animation, color grading, compositing, La Otra Familia, Heroes del Norte, Cloroformo, Paramedicos, Dos lunas, Pulling strings, Amor a primera visa" />
        <link rel="stylesheet" href="http://bygstudio.com/css/reset1.0.css"/>
        <link rel="stylesheet" href="http://bygstudio.com/css/screen.css" />
        <link rel="stylesheet" href="http://bygstudio.com/css/sections.css" />
        <script src="http://bygstudio.com/js/jquery.color.js"></script>        
    </head>
    <body>
        <div id="testcommand">
        <?php 
                class Section {

                    private $mysqli;

                    public function __construct()
                    {
                            $this->mysqli = new mysqli("localhost","bygfilms_cliente","samanthaABADIA2011#","bygfilms_clientes");
                            if ($this->mysqli->connect_errno)
                                    die('No se pudo conectar a la base de datos: '.$this->mysqli->connect_errno.' '.$this->mysqli->connect_error);	
                            $this->mysqli->set_charset("utf8");
                    }

                    public function get_text()
                    {
                        $contentQuery = "SELECT content FROM pages_text WHERE name='contact'"; 
                        $content = $this->mysqli->query($contentQuery);
                        $text = $content->fetch_row();
                        return $text[0];
                    }
                }
        ?>
        </div>
        <!-- Content Start -->
        <div id="website">
            <!-- Organic Tabs Content Starts -->	
            <div id="tab-two">
                <!-- Logo -->
                <div id="logo"></div>
                <!-- Menu Starts -->
                <div id="items">	
                    <ul class="nav">
                        <li><a href="http://bygstudio.com/mainpage.php">HOME</a></li>
                        <li><a href="http://bygstudio.com/about.php">ABOUT</a></li>
                        <li><a href="http://bygstudio.com/services.php">SERVICES</a></li>                        
                    </ul>
                </div>
                <!-- Wrapping Starts -->
                <div class="list-wrap" id="seccion"> 
                    <!-- Services Starts -->	
                    <div id="contact">
                        <div id="container">
                            <!-- Text -->
                            <?php 
                                $contact = new Section();
                                echo $contact->get_text();
                            ?>
                        </div> 
                    </div>
                </div>
            </div>
        </div>        
    </body>
</html>