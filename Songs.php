<?php
    require_once "php/CAD.php";
    session_start();
    $cad = new CAD();

    $username = $_SESSION['username'] ?? NULL;

    if($username != "")
    {
        $profilePictureLocation = $cad -> getProfilePicture();
    }   

    

?>

<html lang="es">
    <head>
	    <meta charset="utf-8">
        <title>Songs</title>
        <link rel="stylesheet" type="text/css" href="css/topbarstyle.css"/>
        <link rel="stylesheet" type="text/css" href="css/searchresults.css"/>
        <link rel="stylesheet" type="text/css" href="css/styles.css"/>
        <script type="text/javascript" src="scripts/script.js"></script>
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
            <div class="Content" id="Songs">
                <div class="LeftMenu">
                    <?php
                        $letter = 'A';
                        for($i = 0; $i<26 ; $i++)
                        {
                            echo "<a href=\"#$letter\" class=\"Button\">$letter</a>";
                            $letter++;
                        }
                    ?>
                </div>
                <div class="Songs">
                    <div class="Title">Canciones</div>
                    <?php

                        $letter = 'A';
                        for($i = 0; $i<26 ; $i++)
                        {
                            $letterExists = $cad -> getSongsWithLetter($letter, "song");
                            $keys = array_keys($_POST);
                            if($keys)
                            {
                                preg_match_all('/[0-9]+/', $keys[0], $matches);
                                $index = $matches[0][0];
                                $keyWithoutIndex = preg_replace('/[0-9]+/', "", $keys);
                                $actionName = $keyWithoutIndex[0];
                                $queryLetter = substr($actionName, -1);
                                
                                if($queryLetter == $letter)
                                {  
                                    $actionName = rtrim($actionName, $letter);
                                    switch($actionName)
                                    {
                                        case "add":
                                            $songToAdd = $letterExists[$index]['id'];
                                            $cad->addToList($songToAdd);
                                        break;

                                        case "delete":
                                            $songToAdd = $letterExists[$index]['id'];
                                            $cad->quitFromList($songToAdd);
                                        break;

                                    }
                                }

                                
                            }
                            if($letterExists)
                            {
                                echo "<div class=\"SongSectionTitle\" id=\"$letter\">$letter</div>
                                        <div class=\"SongSection\">";


                                for($i = 0; $i < sizeof($letterExists);$i++)
                                {
                                    if($username != "")
                                    {
                                        $isOnList = $cad->checkIfList($letterExists[$i]['id']);
                                    }
                                    echo "<a href=\"Song.php?song=".$letterExists[$i]['id']."\" class=\"User\">
                                            <div class=\"UserPicture\">
                                                <img src=\"".$cad->getUserImage($letterExists[$i]['idimage'])."\" >
                                            </div>
                                            <div class=\"Info\">
                                                <div class=\"Name\">".$letterExists[$i]['name']."</div>
                                                <div class=\"Id\">by ".$letterExists[$i]['creator']."</div>
                                            </div>";
                                            if($username != "")
                                            {                  
                                                if($isOnList)
                                                {
                                                    if($letterExists[$i]['creator'] != $username)
                                                    {
                                                        echo "<form action=\"Songs.php\" class=\"QuitFromListForm\" method=\"POST\">
                                                                <input type=\"Submit\" name=\"".$i."delete".$letter."\" class=\"Button\" id=\"Register\" value=\"Quitar de mi lista\">
                                                            </form>";
                                                    }
                                                }
                                                else
                                                {
                                                    if($letterExists[$i]['creator'] != $username)
                                                    {
                                                        echo "<form action=\"Songs.php\" class=\"AddToListForm\" method=\"POST\">
                                                                <input type=\"Submit\" name=\"".$i."add".$letter."\" class=\"Button\" id=\"Login\" value=\"Agregar a mi lista\">
                                                            </form>";
                                                    }
                                                }
                                            }
                                        echo "</a>";
                                }
                                        
                                            
                                echo "</div>";
                            }
                                
                            $letter++;
                        }
                    ?>
                        
                </div>
            </div>
       </div>
    </body>
</html>