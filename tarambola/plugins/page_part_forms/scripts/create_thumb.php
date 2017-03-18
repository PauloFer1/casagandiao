<?php
$picInit = new Imagick($_POST['server_path'].'/public/images/'.$_POST['name']);
$pic = clone $picInit;
$pic->cropimage($_POST['width'], $_POST['height'], $_POST['pos_x'], $_POST['pos_y']);
$pic->resizeimage(184, 128, imagick::FILTER_UNDEFINED, 1);

/*
$crop = imagecreatetruecolor($_POST['width'], $_POST['height']);
$img = imagecreatefromjpeg($_POST['path'].'/public/images/'.$_POST['image_path']);
imagecopyresampled($crop, $img, 0, 0, $_POST['pos_x'], $src_y, $dst_w, $dst_h, $src_w, $src_h)
 * */


require_once("../../../../config.php");
$uploaddir = SERVER_URL."public/images/";//'uploads/';
	$t = (string) microtime();
        $t2 = preg_split("/ /", $t);
	$t3 = implode("", $t2);
	$t4 = explode(".", $t3);
	$t5 = implode ("", $t4);

$uploadfile = $uploaddir . basename($t5.$_POST['name']);

$pic->writeimage($uploadfile);

echo($t5.$_POST['name']);