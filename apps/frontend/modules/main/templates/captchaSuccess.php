<?php


//session_start();



function texto($ancho) {
	$muestra = '';
    $cadena = "0123456789abcdefghijkmnlopqrstwxyz";
    for ($i = 0; $i < $ancho; $i++) {
        $muestra .= $cadena{rand(0, 35)};
    }
    return $muestra;
}

// Define el ancho del texto usando la funcion creada anteriormente.
$_SESSION['captcha'] = texto(5);

// Crea una imagen gif en memoria.

//echo file_exists("images/captcha.GIF");die;

$captcha = imagecreatefromgif("images/captcha.GIF");

// La localizacion de la imagen.
$letras = imagecolorallocate($captcha, 0, 0, 0);

// Unir el texto en la imagen gif creada.
imagestring($captcha, 5, 16, 7, $_SESSION['captcha'], $letras);

// Pone la imagen en cabezera.
header("Content-type: image/gif");

// Muestra la imagen.
imagegif($captcha);
?>
