<?php 
    require_once "php/CAD.php";

    $cad = new CAD();

    session_start();

    $username = $_SESSION['username'] ?? NULL;
    $textpost = $_POST['textpost'] ?? NULL;
    $nav = $_GET['nav'] ?? 1;
    $likes =  NULL;
    $actionName = NULL;
    
    if($username != "")
    {
        $profileData = $cad -> getProfileData();
        
        $follorwes = $cad->followers($username);
        $following = $cad->following($username);
        
        $postResults = $cad->getPostsFromUser($username);
        
        if(isset($textpost) && $textpost != "")
        {
            $cad->postText($textpost);
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
        $postResults = $cad->getPostsFromUser($username);
        $imageResults = $cad->getImagesFromUser($username);
        $songResults = $cad->getSongsByUser($username);
        $profilePictureLocation = $cad -> getProfilePicture();
        
    }
    else
    {
        header("Location: Login.php");
    }

    unset($_POST);


?>

<html lang="es">
    <head>
	    <meta charset="utf-8">
        <title>My Profile</title>
        <link rel="stylesheet" type="text/css" href="css/topbarstyle.css"/>
        <link rel="stylesheet" type="text/css" href="css/styles.css"/>
        <link rel="stylesheet" type="text/css" href="css/profileposts.css"/>
        <link rel="stylesheet" type="text/css" href="css/gallery.css"/>
        <link rel="stylesheet" type="text/css" href="css/songsbyuser.css"/>
        <script type="text/javascript" src="scripts/ProfileTab.js"></script>
        </script>
    </head>

    <body onload="showHideSection(<?php echo $nav;?>)">
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
            <div class="Content" id="Profile">
                <div class="ProfileInfo">
                    <div class="Image">
                        <img id="PP" src="<?php echo $profilePictureLocation ?>">
                    </div>
                    <div class="Information">
                        <div class="TextContent">
                            <div class="Name"><?php echo $profileData[1]." ".$profileData[2] ?> </div>
                            <div class="Username"><?php echo $profileData[0] ?></div>
                        </div>
                        <form class="Follow">
                            <input type="button" class="Button" id="Login" value="Editar perfil" onclick="window.location.href ='EditProfile.php'">
                        </form>
                        <div class="FollowersSection">
                            <div class="text"><?php echo $follorwes-1?> Seguidores</div>
                            <div class="text"><?php echo $following-1?> Seguidos</div>
                        </div>
                        <div class="TextContent" id="Description">
                            Descripción
                            <div class="Abstract"> <?php echo $profileData[6] ?></div>
                        </div>
                    </div>
                </div>
                <div class="ProfileContent">
                    <div class="TabMenu">
                        <div class="Button" onclick="showHideSection(1)">Publicaciones</div>
                        <div class="Button" onclick="showHideSection(2)">Galería</div>
                        <div class="Button" onclick="showHideSection(3)">Canciones</div>
                    </div>

                    <div class="ProfilePostsDashboard" id="p1">
                        <form action="MyProfile.php" method="POST" class="PostSomething">
                            <div class="PhotoText">
                                <img src="<?php echo $profilePictureLocation ?>" class="PostProfilePicture" onclick="window.location.href ='MyProfile.php'">
                                <input type="text" class="TextPost" name="textpost" placeholder="Publica algo">
                            </div>
                            <div class="ButtonPost">
                                <div class="Text">Publicar</div>
                                <label for="imgButton" class="Button">
                                    <img src="images/image.png" class="ButtonIcon">
                                    Imagen
                                </label>
                                <label for="songButton" class="Button">
                                    <img src="images/note.png" class="ButtonIcon">
                                    Canción
                                </label>
                                <input type="button" id="imgButton" style="visibility:hidden;" onclick="window.location.href ='AddPicture.php'">
                                <input type="button" id="songButton" style="visibility:hidden;"  onclick="window.location.href ='AddSong.php'">
                                <input type="submit" value="Publicar" class="Button" id="Posting">
                                
                            </div>
                        </form>
                        <?php
                            if($postResults)
                            {
                                for($i = sizeof($postResults)-1; $i >= 0; $i--)
                                {
                                    $date = date("d/m/Y",strtotime($postResults[$i]['date']));
                                    echo "<form action=\"MyProfile.php#".$i."\" class=\"Post\" method=\"POST\" id=\"".$i."\">
                                    <div class=\"PhotoText\">
                                        <img src=\"$profilePictureLocation\" class=\"PostProfilePicture\" onclick=\"window.location.href ='MyProfile.php'\">
                                        <div class=\"PostInfo\">
                                            <a href=\"MyProfile.php\" class=\"Name\">".$profileData[1]." ".$profileData[2]."</a>";
                                        if($postResults[$i]['type'] == "prof")
                                            echo " &nbsp; &nbsp;  Actualizó su foto de perfil";
                                        echo "<div class=\"Date\">".$date."</div>
                                        </div>
                                        <input type=\"submit\" name=\"".$i."delete\" id=\"deletePost\" class=\"DeleteButton\" value=\"\">
                                        
                                    </div>";
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
                                    if($cad->checkIfLike($postResults[$i]['idpost']))
                                    {
                                        echo "<input type=\"submit\" id=\"LikedBar\" name=\"".$i."dislike\" value=\"      Dislike\"  class=\"Button\">";
                                    }
                                    else
                                    {
                                        echo "<input type=\"submit\" id=\"LikeBar\" name=\"".$i."like\" value=\"     Like\" class=\"Button\" >";
                                    }
                                    echo "</div>
                                        </form>";
                                }
                            }
                        ?>
                        
                    </div>
                    
                    <div class="Gallery" id="p2">
                        <div class="GallerySquare">
                            <div class="ImgContainer">
                                <?php
                                    if($imageResults)
                                    {
                                        for($i = sizeof($imageResults)-1; $i >= 0; $i--)
                                        {
                                            echo "<div class=\"container\">
                                                    <img src=\"".$imageResults[$i]['image']."\" id=\"i".$i."\" class=\"Image\" onclick=\"showPhoto($i,".sizeof($imageResults)-1 .")\">
                                                </div>";
                                        }
                                    }
                                    else
                                        echo "<p class=\"NoPhotos\">Aún no tienes ninguna foto :(</p>";
                                ?>
                            </div>
                        </div>
                        <div class="OpenPicture" id="openPicture">
                            <div class="OpenPictureBar">
                                <div class="OpenPictureUser">
                                    <img src="<?php echo $profilePictureLocation; ?>">
                                </div>
                                <div class="OpenPictureUser">
                                    <p class="Name"><?php echo $profileData[1]." ".$profileData[2] ?></p>
                                    <p class="Username"><?php echo $username ?></p>
                                </div>
                                <div class="NavArrows">
                                    <div class="Nav" onclick="nextPhoto()"><</div>
                                    <div class="Nav" onclick="previousPhoto()">></div>
                                </div>
                                <div class="Close">
                                    <div class="CloseButton" onclick="hidePhoto()">✖</div>
                                </div>
                            </div>
                            <div class="OpenPictureContainer">
                                <img src="" id="selectedImage">
                            </div>
                        </div>
                    </div>
                    <div class="UserSongs" id="p3">
                        <?php
                        if($songResults)
                        {
                            for($i = sizeof($songResults)-1; $i>=0;$i--)
                            {
                            echo "<a href=\"song.php?song=".$songResults[$i]['id']."\" class=\"UserSongInfo\">
                                <img src=\"".$cad->getUserImage($songResults[$i]['idimage'])."\" class=\"UserSongImage\" >
                                <div class=\"SongData\">
                                    <div class=\"Name\">".$songResults[$i]['name']."</div>
                                    <div class=\"Id\">by ".$songResults[$i]['creator']."</div>
                                </div>
                            </a>";
                            }
                        }
                        else
                            echo "Aún no tienes ninguna canción :("
                        ?>
                    </div>
                    
                </div>
            </div>
       </div>
    </body>
</html>