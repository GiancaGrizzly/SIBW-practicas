function openForm()
{
    document.getElementById("id-container-formulario").style.display = "block";
    document.getElementById("boton").style.display = "none";
}

function closeForm()
{
    document.getElementById("id-container-formulario").style.display = "none";
    document.getElementById("boton").style.display = "block";
}

function comprobarEmail(email)
{
    document.getElementById("id-email").style.backgroundColor = "red";
}