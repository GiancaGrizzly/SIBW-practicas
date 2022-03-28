/* direccion_email = usuario + @ + servidor + dominio
 * usuario
 *   comienza por al menos una letra o número \w+
 *   seguida de 0 o más combinaciones de punto o guión y letras o números ((\.|-)\w+)*
 * servidor
 *   al menos una letra o número \w+
 * dominio
 *   comienza por punto \.
 *   le siguen 2, 3 o 4 letras \w{2,3,4}
 *   además, puede haber varios dominios (\.\w{2,3,4})+
 */

function openForm()
{
    document.getElementById("id-container-form").style.display = "block";
    document.getElementById("boton").style.display = "none";
}

function closeForm()
{
    document.getElementById("id-container-form").style.display = "none";
    document.getElementById("boton").style.display = "block";
}

function comprobarEmail(email)
{
    if (!/^\w+((\.|-)\w+)*@\w+(\.\w{2,4})+$/.test(email)) {
        alert("Dirección de correo electrónico inválida");
        document.getElementById("id-email").value = "";
    }
}