<?php

include "common.php";

header( 'content-type: text/html; charset=utf-8' );
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Photo gallery</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/bootstrap-image-gallery.min.css">
	</head>
	<body>
<div class="container-fluid">
<div class="row-fluid">

<div class="span12">
<ul class="breadcrumb">
<li><a href="?path=">Photos</a> <span class="divider">/</span></li>
<?php
if(strlen($path)>1) {
	$chunks = explode('/', $path);
	foreach ($chunks as $i => $chunk) {
		if(strlen($chunk) != 0) {
			$subpath=urlencode(implode('/', array_slice($chunks, 0, $i + 1)));
			print("<li><a href='?path=$subpath'>$chunk</a> <span class='divider'>/</span><li>");
		}
	}
}
?>
</ul>
</div>
</div><!-- row-fluid -->

<div class="row-fluid">
<div class="span3">
<?php
	$files = scandir($photos_path);
	$file_count=0;
	foreach($files as $file){
		if(is_dir("$photos_path/$file")) {
			if($file == "..") {
				if(strlen($path)>1) {
					$parent_dir=urlencode(dirname($path));
					print("<i class='icon-chevron-up'></i><a href='?path=$parent_dir'>$file</a><br>");
				}
			}
			else if($file != "." && $file != "$THUMB_SUBDIR" && isAuthorized("$path/$file", false)) {
				$new_path=urlencode("$path/$file");
				print("<i class='icon-chevron-right'></i><a href='?path=$new_path'>$file</a><br>");
			}
		}
		else {
			$file_count++;
		}
	}

	print("<br><br>");
?>
</div>

<div class="span9">

<?php
	if(isAuthorized("$path", true)) {
		pagination($path, $page, $limit, $file_count);
	}
?>

<div id="gallery" data-toggle="modal-gallery" data-target="#modal-gallery">
<ul class="thumbnails">
<?php
	if(isAuthorized("$path", true)) {
		$file_idx=0;
		foreach($files as $file){
                	if(file_exists("$thumb_path/$file") && !is_dir("$thumb_path/$file")) {
				if($file_idx>=($page-1)*$limit && $file_idx<$page*$limit) {
					$new_path=urlencode($path);
					$image=urlencode($file);
print("<li style='height:100px'><a href='photo.php?type=photo&path=$new_path&image=$image'  title='$image' data-gallery='gallery'><img src='photo.php?type=thumb&path=$new_path&image=$image' class='img-polaroid' style='max-height:100px'></a></li>");
				}
				$file_idx++;
			}
		}
	}
?>
</ul>
</div>

<?php
	if(isAuthorized("$path", true)) {
                pagination($path, $page, $limit, $file_count);
        }
?>

</div><!-- span9 -->
</div><!-- row-fluid -->
</div><!-- container  -->

<div id="modal-gallery" class="modal modal-gallery hide fade" tabindex="-1">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h3 class="modal-title"></h3>
    </div>
    <div class="modal-body"><div class="modal-image"></div></div>
    <div class="modal-footer">
<!--
        <a class="btn btn-primary modal-next">Next <i class="icon-arrow-right icon-white"></i></a>
        <a class="btn btn-info modal-prev"><i class="icon-arrow-left icon-white"></i> Previous</a>
-->
        <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000"><i class="icon-play icon-white"></i> Slideshow</a>
        <a class="btn modal-download" target="_blank"><i class="icon-download"></i> Download</a>
	<a class="btn" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i>Close</a>
    </div>
</div>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/load-image.js"></script>
	<script src="js/bootstrap-image-gallery.js"></script>

</body>
</html>

<?php

function pagination($path, $page, $limit, $file_count) {
	if($file_count<=0) {
		return;
	}
	print("<div class='pagination pagination-mini'>");
	print("<ul>");

	$enc_path=urlencode($path);
        if($page==1) {
                print("<li class='disabled'><a href='#'>&laquo;</a></li>");
        }
        else {
                $prev=$page-1;
                print("<li><a href='?path=$enc_path&page=$prev&limit=$limit'>&laquo;</a></li>");
        }
        for($i=1;$i<($file_count/$limit)+1;$i++) {
                if($i == $page) {
                        print("<li class='active'><a href='#'>$i</a></li>");
                }
                else {
                        print("<li><a href='?path=$enc_path&page=$i&limit=$limit'>$i</a></li>");
                }
        }
        if($page>=$file_count/$limit) {
                print("<li class='disabled'><a href='#'>&raquo;</a></li>");
        }
        else {
                $next=$page+1;
                print("<li><a href='?path=$enc_path&page=$next&limit=$limit'>&raquo;</a></li>");
        }
	print("</ul>");
	print("</div>");
}
?>
