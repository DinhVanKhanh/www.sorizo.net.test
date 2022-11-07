<?php
//call ajax from /lib/common.php
//drm and drmq redirect this file to use ajax from lib/common.php, then return data $_GET['auth]
require_once '../lib/login.php';
require_once '../lib/localstorage.php';
// ajax return data when success 
if (!empty($_GET['auth']) && $_GET['auth'] == "false") {
	$serial_no = str_replace("-", "", trim(@$_POST["serial_key"]));
	if ($serial_no != "") {
		WriteRequestedURL();
		header("location: /sorikuranet_callup.php?mode=drm&serial_key=" . $serial_no);
		exit;
	}
	CheckIntendedProduct("all");
}
?>