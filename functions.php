<?php

function isAuthorized($path, $listImg) {
	global $AUTH_MODE_ENABLED;
	if(isset($AUTH_MODE_ENABLED) && $AUTH_MODE_ENABLED == 0){
		return true;
	}
	$user = $_SERVER['REMOTE_USER'];
	if(!isset($auths)) {
		exec("/bin/grep -E '^$user:' auth.txt", $auths);
	}
	foreach ($auths as $i => $auth) {
		$auth=substr($auth,strlen($user)+1);
		if(startsWith($path, $auth)) {
			return true;
		}
		if(!$listImg && startsWith($auth, $path)) {
                        return true;
                }
	}
	return false;
}

function startsWith($str, $substr)
{
    return !strncmp($str, $substr, strlen($substr));
}
?>
