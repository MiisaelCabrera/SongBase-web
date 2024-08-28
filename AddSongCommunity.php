<?php 
    require_once "php/CAD.php";

    session_start();

    $username = $_SESSION['username'] ?? NULL;
    $community = $_GET['community'];

    if($username != "")
    {
        $image=$_FILES['image'] ?? NULL;
        $tmp_name = $image['tmp_name'] ?? NULL;
        $img_type = $image['type'] ?? NULL;

        $file=$_FILES['file'] ?? NULL;
        $file_tmp_name = $file['tmp_name'] ?? NULL;
        $txt_type = $file['type'] ?? NULL;

        $description = $_POST['description'] ?? NULL;
        $name =  $_POST['name'] ?? NULL;
        $instrument = $_POST['instrument'] ?? NULL;
        $scale = $_POST['scale'] ?? NULL;
        $type = $_POST['type'] ?? NULL;

        $imgId = 1;
        
        $imgDirectory = "images";
        $txtDirectory = "files";

        $detectedError = -1;
        $cad = new CAD();

        $scaleList = $cad->getScales();

        if(isset($name) && isset($instrument) && isset($scale) && isset($type) && $txt_type = "text/plain")
        {
            $name = ucfirst($name);
            $date = date("dmY");
            if($image!=NULL)
            {
                $imgdestiny = $imgDirectory."/sbsongby".$username.$date;
                $imgId = $cad->addSongImage($imgdestiny, $tmp_name);
            }
            $txtdestiny = $txtDirectory."/sbsongby".$username.$name.$date;

            $cad->addCommunityPostSong($name, $instrument, $scale, $type, $imgId, $txtdestiny, $file_tmp_name, $description, $community);

        }

        unset($_POST['submit']);
        unset($_POST['image']);

        $profilePictureLocation = $cad -> getProfilePicture();
    } 
    else
    {
        header("Location: Login.php");
    }

?>

<html lang="es">
    <head>
	    <meta charset="utf-8">
        <title>Upload a song</title>
        <link rel="stylesheet" type="text/css" href="css/topbarstyle.css"/>
        <link rel="stylesheet" type="text/css" href="css/styles.css"/>
        <link rel="stylesheet" type="text/css" href="css/addsong.css"/>
        <script type="text/javascript" src="scripts/DragNDrop.js"></script>
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
            <div class="Content" id="AddSong">
                <div class="square">
                    <div class="Title">Hola <?php echo $_SESSION['name'] ?> :D</div>
                    <div class="Subtitle">Subir una canción</div>
                    <form class="ProfilePictureForm" action="AddSongCommunity.php?community=<?php echo $community;?>" name="ProfilePicture" method="POST" enctype="multipart/form-data">
                        <div class="MiniFields">
                            <div class="MiniField">
                                <h2 class="SubtitleMini">Nombre de la canción</h2>
                                <input type="text" class="TextContainerMini"  name="name">
                            </div>
                            <div class="MiniField">
                                <h2 class="SubtitleMini">Instrumento</h2>
                                <input type="text" class="TextContainerMini"  name="instrument">
                            </div>
                        </div>
                        <div class="MiniFields">
                            <div class="MiniField">
                                <h2 class="SubtitleMini">Elije la escala</h2>
                                <select list="scaleList"  name="scale" class="ListContainerMini">
                                    <option value="" selected disabled hidden>Selecciona una escala</option>
                                    <?php
                                        for($i = 0; $i < sizeof($scaleList); $i++)
                                        {
                                            echo "<option value=\"".$scaleList[$i]['id']."\">".$scaleList[$i]['name']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="MiniField">
                                <h2 class="SubtitleMini">Tipo</h2>
                                <select list="scaleList"  name="type" class="ListContainerMini">
                                    <option value="" selected disabled hidden>Selecciona el tipo</option>
                                    <option value="song">Canción</option>"
                                    <option value="exer">Ejercicio</option>"
                                </select>
                            </div>
                        </div>
                        <h2>Inserte una descripción</h2>
                            <input type="text" class="TextContainer"  name="description">
                        <h2>Seleccione una imagen para la portada</h2>
                        <label for="imgSelect" class=DropContainer id="dropContainer">
                            <span class="DropTitle">Arrastra el archivo aquí</span>
                            o
                            <input type="file"  name="image" id="imgSelect"  accept="image/*">
                        </label>
                        <h2>Seleccione el archivo de la canción</h2>
                        <label for="txtSelect" class=DropContainer id="dropContainer">
                            <span class="DropTitle">Arrastra el archivo aquí</span>
                            o
                            <input type="file"  name="file" id="txtSelect"  accept="text/*">
                        </label>
                        
                        <input type="submit" class="Button" id="Login" value="Subir imagen" >
                    </form>

                </div>

                
            </div>
       </div>
    </body>
</html>