<?php
    require_once "php/CAD.php";

    session_start();

    $cad = new CAD();

    $community = $_GET['community'];

    $username = $_SESSION['username'] ?? NULL;
    $textpost = $_POST['textpost'] ?? NULL;

    $join = $_POST['join'] ?? NULL;
    $leave = $_POST['leave'] ?? NULL;
    
    $quit = $_POST['quit'] ?? NULL;

    echo $leave;

    $postResults = $cad->getCommunityPosts($community);
    
    if($username != "")
    {
        $profilePictureLocation = $cad -> getProfilePicture();
    }
    else
    {
        header("Location: Login.php");
    }

    if(isset($textpost) && $textpost != "")
    {
        $cad->postCommunity($textpost, $community);
    }

    if(isset($join))
    {
        $cad->joinCommunity($community);
    }

    if(isset($leave))
    {
        $cad->leaveCommunity($community);
    }

    if(isset($quit))
    {
        $cad->deleteCommunity($community);
    }






    $keys = array_keys($_POST);
        if($keys)
        {
            preg_match_all('/[0-9]+/', $keys[0], $matches);
            if($matches[0])
            {
                $index = $matches[0][0];
                $keyWithoutIndex = preg_replace('/[0-9]+/', "", $keys);
                $actionName = $keyWithoutIndex[0];

                
                switch($actionName)
                {
                    case "like":
                        $postToLike = $postResults[$index]['idpost'];
                        $cad->like($postToLike);
                    break;
                        
                    case "dislike":
                        $postToLike = $postResults[$index]['idpost'];
                        $cad->dislike($postToLike);
                    break;

                    case "delete":
                        $postToDelete = $postResults[$index]['idpost'];
                        $cad->deletePost($postToDelete);
                    break;

                    

                }
            }

            
        }

        
    $postResults = $cad->getCommunityPosts($community);
    $isOnCommunity = $cad->isOnCommunity($community);
    $communityData = $cad->getCommunityData($community);
    $coverPicture = $cad->getUserImage( $communityData[4]);
    

?>

<html lang="es">
    <head>
	    <meta charset="utf-8">
        <title>Home</title>
        <link rel="stylesheet" type="text/css" href="css/topbarstyle.css"/>
        <link rel="stylesheet" type="text/css" href="css/styles.css"/>
        <link rel="stylesheet" type="text/css" href="css/timeline.css"/>
        <link rel="stylesheet" type="text/css" href="css/community.css"/>

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
                        <input type="button" class="Button" value="Inicio" onclick="window.location.href ='TimeLine.php'">
                        <input type="button" class="Button" value="Canciones" onclick="window.location.href ='Songs.php'">
                        <input type="button" class="Button" value="Comunidades" onclick="window.location.href ='Communities.php'">
                        <input type="button" class="Button" value="Ejercicios" onclick="window.location.href ='Exercises.php'">
                    </div>
            
                    <div class="ProfileButtons">            
                        <a href="MyProfile.php" class="ProfilePicture">   
                            <img src="<?php echo $profilePictureLocation ?>" id="ProPicture">
                            <div class="ProfileButton">Mi Perfil</div>   
                        </a>

                        <a href="Login.php" class="Exit">   
                            <img src="images/exit.png" id="ExitPicture">
                            <div class="ExitButton">Cerrar sesión</div> 
                        </a>
                    </div>
                </div>
               
            </div>
            <div class="Content" id="Community">
                <div class="CommunityInfo">
                    <img src="<?php echo $coverPicture?>" class="Cover">
                    <div class="CommunityData">
                        <div class="TextFields">
                            <div class="Name"><?php echo $communityData[1]?></div>
                            <div class="Admin">Administrador: <?php echo $communityData[3]?></div>
                            <div class="Date">Fecha de creación: <?php echo $communityData[2]?></div>
                        </div>
                        <form class="CommunityButtons" action="Community.php?community=<?php echo $community?>" method="POST">
                            <?php
                                if($isOnCommunity)
                                {
                                    if($communityData[3] == $username)
                                    {
                                        echo "<input type=\"submit\" name=\"quit\" class=\"Delete\" value=\"\">";
                                    }
                                    else
                                    {
                                        echo "<input type=\"submit\" name=\"leave\" class=\"Button\" id=\"Register\" value=\"Abandonar la comunidad\">";
                                    }
                                }
                                else
                                {
                                    echo "<input type=\"submit\" name=\"join\" class=\"Button\" id=\"Login\" value=\"Unirte a la comunidad\">";
                                }
                                
                            ?>
                        </form>
                    </div>
                    <div class="Description">
                        <p>
                            <?php echo $communityData[5]?>
                        </p>
                    </div>
                    
                </div>
                <div class="CommunityPosts">
                    <?php   
                    if($isOnCommunity)   
                    {                  
                        echo "<form action=\"Community.php?community=".$community."\" method=\"POST\" class=\"PostSomething\">
                            <div class=\"PhotoText\">
                                <img src=\"".$profilePictureLocation."\" class=\"PostProfilePicture\" onclick=\"window.location.href ='MyProfile.php'\">
                                <input type=\"text\" class=\"TextPost\" name=\"textpost\" placeholder=\"Publica algo\">
                            </div>
                            <div class=\"ButtonPost\">
                                <div class=\"Text\">Publicar</div>
                                <label for=\"imgButton\" class=\"Button\">
                                    <img src=\"images/image.png\" class=\"ButtonIcon\">
                                    Imagen
                                </label>
                                <label for=\"songButton\" class=\"Button\">
                                    <img src=\"images/note.png\" class=\"ButtonIcon\">
                                    Canción
                                </label>
                                <input type=\"button\" id=\"imgButton\" style=\"visibility:hidden;\" onclick=\"window.location.href ='AddPictureCommunity.php?community=".$community."'\">
                                <input type=\"button\" id=\"songButton\" style=\"visibility:hidden;\"  onclick=\"window.location.href ='AddSongCommunity.php?community=".$community."'\">
                                <input type=\"submit\" value=\"Publicar\" class=\"Button\" id=\"Posting\">
                                
                            </div>
                        </form>  ";
                            if($postResults && $isOnCommunity)
                            {
                                for($i = sizeof($postResults)-1; $i >= 0; $i--)
                                {
                                    $userImage = $cad->getUserImage($cad->getUserImageId( $postResults[$i]['username']));
                                    $date = date("d/m/Y",strtotime($postResults[$i]['date']));
                                    echo "<form action=\"Community.php?community=".$community."#".$i."\" class=\"Post\" method=\"POST\" id=\"".$i."\">
                                    <div class=\"PhotoText\">
                                        <img src=\"".$userImage."\" class=\"PostProfilePicture\" onclick=\"window.location.href ='user.php?user=".$postResults[$i]['username']."'\">
                                        <div class=\"PostInfo\">
                                            <a href=\"user.php?user=".$postResults[$i]['username']."\" class=\"Name\">".$postResults[$i]['name']." ".$postResults[$i]['lastname']."</a>";
                                            if($postResults[$i]['type'] == "prof")
                                            echo " &nbsp; &nbsp;  Actualizó su foto de perfil";
                                            echo "<div class=\"Date\">".$date."</div>
                                        </div>";
                                        
                                    if($postResults[$i]['username'] == $username)
                                        echo "<input type=\"submit\" name=\"".$i."delete\" id=\"deletePost\" class=\"DeleteButton\" value=\"\">";
                                        
                                    echo "</div>";
                                    if($postResults[$i]['text'] != "")
                                    {
                                        echo "<div class=\"PostText\">".$postResults[$i]['text']."</div>";
                                    }
                                    if($postResults[$i]['idsong'] != "")
                                    {
                                        $songData = $cad->getsong($postResults[$i]['idsong']);
                                        echo "<a href=\"song.php?song=".$postResults[$i]['idsong']."\" class=\"UserSongInfoPost\">
                                                    <img src=\"".$cad->getUserImage($songData[4])."\" class=\"UserSongImage\" >
                                                    <div class=\"SongData\">
                                                        <div class=\"Name\">".$songData[1]."</div>
                                                        <div class=\"Id\">by ".$songData[2]."</div>
                                                    </div>
                                                </a>";
                                    }
                                    if($postResults[$i]['id'] != "")
                                    {
                                        $image = $cad->getImage($postResults[$i]['id']);
                                        echo "<div class=\"PostImg\">
                                                <img src=\"$image\">
                                            </div>";
                                    }
                                    $likes = $cad->likesCount($postResults[$i]['idpost']);
                                    echo "<div class=\"ButtonPost\">
                                                <div class=\"LikesCount\">
                                                    <img src=\"images/like.png\" class=\"LikeButton\">
                                                    $likes
                                                </div>";
                                    if($username != "")
                                    {
                                        if($cad->checkIfLike($postResults[$i]['idpost']))
                                        {
                                            echo "<input type=\"submit\" id=\"LikedBar\" name=\"".$i."dislike\" value=\"      Dislike\"  class=\"Button\">";
                                        }
                                        else
                                        {
                                            echo "<input type=\"submit\" id=\"LikeBar\" name=\"".$i."like\" value=\"     Like\" class=\"Button\" >";
                                        }
                                    }
                                    echo "</div>
                                        </form>";
                                }
                            }
                        }
                        else
                        {
                            echo "<div class=\"NoUser\"> Debes unirte a esta comunidad para poder ver el contenido ;) </div>";
                        }
                        ?> 

                        
                        
                </div>
            </div>
       </div>
    </body>
</html>