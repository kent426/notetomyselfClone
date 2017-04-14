<?php

session_start();

/*Create a 220x35 image*/
$im = imagecreatetruecolor(150, 50);



/*Color code for orange*/
$white = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);

/*Color code for white*/
$black = imagecolorallocate($im, 0x00, 0x00, 0x00);

/*Generate a random string using md5*/
$md5_hash = md5(rand(0,999));

/*Trim the string down to 6 characters*/
$captcha_code = substr($md5_hash, 15, 4);

/*Store the value of the generated captcha code in session*/
$_SESSION['captcha'] = $captcha_code;

/* Set the background as orange */
imagefilledrectangle($im, 0, 0, 150, 50, $white);

$line_color = imagecolorallocate($im, 64,64,64);
for($i=0;$i<10;$i++) {
    imageline($im,0,rand()%50,200,rand()%50,$line_color);
}

/*Path where TTF font file is present*/
$font_file = './TURNB___.TTF';

/* Draw our randomly generated code*/
imagefttext($im, 35, 0, 5, 30, $black, $font_file, $captcha_code);

/* Output the image to the browser*/
header('Content-Type: image/png');
imagepng($im);

/*Destroy*/
imagedestroy($im);
?>