<?php
    require_once "php/CAD.php";
    session_start();
    $cad = new CAD();

    $username = $_SESSION['username'] ?? NULL;
    $song = $_GET['song'];

    $songData = $cad->getSong($song);
    $scaleName = $cad->getScaleName($songData[6]);
    $songPicture = $cad->getUserImage($songData[4]);
    $post = $cad->getSongPost($song);

    if(isset($_POST['like']))
    {
        $cad->like($post);
    }

    if(isset($_POST['dislike']))
    {
        $cad->dislike($post);
    }

    if(isset($_POST['quit']))
    {
        $cad->quitFromList($song);
    }
    if(isset($_POST['add']))
    {
        $cad->addToList($song);
    }

    if($username != "")
    {
        $profilePictureLocation = $cad -> getProfilePicture();
    }   

    $chords = $cad->getChordsFromScale($songData[6]);

    

?>

<html lang="es">
    <head>
	    <meta charset="utf-8">
        <title><?php echo $songData[1];?></title>
        <link rel="stylesheet" type="text/css" href="css/topbarstyle.css"/>
        <link rel="stylesheet" type="text/css" href="css/styles.css"/>
        <link rel="stylesheet" type="text/css" href="css/song.css"/>
        <script type="text/javascript" src="scripts/Tab.js"></script>
       
    </head>

    <body >
       <div class="Page" lang="es">
            <div class="TopBar">
                <div class="Logo">SB</div>
                <input type="checkbox" class="ToogleLabel" >
                <div class="Toogle">
                    <div class="MenuToogle"></div>
                    <div class="MenuToogle"></div>
                    <div class="MenuToogle"></div>
                </div>
                <div class="PlegableMenu"> 
                    <form class="SearchBar" method="get" action="Search.php">
                        <input type="search" name="search" class="Bar" required placeholder="Buscar canciones, ejercicios, etc."> 
                        <input type="submit" class="Button" value="Buscar">
                    </form>
                    <div class="Menu">
                        <input type="button" class="Button" value="Inicio" <?php
                            if($username == "")
                            {
                                echo "onclick=\"window.location.href ='Login.php'\"";
                            }
                            else
                            {
                                echo "onclick=\"window.location.href ='TimeLine.php'\"";
                            }
                        ?>>
                        <input type="button" class="Button" value="Canciones" onclick="window.location.href ='Songs.php'">
                        <input type="button" class="Button" value="Comunidades" onclick="window.location.href ='Communities.php'">
                        <input type="button" class="Button" value="Ejercicios" onclick="window.location.href ='Exercises.php'">
                    </div>
                    <?php

                        if($username == "")
                        {
                            echo "<div class=\"LoginButtons\">
                                    <input type=\"button\" class=\"Button\" value=\"Iniciar sesión\" id=\"Login\" onclick=\"window.location.href ='Login.php'\">
                                    <input type=\"button\" class=\"Button\" value=\"Registrate\" id=\"Register\" onclick=\"window.location.href ='Sign-in.php'\">
                                </div>";
                        }
                        else
                        {
                            echo "<div class=\"ProfileButtons\">            
                                    <a href=\"MyProfile.php\" class=\"ProfilePicture\">   
                                        <img src=\"$profilePictureLocation \" id=\"ProPicture\">
                                        <div class=\"ProfileButton\">Mi Perfil</div>   
                                    </a>
            
                                    <a href=\"Login.php\" class=\"Exit\">   
                                        <img src=\"images/exit.png\" id=\"ExitPicture\">
                                        <div class=\"ExitButton\">Cerrar sesión</div> 
                                    </a>
                                </div>";
                        }
                    ?>
                </div>
               
            </div>
            <div class="Content" id="SongPage">
                <div class="SongSquare">
                    <div class="SongInfo">
                        <img src="<?php echo $songPicture ?>" class="SongPicture">
                        <div class="SongData">
                            <div class="Name"><?php echo $songData[1];?></div>
                            <div class="Author">Autor: <?php echo $songData[2];?></div>
                            <div class="Info">Escala: <?php echo $scaleName;?></div>
                            <div class="Info">Instrumento: <?php echo $songData[7];?></div>
                        </div>
                        <?php
                        if($username != "")
                        {
                            echo "<form method=\"POST\" action=\"Song.php?song=$song\" class=\"SongButtons\">";
                            if($cad->checkIfLike($post))
                            echo "<input type=\"submit\" class=\"Liked\" name=\"dislike\" value=\"\">";
                            else
                            echo "<input type=\"submit\" class=\"Like\" name=\"like\" value=\"\">";
                            $likes = $cad->likesCount($post);
                            echo "<div class=\"LikeCount\">".$likes."</div>";
                            if($songData[2]!=$username)
                            {
                            $isOnList = $cad->checkIfList($song);
                                if($isOnList)
                                {
                                    echo "<input type=\"submit\" class=\"QuitFromList\" name=\"quit\" id=\"Register\" value=\"Quitar de mi lista\">";
                                }
                                else
                                {
                                    echo "<input type=\"submit\" class=\"AddToList\" name=\"add\" id=\"Login\" value=\"Agregar a mi lista\">";
                                }
                            }
                            echo "</form>";
                        }
                        ?>
                    </div>
                    <div class="Subtitle">Acordes</div>
                    <div class="Chords">
                        <?php
                            for($i = 0; $i<7;$i++)
                            {
                                $chordData = $cad->getChordData($chords[$i]['chord']);
                                echo "<div class=\"Chord\">
                                        <img src=\"".$chordData[2]."\" class=\"ChordImage\">
                                        <div class=\"ChordName\">".$chordData[1]."</div>
                                    </div>";

                            }
                        ?>
                        
                    </div>
                    
                    <div class="Subtitle">Tablatura</div>
                    <div id="Tab">
                        <img src="images/tab.png" class="TabImage">
                        <img src="images/tab.png" class="TabImage">
                        <img src="images/tab.png" class="TabImage">
                    </div>
                    
                </div>
            </div>
       </div>
    </body>
</html>