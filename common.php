<?php

include "config.php";
include "functions.php";

$page=1;
$limit=$PAGE_SIZE;

parse_str($_SERVER['QUERY_STRING']);

// build path from url
$base = realpath("$THUMB_BASE_DIR$path$THUMB_SUBDIR");
if(strncmp($base, $THUMB_BASE_DIR, strlen($THUMB_BASE_DIR))) {
	//security check, verify that the path is in the photo directory
	$path="";
}
else {
	$path=substr($base, strlen($THUMB_BASE_DIR));
}
if(!isAuthorized($path, false)) {
	$path="";
}
if(strlen($path) == 0 || substr($path,0,1) != "/") {
	$path = "/$path";
}
if(strlen($path) >0 && substr($path,strlen($path)-1,1) == "/") {
	$path = substr($path,0,strlen($path)-1);
}
$photos_path="$PHOTOS_BASE_DIR$path";
$thumb_path="$THUMB_BASE_DIR$path$THUMB_SUBDIR";
?>
