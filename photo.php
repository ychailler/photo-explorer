<?php

include "common.php";

header("Content-type: image/jpeg");

if( isAuthorized($path, true) ) {

	if($type=="photo") {
		$im = file_get_contents("$photos_path/$image");
		echo $im;
	}
	else if($type=="thumb") {
	        $im = file_get_contents("$thumb_path/$image");
	        echo $im;
	}
}

?>
