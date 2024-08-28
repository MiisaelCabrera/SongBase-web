<?php 
    require_once "php/CAD.php";

    session_start();

    $username = $_SESSION['username'] ?? NULL;

    if($username != "")
    {
        $image=$_FILES['image'] ?? NULL;
        $tmp_name = $image['tmp_name'] ?? NULL;
        $img_type = $image['type'] ?? NULL;

        $description = $_POST['description'] ?? NULL;
        $name =  $_POST['name'] ?? NULL;

        $imgId = 2;
        
        $imgDirectory = "images";

        $detectedError = -1;
        $cad = new CAD();

        $scaleList = $cad->getScales();

        if(isset($name))
        {
            $name = ucfirst($name);
            $date = date("dmY");
            if($image!=NULL)
            {
                $imgdestiny = $imgDirectory."/sbcommunity".$username.$date;
                $imgId = $cad->addSongImage($imgdestiny, $tmp_name);
            }

            $cad->addCommunity($name, $imgId, $description);

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
                    <div class="Subtitle">Crear una comunidad</div>
                    <form class="ProfilePictureForm" action="AddCommunity.php" name="ProfilePicture" method="POST" enctype="multipart/form-data">
                        <div class="MiniFields">
                            <div class="MiniField">
                                <h2 class="SubtitleMini">Nombre de la comunidad</h2>
                                <input type="text" class="TextContainerMini"  name="name">
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
                        
                        <input type="submit" class="Button" id="Login" value="Subir imagen" >
                    </form>

                </div>

                
            </div>
       </div>
    </body>
</html>