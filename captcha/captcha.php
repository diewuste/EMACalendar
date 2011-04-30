<?php
/**
 * Source : http://www.notoon.com/45-php-creation-captcha-anti-spam.html
 */
session_start();

if (!defined('ABSPATH'))
	define('ABSPATH', dirname(__FILE__) . '/');

function getCode($length) {
	$chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
	$rand_str = '';
	for ($i = 0; $i < $length; $i++) {
		$rand_str .= $chars{ mt_rand(0, strlen($chars) - 1) };
	}
	return $rand_str;
}

$theCode = getCode(5);
$_SESSION['captcha'] = md5($theCode);
$char1 = substr($theCode, 0, 1);
$char2 = substr($theCode, 1, 1);
$char3 = substr($theCode, 2, 1);
$char4 = substr($theCode, 3, 1);
$char5 = substr($theCode, 4, 1);
$fonts = glob('fonts/*.ttf');

function random($tab) {
	return $tab[array_rand($tab)];
}

$image = imagecreatefrompng('captcha.png');
$colors = array(imagecolorallocate($image, 131, 154, 255),
	imagecolorallocate($image, 89, 186, 255),
	imagecolorallocate($image, 155, 190, 214),
	imagecolorallocate($image, 255, 128, 234),
	imagecolorallocate($image, 255, 123, 123));

imagettftext($image, 28, -10, 0, 37, random($colors), ABSPATH . '/' . random($fonts), $char1);
imagettftext($image, 28, 20, 37, 37, random($colors), ABSPATH . '/' . random($fonts), $char2);
imagettftext($image, 28, -35, 55, 37, random($colors), ABSPATH . '/' . random($fonts), $char3);
imagettftext($image, 28, 25, 100, 37, random($colors), ABSPATH . '/' . random($fonts), $char4);
imagettftext($image, 28, -15, 120, 37, random($colors), ABSPATH . '/' . random($fonts), $char5);

header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>