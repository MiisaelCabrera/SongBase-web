<?php 
    require_once "php/CAD.php";

    session_start();

    $username = $_SESSION['username'] ?? NULL;

    if($username != "")
    {
        $image=$_FILES['image'] ?? NULL;
        $tmp_name = $image['tmp_name'] ?? NULL;
        $img_type = $image['type'] ?? NULL;

        $descriptionProfile = $_POST['descriptionProfile'] ?? NULL;
        $description = $_POST['description'] ?? NULL;
        
        $directory = "images";

        $detectedError = -1;
        $cad = new CAD();
        
        if((strpos($img_type, "gif") || strpos($img_type, "jpeg") || strpos($img_type, "jpg")) || strpos($img_type, "png"))
        {
            $date = date("dmY");
            $destiny = $directory."/sb".$date;
            $detectedError = $cad -> addImage($destiny, $tmp_name, $description);
        }

        if(isset($descriptionProfile))
        {
            $cad->changeDescription($descriptionProfile);
        }


        unset($_POST);

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
        <title>Edit Profile</title>
        <link rel="stylesheet" type="text/css" href="css/topbarstyle.css"/>
        <link rel="stylesheet" type="text/css" href="css/styles.css"/>
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
            <div class="Content" id="ProfilePicture">
                <div class="square">
                    <div class="Title">Hola <?php echo $_SESSION['name'] ?> :D</div>
                    <div class="Subtitle">Cambiar foto de perfil</div>
                    <form class="ProfilePictureForm" action="EditProfile.php" name="ProfilePicture" method="POST" enctype="multipart/form-data">
                        <h2>Cambiar descripción de perfil</h2>
                        <input type="text" class="TextContainer"  name="descriptionProfile">
                        <h2>Inserte un encabezado de foto</h2>
                        <input type="text" class="TextContainer"  name="description">
                        <h2>Seleccione una imagen</h2>
                        <label for="imgSelect" class=DropContainer id="dropContainer">
                            <span class="DropTitle">Arrastra el archivo aquí</span>
                            o
                            <input type="file"  name="image" id="imgSelect"  accept="image/*">
                        </label>
                        <input type="submit" class="Button" id="Login" value="Actualizar perfil" >
                    </form>

                </div>

                
            </div>
       </div>
    </body>
</html>