<?php
    require_once "php/CAD.php";
    session_start();
    $cad = new CAD();

    $search = $_GET['search'];
    $username = $_SESSION['username'] ?? NULL;

    if(isset($search))
    {
        $userResults = $cad->searchUsers($search); 
        $songResults = $cad->searchSongs($search, "song"); 
        $excersiceResults = $cad->searchSongs($search, "exer"); 
        
        $communityResults = $cad->searchCommunities($search); 


    }

    if($username != "")
    {
        $profilePictureLocation = $cad -> getProfilePicture();
    }


    $keys = array_keys($_POST);
    if($keys)
    {
        preg_match_all('/[0-9]+/', $keys[0], $matches);
        $index = $matches[0][0];
        $keyWithoutIndex = preg_replace('/[0-9]+/', "", $keys);
        $actionName = $keyWithoutIndex[0];

        
        switch($actionName)
        {
            case "follow":
                $userToFollow = $userResults[$index]['username'];
                $cad->follow($userToFollow);
            break;
                
            case "unfollow":
                $userToFollow = $userResults[$index]['username'];
                $cad->unfollow($userToFollow);
            break;

            case "add":
                $songToAdd = $songResults[$index]['id'];
                $cad->addToList($songToAdd);
            break;

            case "delete":
                $songToAdd = $songResults[$index]['id'];
                $cad->quitFromList($songToAdd);
            break;

            case "join":
                $communityToJoin = $communityResults[$index]['id'];
                $cad->joinCommunity($communityToJoin);
            break;

            case "leave":
                $communityToJoin = $communityResults[$index]['id'];
                $cad->leaveCommunity($communityToJoin);
            break;

        }

        
    }
    
    
    unset($_POST);

    

?>

<html lang="es">
    <head>
	    <meta charset="utf-8">
        <title>Results</title>
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
            <div class="Content" id="Search">
                <div class="LeftMenu">
                    <a href="#Users" class="Button">Usuarios</a>
                    <a href="#SongsList" class="Button">Canciones</a>
                    <a href="#Exercises" class="Button">Ejercicios</a>
                    <a href="#Communities" class="Button">Comunidades</a>
                </div>
                <div class="Results">
                    <div class="Title">Resultados</div>
                    <div class="SearchSectionTitle" id="Users">Usuarios</div>
                    <div class="SearchSection">
                        <?php
                            if($userResults)
                            {
                                for($i = 0; $i < sizeof($userResults);$i++)
                                {
                                    if($username != "")
                                    {
                                        $isFollowing = $cad->checkIfFollow($userResults[$i]['username']);
                                    }
                                    echo "<a href=\"User.php?user=".$userResults[$i]['username']."&nav=1\" class=\"User\">
                                            <div class=\"UserPicture\">
                                                <img src=\"".$cad->getUserImage($userResults[$i]['id'])."\" >
                                            </div>
                                            <div class=\"Info\">
                                                <div class=\"Name\">".$userResults[$i]['name']." ".$userResults[$i]['lastname']."</div>
                                                <div class=\"Id\">".$userResults[$i]['username']."</div>
                                            </div>";
                                            if($username != "")
                                            {                  
                                                if($isFollowing)
                                                {
                                                    if($userResults[$i]['username'] != $username)
                                                    {
                                                        echo "<form action=\"Search.php?search=$search\" class=\"UnfollowSearchForm\" method=\"POST\">
                                                                <input type=\"Submit\" name=\"".$i."unfollow\" class=\"Button\" id=\"Register\" value=\"Dejar de Seguir\">
                                                            </form>";
                                                    }
                                                }
                                                else
                                                {
                                                    if($userResults[$i]['username'] != $username)
                                                    {
                                                        echo "<form action=\"Search.php?search=$search \" class=\"FollowSearchForm\" method=\"POST\">
                                                                <input type=\"Submit\" name=\"".$i."follow\" class=\"Button\" id=\"Login\" value=\"Seguir\">
                                                            </form>";
                                                    }
                                                }
                                            }
                                        echo "</a>";
                                }
                            }
                            else
                            {
                                echo "No se encontraron resultados";
                            }
                        ?>
                        
                    </div>
                    <div class="SearchSectionTitle" id="SongsList">Canciones</div>
                    <div class="SearchSection"> 
                        <?php
                            if($songResults)
                            {
                                for($i = 0; $i < sizeof($songResults);$i++)
                                {
                                    if($username != "")
                                    {
                                        $isOnList = $cad->checkIfList($songResults[$i]['id']);
                                    }
                                    echo "<a href=\"Song.php?song=".$songResults[$i]['id']."\" class=\"User\">
                                            <div class=\"UserPicture\">
                                                <img src=\"".$cad->getUserImage($songResults[$i]['idImage'])."\" >
                                            </div>
                                            <div class=\"Info\">
                                                <div class=\"Name\">".$songResults[$i]['name']."</div>
                                                <div class=\"Id\">by ".$songResults[$i]['creator']."</div>
                                            </div>";
                                            if($username != "")
                                            {                  
                                                if($isOnList)
                                                {
                                                    if($songResults[$i]['creator'] != $username)
                                                    {
                                                        echo "<form action=\"Search.php?search=$search\" class=\"QuitFromListForm\" method=\"POST\">
                                                                <input type=\"Submit\" name=\"".$i."delete\" class=\"Button\" id=\"Register\" value=\"Quitar de mi lista\">
                                                            </form>";
                                                    }
                                                }
                                                else
                                                {
                                                    if($songResults[$i]['creator'] != $username)
                                                    {
                                                        echo "<form action=\"Search.php?search=$search \" class=\"AddToListForm\" method=\"POST\">
                                                                <input type=\"Submit\" name=\"".$i."add\" class=\"Button\" id=\"Login\" value=\"Agregar a mi lista\">
                                                            </form>";
                                                    }
                                                }
                                            }
                                        echo "</a>";
                                }
                            }
                            else
                            {
                                echo "No se encontraron resultados";
                            }
                        ?>
                        
                    </div>
                    <div class="SearchSectionTitle" id="Exercises">Ejercicios</div>
                    <div class="SearchSection">
                        <?php
                            if($excersiceResults)
                            {
                                for($i = 0; $i < sizeof($excersiceResults);$i++)
                                {
                                    if($username != "")
                                    {
                                        $isOnList = $cad->checkIfList($excersiceResults[$i]['id']);
                                    }
                                    echo "<a href=\"Song.php?song=".$excersiceResults[$i]['id']."\" class=\"User\">
                                            <div class=\"UserPicture\">
                                                <img src=\"".$cad->getUserImage($excersiceResults[$i]['idImage'])."\" >
                                            </div>
                                            <div class=\"Info\">
                                                <div class=\"Name\">".$excersiceResults[$i]['name']."</div>
                                                <div class=\"Id\">by ".$excersiceResults[$i]['creator']."</div>
                                            </div>";
                                            if($username != "")
                                            {                  
                                                if($isOnList)
                                                {
                                                    if($excersiceResults[$i]['creator'] != $username)
                                                    {
                                                        echo "<form action=\"Search.php?search=$search\" class=\"QuitFromListForm\" method=\"POST\">
                                                                <input type=\"Submit\" name=\"".$i."delete\" class=\"Button\" id=\"Register\" value=\"Quitar de mi lista\">
                                                            </form>";
                                                    }
                                                }
                                                else
                                                {
                                                    if($excersiceResults[$i]['creator'] != $username)
                                                    {
                                                        echo "<form action=\"Search.php?search=$search \" class=\"AddToListForm\" method=\"POST\">
                                                                <input type=\"Submit\" name=\"".$i."add\" class=\"Button\" id=\"Login\" value=\"Agregar a mi lista\">
                                                            </form>";
                                                    }
                                                }
                                            }
                                        echo "</a>";
                                }
                            }
                            else
                            {
                                echo "No se encontraron resultados";
                            }
                        ?>
                    </div>
                    <div class="SearchSectionTitle" id="Communities">Comunidades</div>
                    <div class="SearchSection">
                    <?php
                            if($communityResults)
                            {
                                for($i = 0; $i < sizeof($communityResults);$i++)
                                {
                                    if($username != "")
                                    {
                                        $isOnList = $cad->isOnCommunity($communityResults[$i]['id']);
                                    }
                                    echo "<a href=\"Community.php?community=".$communityResults[$i]['id']."\" class=\"User\">
                                            <div class=\"UserPicture\">
                                                <img src=\"".$cad->getUserImage($communityResults[$i]['imageid'])."\" >
                                            </div>
                                            <div class=\"Info\">
                                                <div class=\"Name\">".$communityResults[$i]['name']."</div>
                                                <div class=\"Id\">by ".$communityResults[$i]['admin']."</div>
                                            </div>";
                                            if($username != "")
                                            {                  
                                                if($isOnList)
                                                {
                                                    if($communityResults[$i]['admin'] != $username)
                                                    {
                                                        echo "<form action=\"Search.php?search=$search\" class=\"QuitFromListForm\" method=\"POST\">
                                                                <input type=\"Submit\" name=\"".$i."leave\" class=\"Button\" id=\"Register\" value=\"Abandonar\">
                                                            </form>";
                                                    }
                                                }
                                                else
                                                {
                                                    if($communityResults[$i]['admin'] != $username)
                                                    {
                                                        echo "<form action=\"Search.php?search=$search \" class=\"AddToListForm\" method=\"POST\">
                                                                <input type=\"Submit\" name=\"".$i."join\" class=\"Button\" id=\"Login\" value=\"Unirse\">
                                                            </form>";
                                                    }
                                                }
                                            }
                                        echo "</a>";
                                }
                            }
                            else
                            {
                                echo "No se encontraron resultados";
                            }
                        ?>

                    </div>
                </div>
            </div>
       </div>
    </body>
</html>