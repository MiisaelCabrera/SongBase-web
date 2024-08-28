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
        <link rel="stylesheet" type="text/css" href="css/Communities.css"/>
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
                    <div class="Title">Comunidades</div>
                    <div class="ButtonCreate" id="Login" onclick="window.location.href ='AddCommunity.php'">Crear una comunidad</div>
                    <?php

                        $letter = 'A';
                        for($i = 0; $i<26 ; $i++)
                        {
                            $letterExists = $cad -> getCommunityWithLetter($letter);
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
                                        case "join":
                                            $communityToJoin = $letterExists[$index]['id'];
                                            $cad->joinCommunity($communityToJoin);
                                        break;

                                        case "leave":
                                            $communityToJoin = $letterExists[$index]['id'];
                                            $cad->leaveCommunity($communityToJoin);
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
                                        $isOnList = $cad->isOnCommunity($letterExists[$i]['id']);
                                    }
                                    echo "<a href=\"Community.php?community=".$letterExists[$i]['id']."\" class=\"User\">
                                            <div class=\"UserPicture\">
                                                <img src=\"".$cad->getUserImage($letterExists[$i]['imageid'])."\" >
                                            </div>
                                            <div class=\"Info\">
                                                <div class=\"Name\">".$letterExists[$i]['name']."</div>
                                                <div class=\"Id\">by ".$letterExists[$i]['admin']."</div>
                                            </div>";
                                            if($username != "")
                                            {                  
                                                if($isOnList)
                                                {
                                                    if($letterExists[$i]['admin'] != $username)
                                                    {
                                                        echo "<form action=\"Communities.php\" class=\"QuitFromListForm\" method=\"POST\">
                                                                <input type=\"Submit\" name=\"".$i."leave".$letter."\" class=\"Button\" id=\"Register\" value=\"Abandonar\">
                                                            </form>";
                                                    }
                                                }
                                                else
                                                {
                                                    if($letterExists[$i]['admin'] != $username)
                                                    {
                                                        echo "<form action=\"Communities.php\" class=\"AddToListForm\" method=\"POST\">
                                                                <input type=\"Submit\" name=\"".$i."join".$letter."\" class=\"Button\" id=\"Login\" value=\"Unirse\">
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