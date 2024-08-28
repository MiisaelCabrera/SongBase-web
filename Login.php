<?php

    require_once "php/CAD.php";

    session_start();
    
    $username=$_POST['username'] ?? NULL;
    $password=$_POST['password'] ?? NULL;
    
    if(session_status() == PHP_SESSION_ACTIVE)
    {
        $_SESSION['username'] = "";
        session_unset();
        session_destroy();
        session_abort();
    }

    $detectedError = -1;

    if(isset($username) && isset($password))
    {
        if($username != "" && $password != "")
        {
            $cad = new CAD();
            $detectedError = $cad -> verifyUser($username, $password);              

        }
        else
        {
            $detectedError = 1;
        }
    }

    

    unset($_POST['username']);
    unset($_POST['password']);

?>

<html lang="es">
    <head>
	    <meta charset="utf-8">
        <title>LogIn</title>
        <link rel="stylesheet" type="text/css" href="css/topbarstyle.css"/>
        <link rel="stylesheet" type="text/css" href="css/styles.css"/>
        <script type="text/javascript" src="scripts/Login.js"></script>
    </head>
    <body onload="checkIfError(<?php echo $detectedError?>) ">
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
                        <input type="button" class="Button" value="Inicio" onclick="window.location.href ='Login.php'" >
                        <input type="button" class="Button" value="Canciones" onclick="window.location.href ='Songs.php'">
                        <input type="button" class="Button" value="Comunidades" onclick="window.location.href ='Communities.php'">
                        <input type="button" class="Button" value="Ejercicios" onclick="window.location.href ='Exercises.php'">
                    </div>
                    <div class="LoginButtons">
                        <input type="button" class="Button" value="Iniciar sesión" id="Login" onclick="window.location.href ='Login.php'">
                        <input type="button" class="Button" value="Registrate" id="Register" onclick="window.location.href ='Sign-in.php'">
                    </div>
                </div> 
            </div>
            <div class="Content" id="LoginPage">
                <div class="HalfPage" id="PageName">SongBase</div>
                <div class="HalfPage">
                    <div id="Error"></div>
                    <form class="LoginSquare" action="Login.php" method="POST">
                        <input type="text" class="TextBox" name="username" placeholder="@Nombre_de_usuatrio">
                        <input type="password" class="TextBox" name="password" placeholder="Contraseña">
                        <input type="submit" class="Button" value="Iniciar sesión" id="Login" name="Lgn">
                        <input type="button" class="Button" value="Registrate" id="Register" onclick="window.location.href ='Sign-in.php'">
                        <input type="button" class="PasswordButton" value="¿Olvidaste tu Contraseña?">
                    </form>
                </div>
            </div>
       </div>
    </body>
</html>