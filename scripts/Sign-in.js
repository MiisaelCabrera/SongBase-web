function checkIfError(error)
{
    console.log(error);

    if(error == 1)
    {
        var errorDiv =  document.getElementById('Error');
        errorDiv.innerHTML =  `Por favor rellena todos los campos`;
        errorDiv.classList.add("SignInError");
        var formSquare = document.getElementsByClassName('SignInSquare');
        formSquare[0].style.borderTopLeftRadius = 0;
        formSquare[0].style.borderTopRightRadius = 0;
    }
    else if(error == 0)
    {
        var errorDiv =  document.getElementById('Error');
        errorDiv.innerHTML =  `Las contraseñas no coinciden`;
        errorDiv.classList.add("SignInError");
        var formSquare = document.getElementsByClassName('SignInSquare');
        formSquare[0].style.borderTopLeftRadius = 0;
        formSquare[0].style.borderTopRightRadius = 0;
    }

    else if(error == 2)
    {
        var errorDiv =  document.getElementById('Error');
        errorDiv.innerHTML =  `El nombre de usuario ya existe. Intenta con otro`;
        errorDiv.classList.add("SignInError");
        var formSquare = document.getElementsByClassName('SignInSquare');
        formSquare[0].style.borderTopLeftRadius = 0;
        formSquare[0].style.borderTopRightRadius = 0;
    }

    else if(error == 3)
    {
        var errorDiv =  document.getElementById('Error');
        errorDiv.innerHTML =  `Hubo un error. Por favor intentalo más tarde`;
        errorDiv.classList.add("SignInError");
        var formSquare = document.getElementsByClassName('SignInSquare');
        formSquare[0].style.borderTopLeftRadius = 0;
        formSquare[0].style.borderTopRightRadius = 0;
    }
}