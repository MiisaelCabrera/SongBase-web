<?php

    require_once "php/CAD.php";

    if(session_status() == PHP_SESSION_ACTIVE)
    {
        session_destroy();
        session_abort();
    }

    $name=$_POST['name'] ?? NULL;
    $lastname=$_POST['lastname'] ?? NULL;
    $username=$_POST['username'] ?? NULL;
    $mail=$_POST['mail'] ?? NULL;
    $password=$_POST['password'] ?? NULL;
    $password_2=$_POST['password_2'] ?? NULL;

    $detectedError = -1;

    if(isset($name) && isset($lastname) && isset($username) && isset($mail) && isset($password) && isset($password_2))
    {
        if($name != "" && $lastname != "" && $username != "" && $mail != "" && $password != "")
        {
            if($password == $password_2)
            {
                $finalUsername = "@".$username;

                $cad = new CAD();
                $detectedError = $cad -> addUser($name, $lastname, $finalUsername, $mail, $password);
            }
            else
            {
                $detectedError = 0;
            }
        }
        else
        {
            $detectedError = 1;
        }
    }

    unset($_POST['name']);
    unset($_POST['lastname']);
    unset($_POST['username']);
    unset($_POST['mail']);
    unset($_POST['password']);
    unset($_POST['password_2']);

?>

<html lang="es">
    <head>
	    <meta charset="utf-8">
        <title>SignUp</title>
        <link rel="stylesheet" type="text/css" href="css/topbarstyle.css"/>
        <link rel="stylesheet" type="text/css" href="css/styles.css"/>
        <script type="text/javascript" src="scripts/Sign-in.js"></script>
    </head>

    <body onload="checkIfError(<?php echo $detectedError?>) ">
       <div class="Page" lang="es" >
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
                        <input type="button" class="Button" value="Inicio"  onclick="window.location.href ='Login.php'">
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
            <div class="Content" id="SignIn" >
                <div class="Title">Registrate</div>
                <div id="Error"></div>
                <form class="SignInSquare" action="Sign-In.php" method="POST">
                    <div class="Requisite">Nombre</div>
                    <input type="text" class="TextBox" name="name" placeholder="Nombre">
                    <div class="Requisite">Apellido</div>
                    <input type="text" class="TextBox" name="lastname" placeholder="Apellido">
                    <div class="Requisite">Nombre de usuario</div>
                    <input type="text" class="TextBox" name="username" placeholder="Nombre de usuario">
                    <div class="Requisite">Correo electrónico</div>
                    <input type="text" class="TextBox" name="mail" placeholder="Correo electrónico">
                    <div class="Requisite">Contraseña</div>
                    <input type="password" class="TextBox" name="password" placeholder="Contraseña">
                    <div class="Requisite">Confirmar contraseña</div>
                    <input type="password" class="TextBox" name="password_2" placeholder="Confirmar contraseña">
                    <input type="submit" class="Button" value="Registrate" id="Login">
                    <div class="Text">¿Ya tienenes cuenta?</div>
                    <input type="button" class="Button" value="Inicia sesión" id="Register" onclick="window.location.href ='Login.php'">
                </form>
            </div>
       </div>
    </body>
</html>